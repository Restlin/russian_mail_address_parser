<p align="center">
    <h1 align="center">Адреса друзей по переписке</h1>
    <br>
</p>

<h3>Кейс</h3>
<p>Прототип сервиса разработан в рамках хакатона Северо-Западного IT-хаба по кейсу Почты России</p>
<p>Сервис предоставляет возможность проверить и нормализовать адресные базы данных пользователей в виде файлов csv или xlsx</p>

<h4>Реализованный функционал</h4>
<ul>
    <li>регистрация пользователя</li>
    <li>авторизация пользователя</li>
    <li>загрузка файлов пользователем и их автоматическая обработка</li>
    <li>отслеживание прогресса обработки файлов</li>
    <li>просмотр результатов обработки и построчное редактирование адресной базы</li>
    <li>выгрузка файлов в форматах csv и xlsx</li>
    <li>статистика по работе сервиса</li>
</ul>
<h4>Демо</h4>
<p>Демо сервиса доступно по адресу: http://restlin.keenetic.link:10080 </p>
<p>Реквизиты тестового пользователя: email: <b>testuser@test.ru</b>, пароль: <b>testuser</b></p>

<h4>Как пользоваться сервисом</h4>

<h5>Вход в систему</h5>
<p>Любой человек может войти в систему под тестовым пользователем <b>testuser@test.ru</b> с паролем <b>testuser</b> на <a href="http://restlin.keenetic.link:10080/index.php?r=site%2Flogin">странице авторизации.</a></p>

<h5>Регистрация</h5>
<p>Если тестовый пользователь не вариант, то можно зарегистрировать собственную учетную запись на <a href="http://restlin.keenetic.link:10080/index.php?r=site%2Fregistration">странице регистрации.</a> и подтвердить ее email.</p>

<h5>Работа с файлами</h5>
<p>После входа пользователя на сервис он попадает на <a href="http://restlin.keenetic.link:10080/index.php?r=file%2Findex">страницу работы с файлами</a>, где он может загрузить свой файл, видит уже загруженные файлы и их прогресс обработки.</p>
<p>Пользователь может нажать на имя файла и войти в конкретную информацию о каждом файле, где видно как обработана каждая строка, а также есть возможность выгрузить файл в форматах csv или xlsx.</p>

<h5>Статистика</h5>
<p>Также любой пользователь может просмотреть статистику работы сервиса, где выводится количество пользователей, обработанных файлов, строк файлов и средняя скорость работы сервиса. Чтобы ее увидеть пользователь должен нажать на <a href="http://restlin.keenetic.link:10080/index.php?r=site%2Fstats">ссылка статистика</a> на верхней панели сайта.</p>

[![Issues](https://img.shields.io/github/issues/Restlin/russian_mail_address_parser)](https://github.com/Restlin/russian_mail_address_parser/issues)
[![Stars](https://img.shields.io/github/stars/Restlin/russian_mail_address_parser)](https://github.com/Restlin/russian_mail_address_parser/stargazers)
[![License](https://img.shields.io/github/license/Restlin/russian_mail_address_parser)](https://github.com/Restlin/russian_mail_address_parser/blob/master/LICENSE.md)



ТРЕБОВАНИЯ
------------
1) развертывание сервиса производится на debian-like linux;
2) требуется установленный web-сервер с поддержкой PHP(версия 7.4+) интерпретации (apache, nginx);
3) требуется установленная СУБД PostgreSQL(версия 12+);
4) требуется установленный пакет xlsx2csv для работы с файлами xlsx;


УСТАНОВКА
------------
### Установка пакета xlsx2csv

Выполните 
~~~
sudo apt-get install xlsx2csv
~~~
### База данных PostgreSQL

Необходимо создать пустую базу данных PostgreSQL, а подключение к базе прописать в конфигурационный файл сервиса по адресу: папка_сервиса/config/db.php
### Выполнение миграций

Для заполнения базы данных системной информацией выполните в корневой папке сервиса: 
~~~
./yii migrate 
~~~
и согласитесь с запросом
### Запуск очередей

Для запуска очередей обработки выполните в корневой папке сервиса: 
~~~
./yii queue/listen & 
~~~

столько раз, сколько демонов обработки вы хотите
### Веб-сервер

Настройте ваш веб-сервер так, чтобы папка_сервиса/web была доступна через веб-сервер.

Или можно запустить тестовый стенд командой:
~~~
./yii serve/index
~~~

### Установка зависимостей проекта

Установка зависимостей осуществляется с помощью [Composer](http://getcomposer.org/). Если у вас его нет вы можете установить его по инструкции
на [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

После этого выполнить команду в директории проекта:

~~~
composer install
~~~
