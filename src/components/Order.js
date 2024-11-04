import React, { useEffect, useState } from 'react';
import '../css/Order.css';

function Order({ user, onDelete }) {
    const [orderlist, setOrderlist] = useState([]);

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

        fetchProducts();
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
        if (onDelete(p.article)) {

            let pcopy = [];
            let len = 0;
            for (let i = 0; i < orderlist.length; i++) {

                if ((Number(orderlist[i].quantity) === 1) && (orderlist[i].article === p.article)) {
                    continue;
                }

                len = pcopy.push(orderlist[i]);

                if (orderlist[i].article === p.article) {
                    pcopy[len - 1].quantity--;
                }
            }
            setOrderlist(pcopy);
        }
        //        fetchProducts();
    }

    return (
        <div className='order'>
            <h1>Номер заказа: {1}</h1>
            <h2>Оплачено 222 руб</h2>

            <table cellPadding={5}>
                {
                    orderlist.map(
                        p => (<tr>

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

            </table>

            <h2>Всего количество {sumQ} шт</h2>
            <h2>На сумму {sumS} руб</h2>
            <button onClick={alert('Заказ оплачен')}>Оплатить заказ</button>
        </div>

    );


}

export default Order