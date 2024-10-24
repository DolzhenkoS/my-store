import React,{useState} from 'react';
import './App.css';
import ProductList from './components/ProductList';
import Cart from './components/Cart';

function App() {
const [cart,setCart] = useState([]);

const products = [
  {id:1,name:'Апельсины',price: 10.99},
  {id:2,name:'Мандарины',price: 12.99},
  {id:3,name:'Яблоки',price: 20.99},
  {id:4,name:'Огурцы',price: 35.99},
  {id:5,name:'Помидоры',price: 40.99},
];

function addToCart(product){
  setCart([...cart,product])
}


  return (
    <div className="App">
      <h1>Онлайн магазин</h1>
      <ProductList products={products} addToCart={addToCart}></ProductList>
      <Cart cartItems={cart}></Cart>
    </div>
  );
}

export default App;
