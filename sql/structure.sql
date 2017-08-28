CREATE FUNCTION "unaccent"()
RETURNS VARCHAR
LANGUAGE SQL
NOT DETERMINISTIC
CONTAINS SQL
SQL SECURITY DEFINER
COMMENT E''
$$ 
declare
        p_str    alias for $1;
        v_str    varchar;
begin
        select translate(p_str, 'ÀÁÂÃÄÅ', 'AAAAAA') into v_str;
        select translate(v_str, 'ÉÈËÊ', 'EEEE') into v_str;
        select translate(v_str, 'ÌÍÎÏ', 'IIII') into v_str;
        select translate(v_str, 'ÌÍÎÏ', 'IIII') into v_str;
        select translate(v_str, 'ÒÓÔÕÖ', 'OOOOO') into v_str;
        select translate(v_str, 'ÙÚÛÜ', 'UUUU') into v_str;
        select translate(v_str, 'àáâãäå', 'aaaaaa') into v_str;
        select translate(v_str, 'èéêë', 'eeee') into v_str;
        select translate(v_str, 'ìíîï', 'iiii') into v_str;
        select translate(v_str, 'òóôõö', 'ooooo') into v_str;
        select translate(v_str, 'ùúûü', 'uuuu') into v_str;
        select translate(v_str, 'Çç', 'Cc') into v_str;
        return v_str;
end; $$

CREATE FUNCTION "simple_unaccent"()
RETURNS TEXT
LANGUAGE SQL
NOT DETERMINISTIC
CONTAINS SQL
SQL SECURITY DEFINER
COMMENT E''
$$ 
select
translate($1,'áàâãäăéèêëęíìïóòôõöúùûüÁÀÂÃÄÉÈÊËÍÌÏÓÒÔÕÖÚÙÛÜçÇ',
             'aaaaaaeeeeeiiiooooouuuuAAAAAEEEEIIIOOOOOUUUUcC');
 $$
