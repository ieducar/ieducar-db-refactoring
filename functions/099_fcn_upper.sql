--
-- Name: fcn_upper(text); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION fcn_upper(text) RETURNS text
    AS $_$
   DECLARE
    v_texto     ALIAS FOR $1;
    v_retorno   text := '';
   BEGIN
    IF v_texto IS NOT NULL THEN
     SELECT translate(upper(v_texto),'ביםףת‎אטלעשדץגךמפûהכןצüח','ֱֹֽ׃ÚÝְָּׂÙֳױֲÊ־װÛִֻֿײÜַ') INTO v_retorno;
    END IF;
    RETURN v_retorno;
   END;
  $_$
    LANGUAGE plpgsql;