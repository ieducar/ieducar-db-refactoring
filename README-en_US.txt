i-Educar database refactoring
=============================

This english version is just for information.

This is an i-Educar side project to add support to PostgreSQL 8.3+.

Currently, the software can only run in PostgreSQL 8.2 because it have a 
lot of database functions calls without explicit typecasting.

Thus, all the user land database functions (triggers and stored procedures) 
were cutted and pasted to the own file in sequential order. This way, we can
have a better peer review. Once a function is reviewed, it will be merged 
back to the ieducar.sql file that is shipped with i-Educar.

Please, update the issue in our Trac at: 
<http://svn.softwarepublico.gov.br/trac/ieducar/ticket/52>

The related PostgreSQL 8.3 references can be helpful:
<http://www.postgresql.org/docs/8.3/static/functions-string.html>
<http://www.postgresql.org/docs/8.3/static/datatype.html>


From PostgreSQL 8.3 changelog:

Non-character data types are no longer automatically cast to TEXT (Peter, Tom)

Previously, if a non-character value was supplied to an operator or function 
that requires text input, it was automatically cast to text, for most 
(though not all) built-in data types. This no longer happens: an explicit cast 
to text is now required for all non-character-string types. For example, these 
expressions formerly worked:

substr(current_date, 1, 4)
23 LIKE '2%'

but will now draw "function does not exist" and "operator does not exist" errors 
respectively. Use an explicit cast instead:

substr(current_date::text, 1, 4)
23::text LIKE '2%'

(Of course, you can use the more verbose CAST() syntax too.) The reason for the 
change is that these automatic casts too often caused surprising behavior. An 
example is that in previous releases, this expression was accepted but did not 
do what was expected:

current_date < 2017-11-17
This is actually comparing a date to an integer, which should be (and now is) 
rejected — but in the presence of automatic casts both sides were cast to text 
and a textual comparison was done, because the text < text operator was able to 
match the expression when no other < operator could.

Types char(n) and varchar(n) still cast to text automatically. Also, automatic 
casting to text still works for inputs to the concatenation (||) operator, so 
long as least one input is a character-string type.