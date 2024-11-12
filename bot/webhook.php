<?php
//include_once ('../DB/connect.php');

//Подключаем файл с настройками
//require_once __DIR__ . '/init.php';
include_once('init.php');

//Подключаем класс для работы с API
//require_once __DIR__ . '/bot.php';
include_once('bot.php');


//Запускаем обработку полученных данных
new WebHook();

//Создание класса
class WebHook
{
    //свойство в которое запишем id пользователя
    private $user_id;
    //свойство класса в которое мы установим объект для работы с API
    private $bot;
    //подключение к базе MySQL
    private $link;

    private $page;

    public function __construct()
    {
        $db_host = 'localhost';
        $db_user = 'root';
        $db_password = 'rats1976';
        $db_name = 'roze2025';

        $this->link = mysqli_connect($db_host, $db_user, $db_password, $db_name);
        if (!$this->link) {
            die('<p style="color:red">' . mysqli_connect_errno() . ' - ' . mysqli_connect_error() . '</p>');
        }
        //	mysqli_query($link, "SET NAMES utf-8");
        $this->link->set_charset('utf8');


        //Создаем объект бота для работы API
        $this->bot = new Bot(BOT_TOKEN);

        //Получаем id пользователя через функцию getChatId
        $this->user_id = $this->bot->getChatId();

        $this->page = 1;
        //запускаем обработку webhook
        $this->init();
    }


    public function init()
    {

        $result = $this->link->query("SELECT * FROM users WHERE ident='" . $this->bot->user['id'] . "'");
        if ($result->num_rows == 0) {
            $fname = $this->bot->user['first_name'];
            $lname = $this->bot->user['last_name'];

            $result = $this->link->query("INSERT INTO users (ident,first_name,last_name,username)  VALUES ('" . $this->bot->user['id'] . "','" . $fname . "','" . $lname . "','" . $this->bot->user['username'] . "')");
            if (!$result) {
                $fname = "";
                $lname = "";
                $this->link->query("INSERT INTO users (ident,first_name,last_name,username)  VALUES ('" . $this->bot->user['id'] . "','" . $fname . "','" . $lname . "','" . $this->bot->user['username'] . "')");
            }

            // $log = date('Y-m-d H:i:s') . ' ' . ($result);
            // file_put_contents('../logs/log.txt', $log . "\r\n", FILE_APPEND);
        }


        //Определяем тип получаемых данных
        $type = $this->bot->getType();
        if ($type == 'message') { //простой текст
            $this->type_message();
        } elseif ($type == 'callback_query') {
            $this->type_message();
            //            $this->type_callback_query();
        } elseif ($type == 'object_message') {
            try {
                $tel = $this->bot->data["message"]["contact"]["phone_number"];
            } catch (Exception $e) {
                $tel = "";
            }
            ;
            $this->link->query("UPDATE users SET phone_number=" . $tel . " WHERE ident=" . $this->user_id);

            $this->bot->sendApiQuery('sendMessage', [
                'text' => 'Спасибо ! Ваш номер телефона сохранен',
                'chat_id' => $this->user_id,
            ]);

        } else {
            $this->bot->sendApiQuery('sendMessage', [
                'text' => 'Произошла ошибка, такие данные мы пока не умеем обрабатывать' . $type,
                'chat_id' => $this->user_id,
            ]);
            //прерываем работу скрипта
            exit;
        }
    }



    //получили текстовое сообщение и обработаем его
    public function type_message()
    {
        //Получаем текст сообщения
        $message = $this->bot->getText();
        //обработка команд с параметром
        //кнопки со страницами
        // if (substr($message, 0, 5) == "next_" || substr($message, 0, 5) == "prev_") {
        //     $m = explode("_", $message);
        //     if ($m[0] == "next") {
        //         $this->page = 1 + $m[1];
        //     }
        //     ;
        //     if ($m[0] == "prev") {
        //         $this->page = -1 + $m[1];
        //     }
        //     ;

        //     $but1 = array('text' => '⏪ Назад ', 'callback_data' => 'prev_' . $this->page, );
        //     $but2 = array('text' => '⏩ Еще ', 'callback_data' => 'next_' . $this->page, );
        //     if ($this->page == 4) {
        //         $col = array($but1);
        //     } elseif ($this->page == 1) {
        //         $col = array($but2);
        //     } else {
        //         $col = array($but1, $but2);
        //     }
        //     ;

        //     $row = array($col);

        //     $result = $this->bot->sendApiQuery('sendMessage', [
        //         'chat_id' => $this->user_id,
        //         'text' => $this->getCatalog(""),
        //         'parse_mode' => "HTML",
        //         'disable_web_page_preview' => true,
        //         'reply_markup' => json_encode(array(
        //                     'inline_keyboard' => $row
        //                 ))
        //     ]);

        //     //пока просто удаляем предпоследнее сообщение, хоть это и не правильно
        //     $mesid = $result['result']['message_id'];
        //     $result = $this->bot->sendApiQuery('deleteMessage', ['chat_id' => $this->user_id, 'message_id' => -1 + $mesid]);

        //     return;
        // }
        // ;


        // if (substr($message, 0, 9) == "/article_") {
        //     $m = explode("_", $message);
        //     $result = $this->bot->sendApiQuery('sendMessage', ['chat_id' => $this->user_id, 'text' => $this->getArticle($m[1]), 'parse_mode' => "HTML"]);
        //     $mesid = $result['result']['message_id'];
        //     $result = $this->bot->sendApiQuery('deleteMessage', ['chat_id' => $this->user_id, 'message_id' => -1 + $mesid]);

        //     return;
        // }
        //Конструкция switch case для обработки сообщения
        switch ($message) {
            case '/start':
                // if($this->user_id == ADMIN_ID) {
                //     $text = 'Привет, создатель!';
                // } else {
                //  $text = 'Здравствуйте, товарищ!';
                // }


                $text = "<a href='https://t.me/rozy2025_bot/rozy2025'>Здравствуйте ! Здесь Вы можете сформировать заказ на саженцы роз.</a>";

                //inline клавиатура
                //     $this->bot->sendApiQuery('sendMessage', ['chat_id' => $this->user_id, 'text' => $text, 'parse_mode'=>"HTML",  
                //         'reply_markup' => json_encode(array(
                //             'inline_keyboard' => array(
                //                 array(
                //                     array(
                //                         'text' => 'Каталог сортов',
                //                         'callback_data' => '/list',
                //                     ),

                //                     array(
                //                         'text' => 'Заказано',
                //                         'callback_data' => '/order',
                //                     ),
                //                 )
                //             ),
                //         ))
                //   ]);

                $replyMarkup3 = [
                    'keyboard' => [
                        [
                            [
                                'text' => 'Отправить телефон для обратной связи',
                                'request_contact' => true,
                            ]
                        ]
                    ],
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true,
                ];
                $encodedMarkup = json_encode($replyMarkup3);



                $this->bot->sendApiQuery('sendMessage', [
                    'chat_id' => $this->user_id,
                    'text' => $text,
                    'parse_mode' => "HTML",
                    'reply_markup' => $encodedMarkup,
                ]);


                break;
            case '/joke':
                //Получили текст joke и отправляем шутку
                $this->bot->sendApiQuery('sendMessage', ['chat_id' => $this->user_id, 'text' => $this->getJokeText()]);
                break;
            // case '/list':
            // case '🌺 Каталог сортов':
            //     $this->bot->sendApiQuery('sendMessage', [
            //         'chat_id' => $this->user_id,
            //         'text' => $this->getCatalog(""),
            //         'parse_mode' => "HTML",
            //         'disable_web_page_preview' => true,
            //         'reply_markup' => json_encode(array(
            //             'inline_keyboard' => array(
            //                 array(
            //                     array(
            //                         'text' => '⏩ Еще',
            //                         'callback_data' => 'next_' . $this->page,
            //                     ),
            //                 )
            //             ),
            //         ))
            //     ]);

            //     break;
            default:
                // $this->bot->sendApiQuery('sendMessage', ['chat_id' => $this->user_id, 'text' => $this->getCatalog($message), 'parse_mode' => "HTML", 'disable_web_page_preview' => true]);
                //получили не start и не joke, отправляем сообщение заглушку
//                $this->bot->sendApiQuery('sendMessage', ['chat_id' => $this->user_id, 'text' => 'Я пока не умею обрабатывать такие данные. Подождите когда разработчик придумает как реагировать на такие сообщения.']);

                $text = "<a href='https://t.me/rozy2025_bot/rozy2025'>Здравствуйте ! Здесь Вы можете сформировать заказ на саженцы роз.</a>";

                $replyMarkup3 = [
                    'keyboard' => [
                        [
                            [
                                'text' => 'Отправить телефон для обратной связи',
                                'request_contact' => true,
                            ]
                        ]
                    ],
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true,
                ];
                $encodedMarkup = json_encode($replyMarkup3);



                $this->bot->sendApiQuery('sendMessage', [
                    'chat_id' => $this->user_id,
                    'text' => $text,
                    'parse_mode' => "HTML",
                    'reply_markup' => $encodedMarkup,
                ]);


                break;
        }
    }


    private function getCatalog($filter)
    {
        $str = "";

        if ($filter) {
            $q = "SELECT * from goods WHERE name LIKE '%" . $filter . "%'";
        } else {
            $s = ($this->page - 1) * 25;
            $q = "SELECT * from goods ORDER BY article LIMIT " . $s . ",25";
        }
        ;

        $result = $this->link->query($q);
        $num = 1;
        while ($row = $result->fetch_row()) {
            if ($filter && $num > 50) {
                $str = $str . "<i>❗Показаны не все результаты, уточните запрос </i>";
                break; //ограничение длины сообщения
            }
            ;

            $free = 100; //доступные к заказу
            $price = 250; //цена 
            $url = "'https://t.me/rozy2025_bot/rozy2025?startapp=" . $row[5] . "'";
            $str = $str . $row[5] . ". <a href=" . $url . ">" . $row[1] . "</a>\r\n";
            $num = $num + 1;

        }
        return $str;

    }

    private function getArticle($art)
    {

        $q = "SELECT * from goods WHERE article='" . $art . "'";
        $result = $this->link->query($q);

        while ($row = $result->fetch_row()) {
            // рез = "<a href="""+ном.URLКартинка+""">"+ВРЕГ(ном.Наименование)+"</a>"+Символы.ПС+ном.Описание+Символы.ПС+
            // "<b>Цена: "+формат(ном.Цена,"ЧЦ=10; ЧДЦ=2; ЧФ='Ч руб'")+"</b>"+Символы.ПС+
            // "✿ <u>Доступно: "+формат(ДоступноДляЗаказа(ном),"ЧЦ=8; ЧФ='Ч шт'")+"</u>"+Символы.ПС+?(КоличествоВЗаказе(user,ном)=0,"Нет в заказе",
            // "★ Заказано: "+формат(КоличествоВЗаказе(user,ном),"ЧЦ=10; ЧФ='Ч шт'"))+Символы.ПС;

            $str = '<a href="' . $row[3] . '">' . $row[1] . '</a>';

        }

        return $str;

    }
    //Функция для выдачи случайного анекдота
    private function getJokeText()
    {
        //массив текстов
        $array = [
            'Они жили счастливо до конца жизни, пока не узнали, что другие живут дольше и гораздо счастливее.',
            'Жизнь делится на два этапа — сначала нет ума, потом здоровья.',
            'Курить вредно, пить противно, а умирать здоровым обидно.',
            'Мы учимся на своих ошибках и потом от этих ошибок лечимся.',
            'Рецепт простейших бутербродов: просто уложите кусочек хлеба на другой кусочек хлеба.',
            'Классика — это разновидность литературы, которую люди предпочитают хвалить, а не читать.',
            'Странный этот мир: двое смотрят одно и то же, а видят прямо противоположное.',
            'Только познав чёрную полосу в жизни, вы начинаете ценить серую.',
            'Честный человек, мечтающий стать политиком, должен помнить, что такое перевоплощение в принципе невозможно.',
            'В жизни настоящего программиста есть только две женщины: Ася и Клава. Ну, не считая матери. Хотите сладких снов? — Спите в торте!',
            'Какая крыша не любит быстрой езды!',
            'Когда вы начинаете вникать в суть любой распродажи, помните, что в русском языке слова «скидка» и «кинуть» имеют один и тот же корень.',
            'Все мужчины одинаковы, только зарплаты у них разные.',
            'Он лучше всех знал, как всё делать... Правда, ничего у него не получалось.',
            'Если они постоянно смеются над вами, это означает, что вы приносите радость людям.',
            'У каждого человека столько тщеславия, сколько ему не хватает интеллекта.',
            'В России многое изменилось за пять лет, почти ничего за двести лет.',
            'Синоптики, как и сапёры, ошибаются только один раз. Но каждый день.',
            'Если бы не мои ноги, меня бы здесь не было.',
            'Если в человеке все прекрасно, то может быть это не наш человек?',
        ];
        //возвращаем случайное значение массива
        return $array[rand(0, count($array) - 1)];
    }
}

?>