CREATE TABLE IF NOT EXISTS posts
(
    id         SERIAL       NOT NULL PRIMARY KEY,
    title      varchar(255) NOT NULL,
    content    text         NOT NULL,
    created_at timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS comments
(
    id         SERIAL PRIMARY KEY,
    post_id    int       NOT NULL,
    name       varchar(255)       DEFAULT NULL,
    email      varchar(255)       DEFAULT NULL,
    content    text      NOT NULL,
    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT comments_ibfk_1 FOREIGN KEY (post_id) REFERENCES posts (id)
);
