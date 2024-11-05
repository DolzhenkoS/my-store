import React, { useEffect, useState } from 'react';
import '../css/Order.css';

function Order({ user, onDelete, onClose }) {
    const [orderlist, setOrderlist] = useState([]);
    const [orderInfo, setOrderInfo] = useState([]);

    useEffect(() => {
        let id = '1566876383';
        if (user) { id = user.id }
        const fetchProducts = async () => {
            try {
                const responce = await fetch("https://ratsberry.sytes.net/api/hs/getOrderList.php?id=" + id, {
                    method: "GET",
                    mode: 'cors',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                });
                const data = await responce.json();
                setOrderlist(data);
            } catch (error) {
                console.error('Ошибка при получении данных:', error);
            }
        }

        const getOrderInfo = async () => {
            try {
                const responce = await fetch("https://ratsberry.sytes.net/api/hs/getOrderInfo.php?id=" + id, {
                    method: "GET",
                    mode: 'cors',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                });
                const data = await responce.json();
                setOrderInfo(data[0]);
            } catch (error) {
                console.error('Ошибка при получении данных:', error);
            }
        }

        fetchProducts();
        getOrderInfo();
    }, []);




    const sumQ = orderlist.reduce((sum, ob) => {
        return sum + Number(ob.quantity)
    }
        , 0)

    const sumS = orderlist.reduce((sum, ob) => {
        return sum + Number(ob.sum)
    }
        , 0)

    function onClickDelete(p) {
        let newOrderInfo = orderInfo;

        if (onDelete(p.article,p.name)) {

            let pcopy = [];
            let len = 0;
            for (let i = 0; i < orderlist.length; i++) {

                if ((Number(orderlist[i].quantity) === 1) && (orderlist[i].article === p.article)) {
                    newOrderInfo.summa -= p.price; 
                    continue;
                }

                len = pcopy.push(orderlist[i]);

                if (orderlist[i].article === p.article) {
                    pcopy[len - 1].quantity--;
                    pcopy[len - 1].sum = pcopy[len - 1].quantity*p.price;
                    newOrderInfo.summa -= p.price; 
                }
            }
            setOrderlist(pcopy);
            setOrderInfo(newOrderInfo);
        }
        //        fetchProducts();
    }

    function onPay(user) {
        let pin = orderInfo.number;
        let message = 'Вы можете оплатить свой заказ через СБП по телефону ☎ <a href="tel:%2B79788113868">%2B79788113868 (РНКБ) </a>\n<u>В сообщении получателю укажите код ☞ </u> <b>' + pin + '</b>\n' +
          'Оплата будет привязана к заказу в течение суток. Все вопросы можно уточнить по указанному номеру ☎ или в <a href="https://t.me/krisenok_ratsberry">чате</a>';
    
        sendMessage(user.id, message);
        onClose();
      }
    
  //ВЗАИМОДЕЙСТВИЕ С КЛИЕНТОМ
  //Отправка сообщения клиенту
  function sendMessage(chatid, text) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'https://ratsberry.sytes.net/api/bot/sendmessage.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    //    var s = 'chatid=' + chatid + '&text=' + encodeURIComponent(text);
    var s = 'chatid=' + chatid + '&text=' + (text);
    xhr.send(s);
  }

function getStatusPay(){
    if (orderInfo.summa > orderInfo.pay) {
        return("Ждет доплаты " + (orderInfo.summa - orderInfo.pay) + " руб");
//        pay.style = "color: red;";
    }
    if (orderInfo.pay == 0) {
        return("Ждет оплаты " + orderInfo.summa + " руб");
//        pay.style = "color: red;";
    }
    if (orderInfo.summa < orderInfo.pay) {
        return("Переплата " + (orderInfo.pay - orderInfo.summa) + " руб");
//        pay.style = "color: green;";
    }
    if (orderInfo.summa == orderInfo.pay) {
        return("Заказ оплачен");
//        pay.style = "color: green;";
    }
}


    return (
        <div className='order'>

            <h1>Номер заказа: {orderInfo.number}</h1>
            <h2>{getStatusPay()}</h2>

            <table cellPadding={5}>
                <tbody>
                    {
                        orderlist.map(
                            p => (<tr key={p.article}>

                                <td>
                                    <img src={'https://ratsberry.sytes.net/api/img/img_' + p.article + '.jpg'} alt=""></img>
                                </td>
                                <td colSpan={4}>
                                    {p.article + "." + p.name}
                                </td>
                                <td>
                                    {p.quantity + " шт"}
                                </td>
                                {/* <td>
                                    {p.price+" руб"}
                                </td> */}
                                <td>
                                    {p.sum + " руб"}
                                </td>
                                <td>
                                    <button onClick={() => { onClickDelete(p) }}>X</button>
                                </td>

                            </tr>
                            )
                        )
                    }
                </tbody>
            </table>

            <h2>Всего количество {sumQ} шт</h2>
            <h2>На сумму {sumS} руб</h2>
            <button onClick={() => { onPay(user) }}>Оплатить заказ</button>
        </div>

    );


}

export default Order