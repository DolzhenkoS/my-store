import React from 'react';

function ProductDetail({product, onClose}){
    if (!product) return null;

    return(
        <div className="product-detail">
            <h2>{product.name}</h2>
            <p>{product.description}</p>
            <p>Цена: {product.price}</p>
            <button onClick={onClose}>Закрыть</button>

        </div>
    )

}

export default ProductDetail;