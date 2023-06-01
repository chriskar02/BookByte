-- drop indexes
drop index USERNAMES on user;
drop index ISBNS on book;

-- drop views
drop view verified_ratings;
drop view verified_handler;

-- drop tables
drop table reservation;
drop table loan;
drop table ratings;
drop table school_storage;
drop table category;
drop table author;
drop table book;
drop table admin;
drop table teacher;
drop table session_tokens;
drop table user;
drop table school;

-- drop procedures and functions
drop procedure return_book;

