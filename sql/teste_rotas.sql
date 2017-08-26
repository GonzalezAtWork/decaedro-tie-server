-- update pontos set id_bairro = 1
-- update pontos set cep = '' where CEP = 'NOT FOUND';
-- select endereco, cep from pontos where CEP != 'NOT FOUND' and CEP != 'WEB ERROR' and CEP != 'SQL ERROR' and CEP != '' order by cep;



select 
'update pontos set cep = \'|| cep ||'\,id_bairro=\'|| id_bairro ||'\ where id_ponto = \'|| id_ponto ||'\'
from (
	select id_ponto, gmaps_endereco, cep, 
	trim(both ', ' from 
		trim(both '- ' from 
			bairro
		)
	) as bairro  from
	(
		select id_ponto, gmaps_endereco, cep, substring( substring( bairro from (position('-' in bairro))) from (position(',' in substring( bairro from (position('-' in bairro)))))) as bairro 
		from
		(
			select id_ponto, gmaps_endereco, 
			substring( gmaps_endereco from (position(' - ' in gmaps_endereco) + 3)  for position(' S�o Paulo, ' in gmaps_endereco) - ( position(' - ' in gmaps_endereco) + 4 )) as bairro,
			substring( gmaps_endereco from (position(', Rep�blica Federativa do Brasil' in gmaps_endereco)-9)  for 9) as cep
			from pontos 
			where gmaps_endereco != ''
		)as bla
	)as ble
	--where bairro like '%-%' or bairro like '%,%'
) as bli
left join bairros on bli.bairro = bairros.nome
order by nome desc

--select * from pontos where gmaps_endereco != ''
--"Rua do Pinheirinho, 247-869 - Perus, S�o Paulo, 05215-000, Rep�blica Federativa do Brasil"