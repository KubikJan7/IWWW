create table user
(
  id          int auto_increment
    primary key,
  username    varchar(20) null,
  password    varchar(20) null,
  email       varchar(50) null,
  description text        null,
  created     datetime    null
);


