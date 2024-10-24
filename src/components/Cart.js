import React from 'react';

function Cart({ cartItems }) {
    return (
        <div className="cart">
            <h2>Корзина</h2>
            {
                cartItems.length === 0 ? (
                    <p>Корзина пуста</p>
                ) : (
                    cartItems.map(
                        (item, index) => (
                            <div key={index}>
                                {item.name} - {item.price} руб
                            </div>
                        )
                    )
                )
            }

        </div>
    )
}

export default Cart;