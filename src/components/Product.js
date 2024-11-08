import React from 'react';
import '../css/Product.css';


function Product({ product, addToCart, openDetail, selectedProduct }) {
    const imgUrl = "https://ratsberry.sytes.net/api/img/img_" + product.article + ".jpg";

    function onClick(e) {
        openDetail(product);
    }

    function info(product) {
        if (product.maxq - product.rezerv > 0) {
            return "Доступно: " + (product.maxq - product.rezerv) + " шт"
        } else {
            return "Не доступно"
        }
        // (product.maxq - product.rezerv>0)? "Доступно: "+product.maxq - product.rezerv:"Нет в наличии"
    }

    return (
        <div key={product.article} className="product" >
            <img onClick={(e) => { onClick(e) }} src={imgUrl} alt={product.name}></img>
            <h2>{product.name}</h2>
            <h3>{info(product)}</h3>
            <p>{product.price} руб</p>
            {((product.maxq - product.rezerv) > 0) ? (
                <button  onClick={() => { addToCart(product) }}>Добавить в заказ</button>
            ) : (
                <button className='add-button' onClick={() => { addToCart(product) }}>Добавить в заказ</button>
            )}
            {/* <div className='icon'>{product.maxq - product.rezerv}</div> */}

        </div>
    )
}

export default Product;