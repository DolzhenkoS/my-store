import React, { useEffect, useState } from 'react';
import './App.css';
import ProductList from './components/ProductList';
import Cart from './components/Cart';
import Checkout from './components/Checkout';

function App() {
  const [cart, setCart] = useState([]);
  const [products, setProducts] = useState([]);
  const [isCheckingOut, setIsCheckingOut] = useState(false);


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

  function handleCheckout() {
    //Очистка корзины после оформления заказа
    setCart([]);
    setIsCheckingOut(false);

  }

  return (
    <div className="App">
      <h1>Онлайн магазин</h1>
      {
        !isCheckingOut ? (
          <>
            <ProductList products={products} addToCart={addToCart}></ProductList>
            <Cart cartItems={cart} removeFromCart={removeFromCart}></Cart>
            <button onClick={() =>  setIsCheckingOut(true) }>Оформить заказ</button>
          </>
        ) : (
          <Checkout cartItems={cart} onCheckOut={handleCheckout}></Checkout>
        )
      }

    </div>
  );
}

export default App;
