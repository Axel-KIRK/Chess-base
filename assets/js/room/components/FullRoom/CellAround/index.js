import React from 'react';
import './cellAround.sass';
import CellAroundOne from './CellAroundOne.js';

const CellAround = ({myColor}) => {
  let grid;
  const aroundBoard = [
    {'1/1': ''}, {'1/2': 'H'}, {'1/3': 'G'}, {'1/4': 'F'}, {'1/5': 'E'}, {'1/6': 'D'}, {'1/7': 'C'}, {'1/8': 'B'}, {'1/9': 'A'}, {'1/10': ''},
    {'2/1': '1'}, {'2/2': ''}, {'2/3': ''}, {'2/4': ''}, {'2/5': ''}, {'2/6': ''}, {'2/7': ''}, {'2/8': ''}, {'2/9': ''}, {'2/10': ''},
    {'3/1': '2'}, {'3/2': ''}, {'3/3': ''}, {'3/4': ''}, {'3/5': ''}, {'3/6': ''}, {'3/7': ''}, {'3/8': ''}, {'3/9': ''}, {'3/10': ''},
    {'4/1': '3'}, {'4/2': ''}, {'4/3': ''}, {'4/4': ''}, {'4/5': ''}, {'4/6': ''}, {'4/7': ''}, {'4/8': ''}, {'4/9': ''}, {'4/10': ''},
    {'5/1': '4'}, {'5/2': ''}, {'5/3': ''}, {'5/4': ''}, {'5/5': ''}, {'5/6': ''}, {'5/7': ''}, {'5/8': ''}, {'5/9': ''}, {'5/10': ''},
    {'6/1': '5'}, {'6/2': ''}, {'6/3': ''}, {'6/4': ''}, {'6/5': ''}, {'6/6': ''}, {'6/7': ''}, {'6/8': ''}, {'6/9': ''}, {'6/10': ''},
    {'7/1': '6'}, {'7/2': ''}, {'7/3': ''}, {'7/4': ''}, {'7/5': ''}, {'7/6': ''}, {'7/7': ''}, {'7/8': ''}, {'7/9': ''}, {'7/10': ''},
    {'8/1': '7'}, {'8/2': ''}, {'8/3': ''}, {'8/4': ''}, {'8/5': ''}, {'8/6': ''}, {'8/7': ''}, {'8/8': ''}, {'8/9': ''}, {'8/10': ''},
    {'9/1': '8'}, {'9/2': ''}, {'9/3': ''}, {'9/4': ''}, {'9/5': ''}, {'9/6': ''}, {'9/7': ''}, {'9/8': ''}, {'9/9': ''}, {'9/10': ''},
    {'10/1': ''}, {'10/2': ''}, {'10/3': ''}, {'10/4': ''}, {'10/5': ''}, {'10/6': ''}, {'10/7': ''}, {'10/8': ''}, {'10/9': ''}, {'10/10': ''},
  ];
  const aroundBoard2 = [
    {'1/1': ''}, {'1/2': 'A'}, {'1/3': 'B'}, {'1/4': 'C'}, {'1/5': 'D'}, {'1/6': 'E'}, {'1/7': 'F'}, {'1/8': 'G'}, {'1/9': 'H'}, {'1/10': ''},
    {'2/1': '8'}, {'2/2': ''}, {'2/3': ''}, {'2/4': ''}, {'2/5': ''}, {'2/6': ''}, {'2/7': ''}, {'2/8': ''}, {'2/9': ''}, {'2/10': ''},
    {'3/1': '7'}, {'3/2': ''}, {'3/3': ''}, {'3/4': ''}, {'3/5': ''}, {'3/6': ''}, {'3/7': ''}, {'3/8': ''}, {'3/9': ''}, {'3/10': ''},
    {'4/1': '6'}, {'4/2': ''}, {'4/3': ''}, {'4/4': ''}, {'4/5': ''}, {'4/6': ''}, {'4/7': ''}, {'4/8': ''}, {'4/9': ''}, {'4/10': ''},
    {'5/1': '5'}, {'5/2': ''}, {'5/3': ''}, {'5/4': ''}, {'5/5': ''}, {'5/6': ''}, {'5/7': ''}, {'5/8': ''}, {'5/9': ''}, {'5/10': ''},
    {'6/1': '4'}, {'6/2': ''}, {'6/3': ''}, {'6/4': ''}, {'6/5': ''}, {'6/6': ''}, {'6/7': ''}, {'6/8': ''}, {'6/9': ''}, {'6/10': ''},
    {'7/1': '3'}, {'7/2': ''}, {'7/3': ''}, {'7/4': ''}, {'7/5': ''}, {'7/6': ''}, {'7/7': ''}, {'7/8': ''}, {'7/9': ''}, {'7/10': ''},
    {'8/1': '2'}, {'8/2': ''}, {'8/3': ''}, {'8/4': ''}, {'8/5': ''}, {'8/6': ''}, {'8/7': ''}, {'8/8': ''}, {'8/9': ''}, {'8/10': ''},
    {'9/1': '1'}, {'9/2': ''}, {'9/3': ''}, {'9/4': ''}, {'9/5': ''}, {'9/6': ''}, {'9/7': ''}, {'9/8': ''}, {'9/9': ''}, {'9/10': ''},
    {'10/1': ''}, {'10/2': ''}, {'10/3': ''}, {'10/4': ''}, {'10/5': ''}, {'10/6': ''}, {'10/7': ''}, {'10/8': ''}, {'10/9': ''}, {'10/10': ''},
  ];
 
  myColor == '1' ? grid = aroundBoard2 : grid = aroundBoard;
  return (
    <div className='container-cellAround'>
      {grid.map(cell => (
        <CellAroundOne item={cell[Object.keys(cell)[0]][0]}
          key= { Object.keys(cell)[0] }
        />
      ))}
    </div>
  );
};

export default CellAround;