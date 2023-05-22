-- DML script

-- 3.1.1
select count(*) as total_loans
 from loan where in_out == 'borrowed'
 group by sch_id;

-- 3.1.2

