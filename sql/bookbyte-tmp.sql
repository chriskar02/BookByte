-- temporary script, here we write queries, views and sql functions for the app. Then we will mve them to schema.sql (views, functions) and to php scripts.

-- find teacher loans
create view teacher_loans as
	select username, birth, isbn, category, first_name, last_name
	from user natural join loan natural join book natural join teacher
		natural join category
;

-- 3.1.1
select count(*) as total_loans
 from loan where in_out == 'borrowed'
 group by sch_id;

-- 3.1.2
select name from author natural join category where category == $user_input_category;

select name
from teacher_loans
where category = $user_input_category;

-- 3.1.3
select first_name, last_name, sum(*) as n_loans
from teacher_loans
where datediff(now(),birth) < 14600
group by username
order by n_loans;


