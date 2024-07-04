CREATE TABLE IF NOT EXISTS users
(
    id         SERIAL       NOT NULL PRIMARY KEY,
    username   varchar(50)  NOT NULL UNIQUE,
    password   varchar(255) NOT NULL,
    email      varchar(100) NOT NULL,
    role       varchar(255),
    created_at timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP
);
