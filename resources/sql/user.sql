CREATE DATABASE breastplate CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE user
(
    id       int auto_increment primary key,
    name     varchar(255) not null,
    email    varchar(255) not null,
    password varchar(255) not null,
    token    varchar(255) null,
    constraint email
        unique (email)
);