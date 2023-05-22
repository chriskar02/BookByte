-- general notes:
--	boolean automatically translates to tinyint
--	must first create table that contains referenced value and then the table with the foreign key (example sch id in school and user)
--	clob is not supported by mariadb so instead we use TEXT
--	cover images (blobs) must be maximum 65KB
--	free text (book summary, keywords and reviews) must be maximum 65KB too.
--	we allow some attributes (eg in book) to be nullable
--	dropping tables must be in correct order: bottom up

create table if not exists school (
	id int primary key,
	name varchar(60) not null,
	email varchar(60) not null,
	address varchar(60) not null,
	city varchar(30) not null,
	principal_name varchar(50) not null,
	phone int not null
) engine=InnoDB default charset=utf8;

create table if not exists user (
	username varchar(20) primary key,
	password varchar(40) not null,
	email varchar(60) not null unique,
	first_name varchar(45) not null,
	sch_id int not null,
	verified boolean default False,
	constraint fk_user_sch_id foreign key (sch_id)
	 references school(id) on delete cascade on update cascade
) engine=InnoDB default charset=utf8;

create table if not exists teacher (
	username varchar(20) primary key,
	handler_id int default null unique,
	verified boolean default False,
	constraint fk_teacher_user foreign key (username)
	 references user(username) on delete cascade on update cascade
) engine=InnoDB default charset=utf8;

create table if not exists admin (
	username varchar(20) primary key,
	constraint fk_admin_user foreign key (username)
	 references user(username) on delete cascade on update cascade
) engine=InnoDB default charset=utf8;

create table if not exists book (
	title varchar(40) not null,
	publisher varchar(20) not null,
	isbn char(10) primary key,
	pages smallint,
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
	category varchar(15) not null,
	primary key (isbn, category),
	constraint fk_category_book foreign key (isbn)
	 references book(isbn) on delete cascade on update cascade
) engine=InnoDB default charset=utf8;

















