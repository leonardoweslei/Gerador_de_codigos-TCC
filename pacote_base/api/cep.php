<?php 
/*
 *	Função de busca de Endereço pelo CEP
 *	-	Desenvolvido Felipe Olivaes para ajaxbox.com.br
 *	-	Utilizando WebService de CEP da republicavirtual.com.br
 */
function busca_cep($cep){
	$resultado = @file_get_contents('http://republicavirtual.com.br/web_cep.php?cep='.urlencode($cep).'&formato=query_string');
	if(!$resultado){
		$resultado = "&resultado=0&resultado_txt=erro+ao+buscar+cep";
	}
	parse_str($resultado, $retorno); 
	return $retorno;
}


/*
 * Exemplo de utilização 
 */

//Vamos buscar o CEP 90020022
$resultado_busca = busca_cep('35300106');

echo "<pre> Array Retornada:
 ".print_r($resultado_busca, true)."</pre>";

switch($resultado_busca['resultado']){
	case '2':
		$texto = "
	Cidade com logradouro único
	<b>Cidade: </b> ".$resultado_busca['cidade']."
	<b>UF: </b> ".$resultado_busca['uf']."
		";	
	break;
	
	case '1':
		$texto = "
	Cidade com logradouro completo
	<b>Tipo de Logradouro: </b> ".$resultado_busca['tipo_logradouro']."
	<b>Logradouro: </b> ".$resultado_busca['logradouro']."
	<b>Bairro: </b> ".$resultado_busca['bairro']."
	<b>Cidade: </b> ".$resultado_busca['cidade']."
	<b>UF: </b> ".$resultado_busca['uf']."
		";
	break;
	
	default:
		$texto = "Fala ao buscar cep: ".$resultado_busca['resultado'];
	break;
}

echo $texto;
?>