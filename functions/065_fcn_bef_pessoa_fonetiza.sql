--
-- Name: fcn_bef_pessoa_fonetiza(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION fcn_bef_pessoa_fonetiza() RETURNS "trigger"
    AS $$
   DECLARE
    v_idpes    bigint;
   BEGIN
    v_idpes := OLD.idpes;
    EXECUTE 'DELETE FROM cadastro.pessoa_fonetico WHERE idpes = '||quote_literal(v_idpes)||';';
    RETURN OLD;
   END;
  $$
    LANGUAGE plpgsql;