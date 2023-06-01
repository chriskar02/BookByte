-- DDL
-- general notes:
--	boolean automatically translates to tinyint
--	must first create table that contains referenced value and then the table with the foreign key (example sch id in school and user)
--	clob is not supported by mariadb so instead we use TEXT
--	cover images (blobs) must be maximum 65KB
--	free text (book summary, keywords and reviews) must be maximum 65KB too.
--	we allow some attributes (eg in book) to be nullable
--	dropping tables must be in correct order: bottom up

DROP SCHEMA IF EXISTS BookByte;
CREATE SCHEMA BookByte;
USE BookByte;

create table if not exists school (
	id int unsigned primary key auto_increment,
	name varchar(60) not null,
	email varchar(60) not null unique,
	address varchar(60) not null unique,
	city varchar(30) not null,
	principal_name varchar(50) not null,
	phone int unsigned not null unique,
	unique(name, city),
	check ( phone between 999999999 and 10000000000 )
) engine=InnoDB default charset=utf8;

create table if not exists user (
	username varchar(20) primary key,
	password varchar(40) not null,
	email varchar(60) not null unique,
	name varchar(50) not null,
	sch_id int unsigned not null,
	user_verified boolean default False,
	constraint fk_user_sch_id foreign key (sch_id)
	 references school(id) on delete cascade on update cascade
) engine=InnoDB default charset=utf8;

create table if not exists session_tokens (
	username varchar(20) not null,
	token varchar(64) not null,
	primary key (username, token),
	constraint fk_token_username foreign key (username)
	 references user(username) on delete cascade on update cascade
) engine=InnoDB default charset=utf8;

create table if not exists teacher (
	username varchar(20) primary key,
	handler_request boolean default False,
	birth date not null,
	handler_verified boolean default False,
	constraint fk_teacher_user foreign key (username)
	 references user(username) on delete cascade on update cascade
) engine=InnoDB default charset=utf8;

create table if not exists admin (
	username varchar(20) primary key,
	constraint fk_admin_user foreign key (username)
	 references user(username) on delete cascade on update cascade
) engine=InnoDB default charset=utf8;

create table if not exists book (
	title varchar(80) not null,
	publisher varchar(40) not null,
	isbn char(10) primary key,
	pages smallint unsigned,
	summary text not null,
	cover_image blob,
	language varchar(15) not null,
	keywords text
) engine=InnoDB default charset=utf8;

create table if not exists author (
	isbn char(10) not null,
	name varchar(50) not null,
	primary key (isbn, name),
	constraint fk_author_book foreign key (isbn)
	 references book(isbn) on delete cascade on update cascade
) engine=InnoDB default charset=utf8;

create table if not exists category (
	isbn char(10) not null,
	category varchar(30) not null,
	primary key (isbn, category),
	constraint fk_category_book foreign key (isbn)
	 references book(isbn) on delete cascade on update cascade
) engine=InnoDB default charset=utf8;

create table if not exists school_storage (
	sch_id int unsigned not null,
	isbn char(10) not null,
	copies int unsigned not null,
	primary key (isbn, sch_id),
	constraint fk_storage_sch_id foreign key (sch_id)
	 references school(id) on delete cascade on update cascade,
	constraint fk_storage_book foreign key (isbn)
	 references book(isbn) on delete cascade on update cascade
) engine=InnoDB default charset=utf8;

create table if not exists ratings (
	username varchar(20) not null,
	isbn char(10) not null,
	date timestamp not null default current_timestamp,
	stars smallint unsigned not null,
	description text,
	rating_verified boolean default 0,
	primary key (username, isbn),
	check ( stars between 0 and 5 ),
	constraint fk_ratings_username foreign key (username)
	 references user(username) on delete cascade on update cascade,
	constraint fk_ratings_book foreign key (isbn)
	 references book(isbn) on delete cascade on update cascade
) engine=InnoDB default charset=utf8;

create table if not exists loan (
	username varchar(20) not null,
	isbn char(10) not null,
	handler_username varchar(20) default null,
	date timestamp not null default current_timestamp,
	sch_id int unsigned not null,
	in_out enum('borrowed','returned') not null,
	transaction_verified int default 0, /*0: not berified borrow, 1: verified borrow, 2: (verified) return*/
	primary key (username, isbn, date, in_out),
	constraint fk_loans_username foreign key (username)
	 references user(username) on delete cascade on update cascade,
	constraint fk_loans_book foreign key (isbn)
	 references book(isbn) on delete cascade on update cascade,
	constraint fk_loans_sch_id foreign key (sch_id)
	 references school(id) on delete cascade on update cascade
) engine=InnoDB default charset=utf8;

create table if not exists reservation (
	username varchar(20) not null,
	isbn char(10) not null,
	date timestamp not null default current_timestamp,
	sch_id int unsigned not null,
	primary key (username, isbn),
	constraint fk_rsv_username foreign key (username)
	 references user(username) on delete cascade on update cascade,
	constraint fk_rsv_book foreign key (isbn)
	 references book(isbn) on delete cascade on update cascade,
	constraint fk_rsv_sch_id foreign key (sch_id)
	 references school(id) on delete cascade on update cascade
) engine=InnoDB default charset=utf8;



create view verified_ratings as
	select * from ratings where rating_verified = 1;

create view verified_handler as
	select username, birth from teacher where handler_verified = 1;


delimiter //

-- when a book is returned, check if there are reservations for it
create procedure return_book(
    in p_username varchar(20),
    in p_isbn char(10),
    in p_handler_username varchar(20),
    in p_sch_id int,
    in p_date timestamp
)
begin
	-- complete borrow transaction
	update loan set transaction_verified = 2 where username = p_username and isbn = p_isbn and loan.date = p_date;
	
	-- add return transaction
    insert into loan (username, isbn, handler_username, sch_id, in_out, transaction_verified)
    values (p_username, p_isbn, p_handler_username, p_sch_id, 'returned', 2);

    -- add new borrow request in case there is an active reservation
	insert into loan (username, isbn, handler_username, sch_id, in_out, transaction_verified)
    select username, isbn, NULL, sch_id, 'borrowed', 0
    from reservation
    where isbn = p_isbn and sch_id = p_sch_id
    limit 1;

	-- remove reservation
    delete from reservation where username = (select username from reservation where isbn = p_isbn and sch_id = p_sch_id limit 1 ) and isbn = p_isbn;

end//

delimiter ;


 






