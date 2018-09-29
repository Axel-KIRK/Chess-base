<?php

namespace App\Utils;

use App\Entity\User;
use App\Entity\Game;
use App\Entity\GameOver;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\Stats;

class GameTopicTools
{
    private $doctrine;


    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function endGameDbEntry(string $playerName, string $opponentName, Game $game, string $result)
    {
        $player = $this->doctrine->getRepository(User::class)->findOneByUsername($playerName);
        $opponent = $this->doctrine->getRepository(User::class)->findOneByUsername($opponentName);

        $playerGameOver = new GameOver();

        $playerGameOver->setPlayer($player)
            ->setOpponent($opponent)
            ->setMovementList($game->getMovementList());
            
        $isWinner = $result === 'win' ? true : false;
       
        $playerGameOver->setIsWinner($isWinner);
        
        $this->doctrine->getManager()->persist($playerGameOver);

        $playerStats = $player->getStats();
       
        if( null === $playerStats){
            $playerStats = new Stats();
        }

        $playerStats->setNbGame($playerStats->getNbGame() + 1);

        if ($result === 'win') {
            $playerStats->setNbWin($playerStats->getNbWin() + 1);
        }
        if ($result === 'lose') {
            $playerStats->setNbLose($playerStats->getNbLose() +1);
        }
        if ($result === 'surrender') {
            $playerStats->setNbSurrender($playerStats->getNbSurrender() + 1);
        }

        $this->doctrine->getManager()->persist($playerStats);

        $player->setStats($playerStats);
       
        $this->doctrine->getManager()->remove($game);

        $this->doctrine->getManager()->flush();
        $this->doctrine->getManager()->refresh($player);
        $this->doctrine->getManager()->refresh($opponent);
    }
}
