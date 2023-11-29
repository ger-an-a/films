<?php
class ConnectedDB
{
    private $connect;
    private $errHandler;

    public function __construct($config, $errHandler)
    {
        $this->errHandler = $errHandler;
        try {
            $this->connect = new PDO($config['db_url'], $config['user'], $config['password']);
        } catch (PDOException $e) {
            $this->errHandler->setError(500, "Error!: " . $e->getMessage());
            exit;
        }
    }

    function checkIsEmpty() //проверяет пуста ли таблица
    {
        return count($this->getDataSQL('SELECT * FROM `films`;')) == 0;
    }

    function executeSQL($sql, $sql_params = array()) //выполняет запрос с параметрами
    {
        $stmt = $this->connect->prepare($sql);

        $stmt->execute($sql_params);

        return $stmt;
    }

    function getDataSQL($sql, $sql_params = array()) // выполняет запрос с параметрами и возвращает результат
    {
        $stmt = $this->executeSQL($sql, $sql_params);

        $elementList = array();

        while ($element = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $elementList[] = $element;
        }

        return $elementList;
    }

    function addFilmData($title, $img, $about, $author) // добавление записи
    {
        $sql = 'INSERT IGNORE INTO `films` (`id`, `title`, `img`, `about`, `author`) VALUES (NULL, :title, :img, :about, :author);';

        $sql_params = array(':title' => $title, ':img' => $img, ':about' => $about, ':author' => $author);

        return $this->executeSQL($sql, $sql_params);
    }


    function findFilmData($title, $author) // поиск по названию и автору
    {
        $sql = 'SELECT * FROM `films` WHERE title = :title and author=:author;';

        $sql_params = array(':title' => $title, ':author' => $author);

        return $this->getDataSQL($sql, $sql_params);
    }

}
?>