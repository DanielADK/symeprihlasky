create table if not exists event
(
    id           int auto_increment
        primary key,
    name_short   varchar(255) not null,
    name_full    varchar(255) not null,
    date_start   date         not null,
    date_end     date         not null,
    active       tinyint(1)   not null,
    price_member int          not null,
    price_other  int          not null,
    type         varchar(255) not null
)
    collate = utf8mb4_unicode_ci;

