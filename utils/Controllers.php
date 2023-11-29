<?php
class Controllers
{
    private $db;
    private $errHandler;

    public function __construct($db, $errHandler)
    {
        $this->errHandler = $errHandler;
        $this->db = $db;
    }

    function getFilms($params) // возвращает отсортированные фильмы из базы по N штук соответственно странице
    {
        if (!$params['count'] || !$params['page']) {
            $this->errHandler->setError(400, '', 'отсутствуют параметры count или page');
        }

        if ($params['count'] >= 30) {
            $this->errHandler->setError(400, '', 'максимальное количество отображаемых фильмов 30');
        }

        $count = $params['count'];
        $start = ($params['page'] - 1) * $count;

        $sql = "SELECT * FROM films ORDER BY title ASC, id ASC LIMIT $start, $count;";

        $films = $this->db->getDataSQL($sql);
        echo json_encode($films);
    }

    function postFilm($JSONdata) //проверяет данные и записывает в БД
    {
        if (!$JSONdata) {
            $this->errHandler->setError(400, '', 'отсутствует тело запроса');
        }

        // сохраняем данные из тела запроса
        $data = json_decode($JSONdata, true);


        if ($data['title'] == '' || !$data['author']) {
            $this->errHandler->setError(400, '', 'title и author - обязательные поля!');
        }

        $title = $data['title'];
        $img = $data['img'];
        $about = $data['about'];
        $author = $data['author'];

        // проверяем img на url
        if (filter_var($img, FILTER_VALIDATE_URL)) {

            $result = $this->db->findFilmData($title, $author);

            if (!$result) {
                // добавляем запись в базу
                $this->db->addFilmData($title, $img, $about, $author);
                $result = $this->db->findFilmData($title, $author);
                http_response_code(201);
                echo json_encode($result);
                exit;
            }

            $this->errHandler->setError(409, '', $result); //уже есть такой фильм, возвращаем его
        } else
            $this->errHandler->setError(400, '', 'введен не URL в img'); // введен не URL в img
    }


}
?>