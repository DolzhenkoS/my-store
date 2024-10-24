import React from 'react';
import Product from './Product';

function ProductList({ products, addToCart }) {
    return (
        <div className="product-list">
{
    products.map(
        product=>(
            <Product key={product.id} product={product} addToCart={addToCart}></Product>
        )
    )
}
        </div>

    )

}

export default ProductList;