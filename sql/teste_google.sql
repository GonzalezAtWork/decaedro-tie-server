-- select * from bairros order by nome
-- select endereco, gmaps_endereco from pontos where gmaps_endereco != '';
-- INSERT INTO bairros(nome,id_zona) 

	--select * from (
		--select distinct on (bairro) 'INSERT INTO bairros (nome, id_zona) values("'|| bairro ||'","1");' from (
		
select
--id_bairro,
--bairro,
-- gmaps_endereco, 
'INSERT INTO pontos(codigo_abrigo, endereco, gmaps_latitude, gmaps_longitude, id_padrao, id_bairro, cep, gmaps_endereco) '
||
'values('
||'"'||
codigo_abrigo
||'","'||
endereco
||'","'||
gmaps_latitude
||'","'||
gmaps_longitude
||'","'||
id_padrao
||'","'||
id_bairro
||'","'||
cep
||'","'||
gmaps_endereco
||'"'||
');' as dump
--			select id_ponto, id_bairro, cep, bairro, gmaps_endereco
			from (
				select id_ponto, endereco, codigo_abrigo, gmaps_latitude, gmaps_longitude, id_padrao,
				substring( gmaps_endereco from (position(' - ' in gmaps_endereco) + 3)  for position(' São Paulo, ' in gmaps_endereco) - ( position(' - ' in gmaps_endereco) + 4 )) as bairro,
				substring( gmaps_endereco from (position('São Paulo, ' in gmaps_endereco) + 11)  for 9) as cep,
				gmaps_endereco 
				from pontos where pontos.gmaps_endereco != '' and pontos.gmaps_endereco != 'ERRO'
			) as bla
			inner join bairros on bla.bairro = bairros.nome
			
		order by id_bairro
		
/*
where bairro = 'aim Paulista'
or  bairro = 'dade Dutra'
or  bairro = 'uatemi'
or  bairro = 'Vil'
*/
--	) ble order by bairro

	
