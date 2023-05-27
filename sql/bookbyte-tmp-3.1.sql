-- temporary script, here we write queries, views and sql functions for the app. Then we will mve them to schema.sql (views, functions) and to php scripts.

-- find teacher loans
create view teacher_loans as
	select username, birth, isbn, category, name
	from user natural join loan natural join book natural join teacher
		  natural join category
;

-- 3.1.1
select s.name as school_name, count(l.sch_id) as total_loans
from school s
join loan l on s.id = l.sch_id
where in_out = 'borrowed'  and date_format(l.date, '%Y-%m') = '2023-08'
group by s.name;

-- 3.1.2

select distinct t.name as teacher_name, a.name as author_name
from teacher_loans t
join loan l on t.username = l.username
join category c on t.category = c.category
join author a on t.isbn = a.isbn
where datediff(now(), l.date) < 365 and c.category = 'History';
	

-- 3.1.3

select name, count(username) as n_loans
from teacher_loans
where datediff(now(),birth) < 14600
group by username
order by n_loans desc;


--3.1.4

select name as author
from author a
left join loan l on a.isbn = l.isbn
where l.isbn is null
group by author;

--3.1.5

select handler_username as handler, count(*) as loaned
from loan
where in_out = 'borrowed' and datediff(now(), date) < 365
group by handler_username
having loaned > 20
order by loaned desc;

--3.1.6

select category1, category2, count(distinct isbn) as count
from (
select 
case when c1.category < c2.category then c1.category else c2.category end as category1,
case when c1.category < c2.category then c2.category else c1.category end as category2,
l.isbn
from loan l
join category c1 on l.isbn = c1.isbn
join category c2 on l.isbn = c2.isbn and c1.category <> c2.category
) as pairs
group by category1, category2	
order by count desc
limit 3;

--3.1.7  !!not sure if it works need more dummy data

select name, count(isbn) as num_books
from author
group by name
having count(isbn) <= (
	select max(book_count) - 2
	from (
		select count(isbn) as book_count
		from author
		group by name
	) as book_counts
)
order by count(isbn) desc;