import React, { useEffect, useState } from 'react';
import './App.css';
import ProductList from './components/ProductList';
import Cart from './components/Cart';

function App() {
  const [cart, setCart] = useState([]);
  const [products, setProducts] = useState([]);

  // const products = [
  //   {id:1,name:'Апельсины',price: 10.99},
  //   {id:2,name:'Мандарины',price: 12.99},
  //   {id:3,name:'Яблоки',price: 20.99},
  //   {id:4,name:'Огурцы',price: 35.99},
  //   {id:5,name:'Помидоры',price: 40.99},
  // ];

  useEffect(() => {
    const fetchProducts = async () => {
      try {
        const responce = await fetch("https://ratsberry.sytes.net/api/hs/getGoods.php", {
          method: "GET",
          mode: 'cors',
          headers: {
            'Content-Type': 'application/json',
          }
        });
        const data = await responce.json();
        setProducts(data);
      } catch (error) {
        console.error('Ошибка при получении данных:', error);
      }
    }

    fetchProducts();
  }, []);

  function addToCart(product) {
    setCart([...cart, product])
  }

  function removeFromCart(productToRemove) {
    setCart(cart.filter(
      product => product.article !== productToRemove.article
    ));
  }

  return (
    <div className="App">
      <h1>Онлайн магазин</h1>
      <ProductList products={products} addToCart={addToCart}></ProductList>
      <Cart cartItems={cart} removeFromCart={removeFromCart}></Cart>
    </div>
  );
}

export default App;
