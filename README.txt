i-Educar database refactoring
=============================

Este é um projeto a parte do i-Educar para adicionar suporte ao PostgreSQL 8.3+.

Atualmente, o software pode apenas ser implantado com o PostgreSQL 8.2 porque
existem diversas chamadas a funções de banco de dados sem o uso de typecasting
explícito.

Então, todas as funções de banco de dados definidas por usuário (triggers e
stored procedures) foram copiadas e coladas para o próprio arquivo em ordem
sequencial (na forma: numero_nome_funcao.sql). Dessa forma, nós temos uma
melhor forma de revisão de ponto. Uma vez que uma função for revista, será
mesclada de volta ao arquivo ieducar.sql que é distribuído com o i-Educar.

Por favor, atualize o ticket relacionado no nosso Trac:
<http://svn.softwarepublico.gov.br/trac/ieducar/ticket/52>

As seguintes referências do PostgreSQL 8.3 pode ser úteis:
<http://www.postgresql.org/docs/8.3/static/functions-string.html>
<http://www.postgresql.org/docs/8.3/static/datatype.html>


Do changelog do PostgreSQL 8.3 sobre a questão do typecasting explícito:

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