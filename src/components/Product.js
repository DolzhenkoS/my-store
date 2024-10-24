import React from 'react';

function Product({ product, addToCart }) {
    const imgUrl = "https://ratsberry.sytes.net/api/img/img_"+product.article+".jpg";
    return (
        <div className="product">
            <img src={imgUrl} alt={product.name}></img>
            <h2>{product.name}</h2>
            <p>Цена: {product.price} руб</p>
            <button onClick={() => { addToCart(product) }}>Добавить в корзину</button>

        </div>
    )
}

export default Product;