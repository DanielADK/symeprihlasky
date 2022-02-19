create table if not exists child
(
    id         int auto_increment
        primary key,
    parent_id  int          not null,
    address_id int          not null,
    name       varchar(255) not null,
    surname    varchar(255) not null,
    birth_date date         null,
    sex        varchar(255) null,
    shirt_size varchar(255) null,
    email      varchar(255) null,
    ctu_member tinyint(1)   null,
    constraint FK_22B35429727ACA70
        foreign key (parent_id) references person (id),
    constraint FK_22B35429F5B7AF75
        foreign key (address_id) references address (id)
)
    collate = utf8mb4_unicode_ci;

create index IDX_22B35429727ACA70
    on child (parent_id);

create index IDX_22B35429F5B7AF75
    on child (address_id);

