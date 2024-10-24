import React, { useState } from 'react';

function Checkout({ cartItems, onCheckOut }) {
    const [name, setName] = useState('');
    const [address, setAddress] = useState('');

    function handleSubmit(e) {
        e.preventDefault();
        if (!name || !address) {
            alert('Заполните все поля !');
            return;
        }

        //Здесь будет отправка данных на сервер
        onCheckOut();
        alert('Спасибо за заказ !');
    }

    return (
        <div className="checkout">
            <h2>Оформление заказа</h2>
            <form onSubmit={handleSubmit}>
                <div>
                    <label>Имя:</label>
                    <input type="text" value={name} onChange={(e) => setName(e.target.value)} required></input>
                </div>
                <div>
                    <label>Адрес доставки:</label>
                    <input type="text" value={address} onChange={(e) => setAddress(e.target.value)} required></input>
                </div>
                <button type="submit">Оформить заказ</button>
            </form>

            <h3>Товары в корзине:</h3>
            {
                cartItems.length === 0 ? (
                    <p>Корзина пуста</p>
                ) : (
                    <ul>
                        {
                            cartItems.map(
                                (item, index) => (
                                    <li key={index}>
                                        {item.name} - {item.price}
                                    </li>
                                )
                            )
                        }
                    </ul>
                )
            }
        </div>
    )

}

export default Checkout;