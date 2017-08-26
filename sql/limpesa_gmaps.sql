select 
id_ponto, codigo_abrigo, gmaps_latitude, gmaps_longitude  
--'update pontos set gmaps_latitude = *'|| gmaps_latitude ||'*, gmaps_longitude = *'|| gmaps_longitude ||'* where id_ponto = ' || id_ponto::varchar
from pontos 
--where gmaps_longitude = '-46.93696034140885'
--where char_length(gmaps_latitude) < 9
--where char_length(gmaps_latitude) > 9
where gmaps_latitude = ''

/*
update pontos set 
gmaps_latitude = '-10.000000000000000', gmaps_longitude = '-10.000000000000000'
-- gmaps_latitude = '', gmaps_longitude = ''
where 
-- gmaps_longitude = ''
gmaps_longitude = '-10.000000000000000'
*/
