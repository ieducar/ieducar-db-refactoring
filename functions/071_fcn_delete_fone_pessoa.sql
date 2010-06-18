--
-- Name: fcn_delete_fone_pessoa(integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION fcn_delete_fone_pessoa(integer) RETURNS integer
    AS $_$
DECLARE
  -- Parâmetro recebidos
  v_id_pes ALIAS for $1;
  
BEGIN
  -- Deleta dados da tabela fone_pessoa
  DELETE FROM cadastro.fone_pessoa WHERE idpes = v_id_pes;
  RETURN 0;
END;$_$
    LANGUAGE plpgsql;