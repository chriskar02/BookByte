--3.2.1

select b.title, a.name
from book b
join author a on a.isbn = b.isbn;


--3.2.2

select l.username, u.name, datediff(now(), l.date) - 7 as delay
from loan l
join user u on l.username = u.username
where l.in_out = 'borrowed'
	and l.date <= date_sub(now(), interval 1 week)
	and l.isbn in (
		select isbn
		from loan 
		where in_out = 'borrowed'
	)
	and (
		u.name like 'Gary Beck'
		or (datediff(now(), l.date) - 7) > 3
	);

--3.2.3

select r.username, avg(r.stars) as avg_user_rating
from ratings r
where r.username like 'andrewholt'
group by r.username;

select c.category, avg(r.stars) as avg_category_rating
from ratings r
join category c on r.isbn = c.isbn
where category like 'Fiction'
group by c.category;
