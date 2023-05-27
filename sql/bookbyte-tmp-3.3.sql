--3.3.1

--3.3.2
select b.title
from book b
where isbn in (
	select isbn
	from loan
	where username = 'zadkins'
	);
