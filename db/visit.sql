use answer;
create table visit
(
  hostuid int unsigned not null DEFAULT 0,
  visituid int unsigned not null DEFAULT 0,
  visittime int unsigned not null DEFAULT 0,
  visitip char(32) not null DEFAULT '',
  index(hostuid),
  index(visituid)
)engine=innodb default charset=utf8;

create table fans
(
  idoluid int unsigned not null DEFAULT 0,
  fansuid int unsigned not null DEFAULT 0,
  ftime int unsigned not null DEFAULT 0,
  index(idoluid),
  index(fansuid)
)engine=innodb default charset=utf8;