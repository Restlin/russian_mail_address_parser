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
    <li>статистика по работе сервиса</li>
</ul>
<h4>Демо</h4>
<p>Демо сервиса доступно по адресу: http://restlin.keenetic.link:10080 </p>
<p>Реквизиты тестового пользователя: email: testuser@test.ru, пароль: testuser</p>

[![Issues](https://img.shields.io/github/issues/Restlin/russian_mail_address_parser)](https://github.com/Restlin/russian_mail_address_parser/issues)
[![Stars](https://img.shields.io/github/stars/Restlin/russian_mail_address_parser)](https://github.com/Restlin/russian_mail_address_parser/stargazers)
[![License](https://img.shields.io/github/license/Restlin/russian_mail_address_parser)](https://github.com/Restlin/russian_mail_address_parser/blob/master/LICENSE.md)

СТРУКТУРА ДИРЕКТОРИЙ
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources



ТРЕБОВАНИЯ
------------

....


УСТАНОВКА
------------
### Нужен xlsx2csv
apt-get install xlsx2csv

### Установка зависимостей проекта

Установка зависимостей осуществляется с помощью [Composer](http://getcomposer.org/). Если у вас его нет вы можете установить его по инструкции
на [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

После этого выполнить команду в директории проекта:

~~~
composer install
~~~
