CREATE FUNCTION fcn_delete_endereco_externo(integer, integer) RETURNS integer
    AS $_$
DECLARE
  -- Parâmetro recebidos
  v_idpes ALIAS for $1;
  v_tipo ALIAS for $2;

BEGIN
  -- Deleta dados da tabela endereco_externo
  DELETE FROM cadastro.endereco_externo WHERE idpes = v_idpes AND tipo = v_tipo;
  RETURN 0;
END;$_$
    LANGUAGE plpgsql;