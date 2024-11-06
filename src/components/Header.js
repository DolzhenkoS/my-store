import React from 'react';
import '../css/Header.css';

function Header({user,handleClick}){
return (
    <header>
      {user && <div>Заказчик: {user.first_name} {user.last_name}</div>}
      {/* <h2>Розы, осень 2025</h2> */}
      <a onClick={handleClick}>Укажите номер телефона</a>
    </header>
)
}

export default Header;