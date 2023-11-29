<?php
class FilmsApi
{
    private $films_url;
    private $film_url;
    private $staff_url;
    private $author_key;
    private $author_value;
    private $context;

    public function __construct($config)
    {
        $this->films_url = $config['films_url'];
        $this->film_url = $config['film_url'];
        $this->staff_url = $config['staff_url'];
        $this->author_key = $config['author_key'];
        $this->author_value = $config['author_value'];
        $this->context = stream_context_create($config['options']);
    }

    private function getRes($url) //выполняет запрос и переводит из json
    {
        return json_decode(file_get_contents($url, false, $this->context));
    }

    private function addFilm($film, $addFilm) //ищет описание и продюсера, затем загружает фильм в базу
    {
        $id = $film->kinopoiskId;

        $title = $film->nameRu;
        $img = $film->posterUrl;

        $filmInfo = $this->getRes($this->film_url . $id); //ищем описание по id
        $about = $filmInfo->description;

        $staff = $this->getRes($this->staff_url . $id); //ищем команду по id

        $author = null;

        if ($staff) { //ищем первого продюсера в массиве команды, если нашли ее
            $professions = array_column($staff, $this->author_key);
            $key = array_search($this->author_value, $professions);
            $author = $key !== false ? $staff[$key]->nameRu : null;
        }

        //добавляем фильм в базу
        $addFilm($title, $img, $about, $author);
    }

    function loadFilms($addFilm) //загружает нужную инфу по фильмам в базу
    {
        $last_page = 1;
        //загружаем инфу по фильмам для всех страниц
        for ($page = 1; $page <= $last_page; $page++) {

            $data = $this->getRes($this->films_url . $page);

            //получили фильмы и количество страниц
            $films = $data->items;
            $last_page = $data->totalPages;

            //получаем доп. инфу и добавляем в базу
            foreach ($films as $film) {
                $this->addFilm($film, $addFilm);
            }
        }
    }
}
?>