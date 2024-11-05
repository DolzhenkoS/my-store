import React from 'react';
import Product from './Product';

function ProductList({ products, addToCart, openDetail,selectedProduct }) {

    return (
        <div className="product-list">
{
    products.map(
        product=>(
            <Product key={product.article} product={product} addToCart={addToCart} openDetail={openDetail} selectedProduct = {selectedProduct}></Product>
        )
    )
}
        </div>

    )

}

export default ProductList;