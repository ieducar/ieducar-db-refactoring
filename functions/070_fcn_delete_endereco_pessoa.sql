CREATE FUNCTION fcn_delete_endereco_pessoa(integer, integer) RETURNS integer
    AS $_$
DECLARE
  -- Parâmetro recebidos
  v_idpes ALIAS for $1;
  v_tipo ALIAS for $2;

BEGIN
  -- Deleta dados da tabela endereco_pessoa
  DELETE FROM cadastro.endereco_pessoa WHERE idpes = v_idpes AND tipo = v_tipo;
  RETURN 0;
END;$_$
    LANGUAGE plpgsql;