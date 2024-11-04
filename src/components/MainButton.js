import React from 'react';

function MainButton({onClick, currentPage}){

    let header = "";
    switch (currentPage) {
        case 0:
        header = "Открыть заказ";
          break;
        case 1:
            header = "Вернуться в каталог";
          break;
        case 2:
            header = "Назад";
          break;
        default:    
      }
    
  

return(

<button className="main-button" onClick={onClick}>{header}</button>
)
}

export default MainButton;