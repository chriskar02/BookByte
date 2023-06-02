-- sql queries από το ερώτημα 3. τα υπόλοιπα queries που χρησιμοποιήθηκαν βρίσκονται ενσωματωμένα στον php κώδικα.
-- σε όσα σημεία χρησιμοποιούνται είσοδοι από τον χρήστη, αυτά φαίνονται ως μεταβλητές php (πχ: $category) όπως και στον ενσωματωμένο κώδικα.

-- 3.1.1
select school.name, school.city, count(loan.sch_id) as total
from school join loan on id = sch_id
where in_out = 'borrowed' and date like '".$yyyy."-".$mm."%'
group by sch_id
order by total desc;

-- 3.1.2
select distinct name
from author join category on category.isbn = author.isbn
where category = '".$category."';

select distinct user.username
from user join loan on user.username = loan.username
	join category on category.isbn = loan.isbn
where loan.in_out = 'borrowed' and loan.transaction_verified <> 0
	and category = '".$category."' and datediff(now(), date) < 365;

-- 3.1.3
select teacher.username, datediff(now(), birth)/365 as age, count(*) as n_loans 
from teacher join loan on teacher.username = loan.username
where in_out = 'borrowed' and transaction_verified <> 0
	and datediff(now(), birth)/365 < 40
group by teacher.username
order by n_loans desc;

-- 3.1.4
select author.name
from author
where author.isbn not in (
    select loan.isbn
    from loan
    where in_out = 'borrowed' and transaction_verified <> 0
);

-- 3.1.5
select group_concat(handler_username), loaned
from (
	select handler_username, count(*) as loaned
	from loan
	where in_out = 'borrowed'
		and transaction_verified <> 0
		and date >= date_sub(curdate(), interval 1 year)
	group by handler_username
	having loaned > 20
	) as subquery
group by loaned
order by loaned desc;

-- 3.1.6
select category1, category2, count(distinct isbn) as count
from (
	select
	case when c1.category < c2.category
	then
		c1.category
	else
		c2.category
	end
	as category1,
	case when c1.category < c2.category
	then
		c2.category
	else
		c1.category
	end
	as category2,
	l.isbn
	from loan l
	join category c1 on l.isbn = c1.isbn
	join category c2 on l.isbn = c2.isbn and c1.category <> c2.category
	) as pairs
group by category1, category2
order by count desc
limit 3;

-- 3.1.7
select name, count(isbn) as num_books
from author
group by name
having count(isbn) <= (
	select max(book_count) - 5
	from (
		select count(isbn) as book_count
		from author
		group by name
	) as book_count
)
order by count(isbn) desc;

-- 3.2.1
-- κατα rating
select book.title, avg(verified_ratings.stars) as rating, book.summary,
	book.cover_image, count_table.total_count, book.isbn
from book
	left join verified_ratings on book.isbn = verified_ratings.isbn
	join (select count(*) as total_count from book) as count_table
group by book.isbn, book.title, book.summary, book.cover_image, 
	count_table.total_count
order by rating desc;

-- κατα πλήθος δανεισμών
select b.title, avg(stars), b.summary, b.cover_image, total_count,
	total_borrowed_tb.total_borrowed as tot_bor, b.isbn
from book as b
	left outer join verified_ratings on b.isbn = verified_ratings.isbn
    left join (
		select isbn, count(*) as total_borrowed
        from book natural join loan
        where in_out = 'borrowed'
        group by isbn
    ) as total_borrowed_tb on b.isbn = total_borrowed_tb.isbn
    join (select count(*) as total_count from book) as count_table
group by b.isbn, b.title
order by tot_bor desc;

-- κατα πλήθος αντίτυπων
select book.title, book.summary, book.cover_image,
	book.isbn, sum(copies) as ssum, avg(stars)
from school_storage
	join book on school_storage.isbn = book.isbn
    left outer join verified_ratings on book.isbn = verified_ratings.isbn
group by book.isbn, book.title
order by ssum desc;
            
-- αναζήτηση με τίτλο, κατηγορία ή ISBN
select merged.title, merged.rating, summary, cover_image, isbn
from ((
	select book.isbn, title, avg(stars) as rating, summary, cover_image
	from book left join verified_ratings on book.isbn = verified_ratings.isbn
	where title like '%".$terms."%'
	group by book.isbn;
)
union (
	select book.isbn, title, avg(stars) as rating, summary, cover_image
	from book left join verified_ratings on book.isbn = verified_ratings.isbn 
	where book.isbn like '%".$terms."%'
	group by book.isbn;
)
union (
	select book.isbn, title, avg(stars) as rating, summary, cover_image
	from book
		left join verified_ratings on book.isbn = verified_ratings.isbn
		join author on book.isbn = author.isbn
	where name like '%".$terms."%'
	group by book.isbn;
)) as merged;
            
            
-- 3.2.2
select username, datediff(now(), date) as days_late
from loan
where in_out = 'borrowed' and transaction_verified = 1
	and date_add(date, interval 7 day) < now();

-- 3.2.3
select avg(stars) as average, username
from ratings
where rating_verified = 1
group by username
order by average desc;

select avg(stars) as average, category
from ratings join category on category.isbn = ratings.isbn
where rating_verified = 1
group by category
order by average desc;


-- 3.3.1
-- ίδιο με 3.2.1

-- 3.3.2
select date, title, name, city, in_out, transaction_verified,
	book.isbn, sch_id
from loan
	join book on loan.isbn = book.isbn
	join school on school.id = loan.sch_id
where username = '".$page_username."'
order by loan.date desc;







