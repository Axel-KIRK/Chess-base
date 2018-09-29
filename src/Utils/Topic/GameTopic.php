<?php

namespace App\Utils\Topic;

use Gos\Bundle\WebSocketBundle\Topic\TopicInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Gos\Bundle\WebSocketBundle\Router\WampRequest;
use Gos\Bundle\WebSocketBundle\Client\ClientManipulatorInterface;
use App\Entity\Game;
use App\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Utils\GameTopicMessage;
use App\Entity\Stats;
use App\Utils\GameTopicTools;
use Gos\Bundle\WebSocketBundle\Topic\ConnectionPeriodicTimer;
use Gos\Bundle\WebSocketBundle\Topic\SecuredTopicInterface;
use Gos\Bundle\WebSocketBundle\Server\Exception\FirewallRejectionException;

class GameTopic implements TopicInterface, SecuredTopicInterface
{
    protected $clientManipulator;
    private $doctrine;
    private $gameTopicMessage;
    private $gameTopicTools;
    private $topicTimer;
    

    /**
     * @param ClientManipulatorInterface $clientManipulator
     */
    public function __construct(ClientManipulatorInterface $clientManipulator, ManagerRegistry $doctrine, GameTopicMessage $gameTopicMessage, GameTopicTools $gameTopicTools)
    {
        $this->clientManipulator = $clientManipulator;
        $this->doctrine = $doctrine;
        $this->gameTopicMessage = $gameTopicMessage;
        $this->gameTopicTools = $gameTopicTools;
    }


    public function secure(ConnectionInterface $connection = null, Topic $topic, WampRequest $request, $payload = null, $exclude = null, $eligible = null, $provider = null)
    {
        // check input data to verify if connection must be blocked
        if ($this->clientManipulator->getClient($connection)->getUsername() !== $request->getAttributes()->get('playerOne')
            && $this->clientManipulator->getClient($connection)->getUsername() !== $request->getAttributes()->get('playerTwo')) {
                dump('nope!');
                throw new FirewallRejectionException();
        } 
    }

            
    /**
     * This will receive any Subscription requests for this topic.
     *
     * @param ConnectionInterface $connection
     * @param Topic $topic
     * @param WampRequest $request
     * @return void
     */
    public function onSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
        if (null !== $this->topicTimer && $this->topicTimer->isPeriodicTimerActive($this->clientManipulator->getClient($connection)->getUsername())) {
            $this->gameTopicMessage->leaverTimer($topic , 'stop');
            dump('revenu à temps, ouf !');
            $this->topicTimer->cancelPeriodicTimer($this->clientManipulator->getClient($connection)->getUsername());
        }
        $topic->autoDelete = true; 
        
        $gameId = $request->getAttributes()->get('gameId');
        $game = $this->doctrine->getRepository(Game::class)->findOneById($gameId);
        if (null === $game) {       
            return;
        }
        $board = $game->getChessBoard();
        $user = $this->clientManipulator->getClient($connection);
        $movementList = $game->getMovementList();
     


        //on verifie que l'utilisateur est bien connecté.Un user non connecté === string;
        if (!is_object($user)) {
            dump('user not connected');
            return;
        }
          

        $playerName = $user->getUsername();
        $playerColor = $playerName === $request->getAttributes()->get('playerOne') ? 'white' : 'black';
        $playerSessionId = $this->clientManipulator->findByUsername($topic, $playerName)['connection']->WAMP->sessionId;

        
        //on envoie au joueur qui arrive sur la page:
        //le board :
        $this->gameTopicMessage->lastBoard($topic, $board, $playerSessionId);
        //la liste des mouvements déjà effectué dans la game :
        $this->gameTopicMessage->movementList($topic, $movementList);
         
        


        //verification et envoi du message can play true ou false au client selon si c'est leur tour de jouer.
        if ($user->getId() === $game->getPlayerWhoCanPlay()->getId()) {
            $this->gameTopicMessage->canPlay(true, $topic, $playerSessionId);
            return;
        }
        if ($user->getId() !== $game->getPlayerWhoCanPlay()->getId()) {
            $this->gameTopicMessage->canPlay(false, $topic, $playerSessionId);
            return;
        }
    }

    /**
     * This will receive any UnSubscription requests for this topic.
     *
     * @param ConnectionInterface $connection
     * @param Topic $topic
     * @param WampRequest $request
     * @return void
     */
    public function onUnSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
        $gameId = $request->getAttributes()->get('gameId');
        $game = $this->doctrine->getRepository(Game::class)->findOneById($gameId);
        if (null === $game) {       
            return;
        }
        $user = $this->clientManipulator->getClient($connection);
        $playerName = $user->getUsername();
        $opponentName =  $playerName === $request->getAttributes()->get('playerOne') ? $request->getAttributes()->get('playerTwo') : $request->getAttributes()->get('playerOne');
        
        if(null === $this->topicTimer){
            $this->topicTimer = $connection->PeriodicTimer;
        }

        if (null !== $game) {
           
            $this->gameTopicMessage->leaverTimer($topic , 'start');

            $this->topicTimer->addPeriodicTimer($playerName, 20, function () use ($topic, $connection,$playerName, $opponentName, $game) {
                $this->gameTopicTools->endGameDbEntry($playerName, $opponentName, $game, 'surrender');
                $this->gameTopicTools->endGameDbEntry($opponentName, $playerName, $game, 'win');
                $this->topicTimer->cancelPeriodicTimer($playerName);
                if ($this->topicTimer->isPeriodicTimerActive($opponentName)) {
                    $this->topicTimer->cancelPeriodicTimer($opponentName);
                }
                dump('partie terminé !');
            });
        }

    }


    /**
     * This will receive any Publish requests for this topic.
     *
     * @param ConnectionInterface $connection
     * @param Topic $topic
     * @param WampRequest $request
     * @param $event
     * @param array $exclude
     * @param array $eligible
     * @return mixed|void
     */
    public function onPublish(ConnectionInterface $connection, Topic $topic, WampRequest $request, $event, array $exclude, array $eligible)
    {
        $topic->autoDelete = true;

        $gameId = $request->getAttributes()->get('gameId');
        $game = $this->doctrine->getRepository(Game::class)->findOneById($gameId);
        $user = $this->clientManipulator->getClient($connection);      
       
       //si la game n'existe pas on return;
        if (null === $game) {       
            return;
        }

        $board = $game->getChessBoard();  
    
        //verification si l'utilisateur est bien connecté/identifié :
        if (!is_object($user)) {
            dump('user not connected');
            return;
        }
 
        $playerName = $user->getUsername();
        $playerColor = $playerName === $request->getAttributes()->get('playerOne') ? 'white' : 'black';
        $playerSessionId = $this->clientManipulator->findByUsername($topic, $playerName)['connection']->WAMP->sessionId;
 
        $opponentName =  $playerName === $request->getAttributes()->get('playerOne') ? $request->getAttributes()->get('playerTwo') : $request->getAttributes()->get('playerOne');
        $opponentColor = $playerColor === 'white' ? 'black' : 'white';
        $opponentConnectionObject = $this->clientManipulator->findByUsername($topic, $opponentName)['connection'];
        
        
        //verification si l'adversaire est bien connecté/identifié :
        if (!is_object($opponentConnectionObject)) {
            $this->gameTopicMessage->lastBoard($topic, $board, $playerSessionId);
            $this->gameTopicMessage->notConnectedOpponent($topic, $playerSessionId);
            return;
        }
        $opponentSessionId = $opponentConnectionObject->WAMP->sessionId;

        
        //envoi de l'etat du board au 2 joueurs :
        $this->gameTopicMessage->lastBoard($topic, $board, $playerSessionId, $opponentSessionId);
        
        
        //on verifie qu'il s'agit du tour du joueur :
        if ($user->getId() !== $game->getPlayerWhoCanPlay()->getId()) {
            $this->gameTopicMessage->notYourTurn($topic, $playerSessionId);
            return;
        }
    
        //on recupere la piece que le client essaye de deplacer dans l'objet board;
        $piece = $board->getPiece($event['movement']['old']);

        //position d'arrivé du mouvement essayé:
        $arrayPos = explode('/', $event['movement']['new']);
        $newPosY = intval($arrayPos[0]);
        $newPosX = intval($arrayPos[1]);
        
        
        
        //si la piece n'est pas presente dans board on renvoi une erreur au client :
        if (null === $piece) {
            $this->gameTopicMessage->notExistingPiece($topic, $playerSessionId);
            return;
        }
        
        //si la piece n'est pas de la couleur du joueur, erreur :
        if ($playerColor !== $piece->getColor()) {
            $this->gameTopicMessage->notYourPiece($topic, $playerSessionId);
            return;
        }
                 
        //verification si le mouvement est valide.
        if (false === $piece->canDothismove($board, $newPosX, $newPosY)) {
            $this->gameTopicMessage->invalidMovement($topic, $playerSessionId);
            return;
        }
                
        //on sauvegarde les infomations du mouvement (utile pour annuler le mouvement si les prochaines verification sont fausses)
        $savedMove = $board->saveMoveInfo($piece, $newPosX, $newPosY);

        //on 'deplace la piece' dans board :
        $board->movePiece($piece, $event['movement']['new']);

        //si le mouvement a mis en echec notre roi : message d'erreur, et on annule le mouvement.
        if (true === $board->thisKingIsCheck($playerColor)) {
            $this->gameTopicMessage->selfCheck($topic, $playerSessionId);
            $board->cancelMove($savedMove);
            return;
        }

        $movementList = $game->getMovementList();
        $movementList[] = $event['newPositions'];
        $game->setMovementList($movementList);
             
        $game->setPlayerWhoCanPlay($this->doctrine->getRepository(User::class)->findOneByUsername($opponentName));
        $this->gameTopicMessage->movementList($topic, $movementList);
        $this->gameTopicMessage->endTurn($topic, $event, $playerSessionId, $opponentSessionId);

          //verif si le roi adverse est en echec apres le mouvement.
        if (true === $board->thisKingIsCheck($opponentColor)) {
           
            //verif si il y'a echec et mat !
            if (true === $board->thisKingIsMat($opponentColor)) {                
                $this->gameTopicMessage->checkMate($topic, $playerSessionId, $opponentSessionId);
                //si il y'a echec et mat, on enregistre la game (dans GameOver) et les stats en Bdd :
                $this->gameTopicTools->endGameDbEntry($playerName, $opponentName, $game, 'win');
                $this->gameTopicTools->endGameDbEntry($opponentName, $playerName, $game, 'lose');
                
                return;
            }

            $this->gameTopicMessage->check($topic, $playerSessionId, $opponentSessionId);
        }
    }

    /**
    * Like RPC is will use to prefix the channel
    * @return string
    */
    public function getName()
    {
        return 'game.topic';
    }
}
