import React, {useEffect} from 'react';
import '../css/ProductDetail.css';

function ProductDetail({product, onClose}){
    useEffect(()=>{
        window.scrollTo(0, 0);
      });

      if (!product) return null;

    
    return(
        <div className="product-detail">
            <h2>{product.name}</h2>
            <img src={"https://ratsberry.sytes.net/api/img/img_"+product.article+".jpg"} alt={product.name}></img>
            <p>{product.description}</p>
            <a href={product.url}>Подробнее...</a>
            <p>Цена: {product.price} руб</p>
            {(product.quantity)?(
                <p>Заказано: {product.quantity} шт</p>
            ):(
                <p>Нет в заказе</p>
            )}
            

        </div>
    )

}

export default ProductDetail;