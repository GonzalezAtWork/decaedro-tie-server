select id_ponto, codigo_abrigo, endereco, gmaps_latitude, gmaps_longitude 
from pontos where 
gmaps_latitude in (
	select gmaps_latitude from (
	select  gmaps_latitude, count(1) as bla from pontos 
	where char_length(gmaps_latitude) > 10 and gmaps_latitude != '-10.000000000000000'
	group by gmaps_latitude
	) as fas where bla > 1
)
order by gmaps_latitude

