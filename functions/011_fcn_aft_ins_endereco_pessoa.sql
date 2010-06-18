--
-- Name: fcn_aft_ins_endereco_pessoa(); Type: FUNCTION; Schema: cadastro; Owner: -
--

CREATE FUNCTION fcn_aft_ins_endereco_pessoa() RETURNS "trigger"
    AS $$
DECLARE
  v_idpes   numeric;
  v_tipo_endereco text;
  BEGIN
    v_idpes   := NEW.idpes;
    v_tipo_endereco := NEW.tipo;
    EXECUTE 'DELETE FROM cadastro.endereco_externo WHERE idpes='||quote_literal(v_idpes)||' AND tipo='||v_tipo_endereco||';';
  RETURN NEW;
END; $$
    LANGUAGE plpgsql;