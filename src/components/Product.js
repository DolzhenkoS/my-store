import React from 'react';

function Product({ product, addToCart }) {
    return (
        <div className="product">
            <h2>{product.name}</h2>
            <p>Цена: {product.price} руб</p>
            <button onClick={() => { addToCart(product) }}>Добавить в корзину</button>

        </div>
    )
}

export default Product;