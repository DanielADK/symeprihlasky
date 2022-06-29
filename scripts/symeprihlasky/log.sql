create table if not exists log
(
    id        int auto_increment
        primary key,
    user_id   int           not null,
    date_time datetime      not null,
    content   varchar(1023) not null,
    constraint FK_8F3F68C5A76ED395
        foreign key (user_id) references person (id)
)
    collate = utf8mb4_unicode_ci;

create index IDX_8F3F68C5A76ED395
    on log (user_id);

