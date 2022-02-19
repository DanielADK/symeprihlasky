create table if not exists address
(
    id       int auto_increment
        primary key,
    street   varchar(255) not null,
    city     varchar(255) not null,
    postcode varchar(255) not null,
    country  varchar(255) not null
)
    collate = utf8mb4_unicode_ci;

