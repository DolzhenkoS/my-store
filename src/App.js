import React, { useEffect, useState } from 'react';
import './App.css';
import ProductList from './components/ProductList';
import Cart from './components/Cart';
import Checkout from './components/Checkout';
import Header from './components/Header';
import Footer from './components/Footer';
import ProductDetail from './components/ProductDetail';
import Search from './components/Search';
import MainButton from './components/MainButton';
import Order from './components/Order';

const tg = window.Telegram.WebApp;


function App() {
  const [cart, setCart] = useState([]);
  const [products, setProducts] = useState([]);
  // const [filterProducts, setFilterProducts] = useState([]);
  const [isCheckingOut, setIsCheckingOut] = useState(false);
  const [selectedProduct, setSelectedProduct] = useState(null);
  const [user, setUser] = useState(null);
  const [filter, setFilter] = useState("");
  const [currentPage, setCurrentPage] = useState(0);

  const LIST_PAGE = 0;
  const ORDER_PAGE = 1;
  const DETAIL_PAGE = 2;

  useEffect(() => {
    let id = '1566876383';
    if (tg.user) { id = tg.user.id }
    const fetchProducts = async () => {
      try {
        const responce = await fetch("https://ratsberry.sytes.net/api/hs/getGoods.php?id=" + id, {
          method: "GET",
          mode: 'cors',
          headers: {
            'Content-Type': 'application/json',
          }
        });
        const data = await responce.json();
        setProducts(data);
        // setFilterProducts(data);
      } catch (error) {
        console.error('Ошибка при получении данных:', error);
      }
    }

    fetchProducts();
    if (window.Telegram) {
      window.Telegram.WebApp.ready();
      setUser(tg.initDataUnsafe.user);
      tg.expand();
      tg.disableVerticalSwipes();
    }

  }, []);


  //tg.MainButton.setText("Открыть заказ").show().onClick(() => { pushMainButton() })

  const addQuantity = async (art, q, count) => {
    let id = '1566876383';
    if (user) { id = user.id }
    try {
      const responce = await fetch("https://ratsberry.sytes.net/api/hs/setOrder.php?id=" + id + "&art=" + art + "&q=" + (q + count), {
        method: "GET",
        mode: 'cors',
        headers: {
          'Content-Type': 'application/json',
        }
      });
      const data = await responce.text();
      if (count > 0) {
        if (data === "ok") { if (user) { tg.showAlert('Товар добавлен') } else { alert('Товар добавлен') } } else {
          if (user) { tg.showAlert(data) } else { alert(data) };
        }

      }
    } catch (error) {
      //console.error('Ошибка при получении данных:', error);
    }
  }

  const getQuantity = async (art) => {
    let id = '1566876383';
    if (user) { id = user.id }
    try {
      //      const responce = await fetch("https://ratsberry.sytes.net/api/hs/getGoods.php?id="+id, {
      const responce = await fetch("https://ratsberry.sytes.net/api/hs/getOrderArt.php?id=" + id + "&art=" + art, {
        method: "GET",
        mode: 'cors',
        headers: {
          'Content-Type': 'application/json',
        }
      });
      const data = await responce.json();
      return data[0];
      // setFilterProducts(data);
    } catch (error) {
      //console.error('Ошибка при получении данных:', error);
    }
  }

  const pushMainButton = () => {
    //alert(currentPage);
    // setFilterProducts(products);
    setFilter("");
    switch (currentPage) {
      case LIST_PAGE:
        setCurrentPage(ORDER_PAGE);
        break;
      case ORDER_PAGE:
        setCurrentPage(LIST_PAGE);
        break;
      case DETAIL_PAGE:
        setCurrentPage(LIST_PAGE);
        break;
      default:
    }
  }


  function addToCart(product) {
    getQuantity(product.article).then(d => {
      try {
        addQuantity(product.article, Number(d.quantity), 1);
      } catch { }
    });
    //   addQuantity(product.article,Number(product.quantity),1);
    // let pcopy = [];
    // for (let i=0; i<products.length; i++){
    //   pcopy[i] = products[i];

    //   if (products[i].article === product.article) {
    //     pcopy[i].quantity++;
    //     pcopy[i].rezerv--;
    //   }
    // }
    // setProducts(pcopy);
  }

  function onDelete(art) {
    getQuantity(art).then(d => {
      try {
        addQuantity(art, Number(d.quantity), -1);
      } catch { return false }
    });

    return true;
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

  function handleCloseDetail() {
    setSelectedProduct(null);
  }

  function openDetail(product) {
    setSelectedProduct(product);
  }

  function onSetFilter(filter) {
    setFilter(filter);
    // setFilterProducts(products.filter(function (p) { return p.name.toUpperCase().includes(filter.toUpperCase()) || filter === "" }));
  }

  function onClose(){
    tg.close();
  }

  return (
    <div className="App">
      {user && <p>Hello, {user.first_name} !</p>}
      {/* <Header></Header> */}

      {(currentPage === LIST_PAGE) ? (
        <>
          <Search onSetFilter={onSetFilter} value={filter}></Search>
          <ProductList products={products.filter(function (p) { return p.name.toUpperCase().includes(filter.toUpperCase()) || filter === "" })} addToCart={addToCart} openDetail={openDetail} selectedProduct={selectedProduct}></ProductList>
        </>
      ) : (currentPage === ORDER_PAGE ? (
        <Order user={user} onDelete={onDelete} onClose={onClose}></Order>
      ) : (
        <></>
      )

      )}

      <MainButton onClick={pushMainButton} currentPage={currentPage}></MainButton>
      <Footer></Footer>
    </div>
  );
}

export default App;
