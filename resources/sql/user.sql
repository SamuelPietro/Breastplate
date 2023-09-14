create table user
(
    id       int auto_increment primary key,
    name     varchar(255) not null,
    email    varchar(255) not null,
    password varchar(255) not null,
    token    varchar(255) null,
    constraint email
        unique (email)
);