<?php

namespace App\Utils;

class GameTopicMessage
{
    public function notYourTurn($topic, $playerSessionId)
    {
        $topic->broadcast(
            [
                'error' => 'Ce n\'est pas votre tour.',
            ],
            array(),
            array($playerSessionId)
            );
    }

    public function endTurn($topic, $event, $playerSessionId, $opponentSessionId)
    {
        $topic->broadcast(
            [
                'canPlay' => false,
                'message' => 'Tu as finis ton tour',
            ],
            array(),
            array($playerSessionId)
            );

        $topic->broadcast(
            [
                'canPlay'=> true,
                'message' => 'Ton adversaire à joué, à ton tour',
                'movement' => $event,
            ],
            array(),
            array($opponentSessionId)
            );
    }

    public function checkMate($topic, $playerSessionId, $opponentSessionId)
    {
        $topic->broadcast(
            [
                'endGame' => 'Echec et mat, vous avez gagné !',
            ],
            array(),
            array($playerSessionId)
            );
        $topic->broadcast(
            [
                'endGame' => 'Echec et mat, vous avez perdu !',
            ],
            array(),
            array($opponentSessionId)
            );
    }

    public function check($topic, $playerSessionId, $opponentSessionId)
    {
        $topic->broadcast(
            [
                'echec' => ' Vous avez mis le roi adverse en echec !',
            ],
            array(),
            array($playerSessionId)
            );
        $topic->broadcast(
            [
                'echec' => 'votre roi est en echec !',
            ],
            array(),
            array($opponentSessionId)
            );
    }

    public function selfCheck($topic, $playerSessionId)
    {
        $topic->broadcast(
            [
                'error' => 'Votre roi est en echec !!',
            ],
            array(),
            array($playerSessionId)
            );
    }

    public function invalidMovement($topic, $playerSessionId)
    {
        $topic->broadcast(
            [
                'error' => 'Ce mouvement est incorect !',
            ],
            array(),
            array($playerSessionId)
            );
    }

    public function notYourPiece($topic, $playerSessionId)
    {
        $topic->broadcast(
            [
                'error' => 'Ce n\'est pas une de vos pieces!',
            ],
            array(),
            array($playerSessionId)
            );
    }

    public function notExistingPiece($topic, $playerSessionId)
    {
        $topic->broadcast(
            [
                'error' => 'Cette piece n\'existe pas !',
            ],
            array(),
            array($player['connection']->WAMP->sessionId)
            );
    }

    public function canPlay(bool $bool, $topic, $playerSessionId)
    {
        $topic->broadcast(
            [
                'canPlay' => $bool,
            ],
            array(),
            array($playerSessionId)
            );
    }

    public function notConnectedOpponent($topic, $playerSessionId)
    {
        $topic->broadcast(
            [
                'error' => "Adversaire non connecté !",
            ],
            array(),
            array($playerSessionId)
            );
    }

    public function lastBoard($topic, $board, $playerSessionId, $opponentSessionId = null)
    {
        $topic->broadcast(
            [
                'lastBoard' => $board->convert(),
            ],
            array(),
            array($playerSessionId, $opponentSessionId)
            );
    }

    public function movementList($topic,$movementList)
    {
        $topic->broadcast(['movementList' => $movementList]);
    }

    public function leaverTimer($topic, string $action)
    {
        $topic->broadcast(['timer' => $action]);
    }
}
