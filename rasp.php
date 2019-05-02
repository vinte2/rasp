<?php
//header('Content-Type: application/json');



$ch = curl_init();


curl_setopt($ch, CURLOPT_URL, "https://balneabilidade.ima.sc.gov.br/relatorio/historico");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,"municipioID=23&localID=39&ano=2018&redirect=true");
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);

$conteudo = curl_exec ($ch);
// echo $conteudo;

$doc =new DOMDocument();

$doc->loadHTML($conteudo);


$tables = $doc->getElementsByTagName('table');
// echo '<pre>';
$dados = [];

foreach ($tables as $key=>$table) {
	if($key!=0){ //ignora a tabela inicial de cabecalho
		if($key % 2 !=0){ //tabelas impares contem dados do ponto de coleta
			$pontos = [];
			$labels = $table->getElementsByTagName('label');
			$pontocoleta= [];
			foreach ($labels as $label) {
				$partes = explode(':',$label->nodeValue);
				$pontocoleta[str_replace(' ', '_',$partes[0])] = $partes[1];
					
			}
			array_push($pontos, $pontocoleta);
			array_push($dados,$pontos);

		}else{ //tabelas pares contem os dados das coletas
			$coletas =[];
					// cho "<pre>";
			$trs = $table->getElementsByTagName('tr');
			foreach ($trs as $tr) {
				$tds = $tr->getElementsByTagName('td');
				$coleta=[];
				foreach ($tds as $td) {
						
					$atributo = $td->getAttribute('class');
					$coleta [$atributo] = $td->nodeValue;
						
				}
				array_push($coletas, $coleta);
					
			}
			array_push($dados,$coletas);

		}
	}
}
echo '<pre>';
print_r($dados);
 // echo json_encode($dados);

 ?>
