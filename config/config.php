<?php

// ПАРАМЕТРЫ БД
$host = 'localhost:8889'; // свои хост и порт в формате host:port
$dbname = 'films'; //название базы данных
$user = 'root'; //пользователь
$password = 'root'; //пароль
$db_url = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

// массив параметров БД для передачи в конструктор
$db_config = array(
    "db_url" => $db_url,
    "user" => $user,
    "password" => $password,
);


// ПАРАМЕТРЫ API (поменять ключ, если привышен суточный лимит запросов)
$api_key = '5310540a-3f15-48b3-815e-9c449c9339da'; //свой ключ, полученный при регистрации на https://kinopoiskapiunofficial.tech

$options = [
    "http" => [
        "header" => "X-API-KEY:$api_key\r\n"
    ]
];
$base_url = 'https://kinopoiskapiunofficial.tech/api';

// массив параметров API для передачи в конструктор
$api_config = array(
    //параметры запроса
    "options" => $options,

    //урл для запроса всех фильмов, в конце добавляется номер страницы
    "films_url" => "$base_url/v2.2/films?order=RATING&type=FILM&ratingFrom=0&ratingTo=10&yearFrom=1000&yearTo=3000&page=",

    //урл для запроса доп инфы по фильму, в конце доб. id фильма
    "film_url" => "$base_url/v2.2/films/",

    //урл для запроса команды по фильму, в конце доб. id фильма
    "staff_url" => "$base_url/v1/staff?filmId=",

    //ключ поля, содержавщего инфу по команде
    "author_key" => "professionKey",

    //значение поля, содержавщего инфу по команде, соответствующее режисеру
    "author_value" => "DIRECTOR",
);

?>