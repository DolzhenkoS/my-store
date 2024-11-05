import React from 'react';
import '../css/Product.css';


function Product({ product, addToCart, openDetail, selectedProduct }) {
    const imgUrl = "https://ratsberry.sytes.net/api/img/img_" + product.article + ".jpg";

    function onClick(e) {
        if (product === selectedProduct) {

            openDetail(product);
        }
    }

    return (
        <div key={product.article} className="product" >
            <img onClick={(e) => { onClick(e) }} src={imgUrl} alt={product.name}></img>
            <h2>{product.name}</h2>
            <p>{product.price} руб</p>
            <button onClick={() => { addToCart(product) }}>Добавить в заказ</button>
            <div className='icon'>{product.maxq - product.rezerv}</div>

        </div>
    )
}

export default Product;