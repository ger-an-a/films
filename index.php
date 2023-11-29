<?php

header('Content-Type: application/json; charset=utf-8');

// константы
$ERROR_MESSAGE400 = 'Некорректный запрос';
$ERROR_MESSAGE409 = 'Запись уже существует';
$ERROR_MESSAGE = 'Ошибка';

require_once 'config/config.php';

require_once './utils/ConnectedDB.php';
require_once './utils/FilmsApi.php';
require_once './utils/Controllers.php';
require_once './utils/ErrorHandler.php';

$errHandler = new ErrorHandler();

//при создании экземпляра подключаемся к БД
$db = new ConnectedDB($db_config, $errHandler);

//при создании экземпляра создаем контекст для взаимодействия
$api = new FilmsApi($api_config);

$controllers = new Controllers($db, $errHandler);
//создание таблицы, если её нет
$db->executeSQL(file_get_contents("initial.sql"));

//добавляем фильмы, если таблица пуста
if ($db->checkIsEmpty()) {
    $api->loadFilms(array($db, 'addFilmData'));
}

//определяем метод и параметры запроса
$method = $_SERVER['REQUEST_METHOD'];
$JSONdata = file_get_contents('php://input');

// if (!$_GET['count'] || !$_GET['page']) {
//     $errHandler->setError(400, '', 'отсутствуют параметры count или page');
// } else
$params = array("count" => $_GET['count'], "page" => $_GET['page']);

//обрабатываем запрос
switch ($method) {
    case 'GET':
        $controllers->getFilms($params);
        break;

    case 'POST':
        $controllers->postFilm($JSONdata);
        break;
}

?>