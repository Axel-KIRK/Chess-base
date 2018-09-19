<?php

namespace App\Models;

class Rook extends Piece
{
    public function canDoThisMove(Board $board, int $newPosX, int $newPosY) : bool
    {
        //la nouvelle case existe :
        if (false === $board->newPosExist($newPosX, $newPosY)) {
            return false;
        }
        //mouvement non null?
        if (false === $this->hasMoved($newPosX, $newPosY)) {
            return false;
        }
        //pas de piece allié à l'arrivé
        if (true === $board->hasAllyPieceOnCase($newPosX, $newPosY, $this->getColor())) {
            return false;
        }
        //mouvement vertical ou mouvement horizontal? pas de pieces entre le depart et l'arrivé?
        if (false === $this->isDoingValideVerticalMovement($board, $newPosX, $newPosY)
        && false ===  $this->isDoingValideHorizontalMovement($board, $newPosX, $newPosY)) {
            return false;
        }
        return true;
    }
}