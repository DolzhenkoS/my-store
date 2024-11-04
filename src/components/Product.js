import React from 'react';

function Product({ product, addToCart, openDetail, selectedProduct }) {
    const imgUrl = "https://ratsberry.sytes.net/api/img/img_" + product.article + ".jpg";

    function onClick(e) {
        if (product === selectedProduct) {

            openDetail(product);
        }
    }

    return (
        <div className="product">
            <img onClick={(e) => { onClick(e) }} src={imgUrl} alt={product.name}></img>
            <h2>{product.name}</h2>
            <p>Цена: {product.price} руб</p>
            <button onClick={() => { addToCart(product) }}>Добавить в заказ</button>

        </div>
    )
}

export default Product;