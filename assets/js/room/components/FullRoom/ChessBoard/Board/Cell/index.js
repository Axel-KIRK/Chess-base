import React from 'react';
import './cell.sass';
import classNames from 'classnames';

const Cell = ({ item, color, row, column }) => {
  const val= Number(row)+ Number(column);
  const className = classNames(
    'fas',
    {
      'fa-chess-pawn': item === 'P',
      'fa-chess-queen': item === 'Q',
      'fa-chess-rook': item === 'T',
      'fa-chess-knight': item === 'C',
      'fa-chess-king': item === 'K',
      'fa-chess-bishop': item === 'F',
      'white': color === '1',
      'black': color === '0'
    });
  const backGround = classNames (
    'cell',
    {
      'brown': (val % 2 === 1),
      'beige': !(val % 2 === 1)
    }
  );

  return (
    <div className={backGround}>
      <p className ={className}></p>
    </div>
  );
};

export default Cell;