-- temporary script, here we write queries, views and sql functions for the app. Then we will mve them to schema.sql (views, functions) and to php scripts.

-- find teacher loans
create view teacher_loans as
	select username, birth, isbn, category, name
	from user natural join loan natural join book natural join teacher
		  natural join category
;

-- 3.1.1
create view school_loans as
	select s.name as school_name, count(l.sch_id) as total_loans
	from school s
	join loan l on s.id = l.sch_id
	where in_out = 'borrowed'  and date_format(l.date, '%Y-%m') = '2023-08'
	group by s.name;
-- 3.1.2
create view category_filter as
	select distinct t.name as teacher_name, a.name as author_name
	from teacher_loans t
	join loan l on t.username = l.username
	join category c on t.category = c.category
	join author a on t.isbn = a.isbn
	where datediff(now(), l.date) < 365 and c.category = 'History';
	

-- 3.1.3
create view youngster_loans as
	select name, count(username) as n_loans
	from teacher_loans
	where datediff(now(),birth) < 14600
	group by username
	order by n_loans;


--3.1.4
create view no_loan as
	select name as author
	from author a
	left join loan l on a.isbn = l.isbn
	where l.isbn is null
	group by author;

--3.1.5


