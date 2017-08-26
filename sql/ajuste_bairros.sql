--select count(1) from pontos where gmaps_endereco = '' and gmaps_latitude != '-10.000000000000000'
--select gmaps_endereco from pontos where gmaps_endereco != ''
select 
'update pontos set id_bairro = ' || id_bairro || ' where id_ponto = ' || id_ponto || ';'
from
(
select id_ponto, id_bairro, cep, bairro, bairros.nome, gmaps_endereco, endereco
	from (
		select id_ponto, endereco, codigo_abrigo, gmaps_latitude, gmaps_longitude, id_padrao,
		replace(
			substring( 
				gmaps_endereco from (position(' - ' in gmaps_endereco) + 3)  for 
				(position(' - SP,' in gmaps_endereco) - (position(' - ' in gmaps_endereco) + 3))
			),', São Paulo',''
		) as bairro,
		replace( substring( gmaps_endereco from (position('SP,' in gmaps_endereco)+4)  for 9),'Repúblic','') as cep,
		gmaps_endereco 
		from pontos 
		where pontos.gmaps_endereco != '' 
		and pontos.gmaps_endereco != 'ERRO'
		and pontos.gmaps_endereco ilike '% - SP,%'
		and pontos.gmaps_endereco ilike '%, São Paulo%'
	) as bla
	left join bairros on bla.bairro ilike '%' || bairros.nome || '%'
where bairros.nome is not null and bairro not ilike '%São Paulo%'
) as ble