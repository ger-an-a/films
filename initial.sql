CREATE TABLE IF NOT EXISTS films(
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    img TEXT NOT NULL,
    about TEXT NOT NULL,
    author VARCHAR(40) NOT NULL
);

CREATE UNIQUE INDEX films_title_author_idx ON  films (title, author);