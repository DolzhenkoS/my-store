import React from 'react';

function Cart({ cartItems, removeFromCart }) {
    return (
        <div className="cart">
            <h2>Корзина</h2>
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
                                        <button onClick={() => removeFromCart(item)}>Удалить</button>
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

export default Cart;