create schema if not exists memory_db collate utf8mb4_0900_ai_ci;

create table if not exists memory_db.dificulty
(
    id int primary key not null ,
    name varchar(50) not null
);

create table if not exists memory_db.scores
(
    id int auto_increment
        primary key,
    name varchar(100) not null,
    time int not null,
    dificulty_id int not null
);

insert into memory_db.dificulty(id, name) VALUES(1, 'Facile');
insert into memory_db.dificulty(id, name) VALUES(2, 'Difficile');
insert into memory_db.dificulty(id, name) VALUES(3, 'Extreme');