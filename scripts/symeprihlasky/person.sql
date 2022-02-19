create table if not exists person
(
    id         int auto_increment
        primary key,
    address_id int          null,
    name       varchar(255) not null,
    surname    varchar(255) not null,
    birth_date date         null,
    sex        varchar(255) null,
    shirt_size varchar(255) null,
    ctu_member tinyint(1)   null,
    email      varchar(255) not null,
    roles      json         null,
    phone      varchar(255) null,
    password   varchar(255) null,
    constraint UNIQ_34DCD176444F97DD
        unique (phone),
    constraint UNIQ_34DCD176E7927C74
        unique (email),
    constraint FK_34DCD176F5B7AF75
        foreign key (address_id) references address (id)
)
    collate = utf8mb4_unicode_ci;

create index IDX_34DCD176F5B7AF75
    on person (address_id);

