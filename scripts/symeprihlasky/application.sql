create table if not exists application
(
    id         int auto_increment
        primary key,
    person_id  int          not null,
    sign_date  datetime     not null,
    hash       varchar(255) not null,
    shirt_size varchar(255) not null,
    event_id   int          not null,
    constraint FK_A45BDDC1217BBB47
        foreign key (person_id) references person (id),
    constraint FK_A45BDDC171F7E88B
        foreign key (event_id) references event (id)
)
    collate = utf8mb4_unicode_ci;

create index IDX_A45BDDC1217BBB47
    on application (person_id);

create index IDX_A45BDDC171F7E88B
    on application (event_id);

