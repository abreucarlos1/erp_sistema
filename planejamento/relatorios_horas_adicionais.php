<?php
/*
		Formulário de HORAS POR PERÍODO	
		
		Criado por Carlos Abreu
		
		local/Nome do arquivo:
		../planejamento/relatorios_horas_adicionais.php
		
		Versão 0 --> VERSÃO INICIAL : 02/03/2006		
		Versao 1 --> Atualização classe banco de dados - 22/01/2015 - Carlos Abreu
		Versão 2 --> Atualização Layout : 10/04/2015 - Carlos
		Versão 3 --> Atualização layout - Carlos Abreu - 03/04/2017
*/
require_once(implode(DIRECTORY_SEPARATOR,array('..','config.inc.php')));
	
require_once(INCLUDE_DIR."include_form.inc.php");

//VERIFICA SE O USUARIO POSSUI ACESSO AO MÓDULO 
//previne contra acesso direto	
if(!verifica_sub_modulo(261))
{
	nao_permitido();
}

$conf = new configs();
?>

<script src="<?php echo INCLUDE_JS ?>validacao.js"></script>

<script src="<?php echo INCLUDE_JS ?>datetimepicker.js"></script>

<?php
$array = array("JANEIRO","FEVEREIRO","MARÇO","ABRIL","MAIO","JUNHO","JULHO","AGOSTO","SETEMBRO","OUTUBRO","NOVEMBRO","DEZEMBRO");

for($i=1;$i<=12;$i++)
{
	$array_per_values[] = sprintf("%02d",$i);
	$array_per_output[] = $array[$i-1];
	if(date("m")==$i)
	{
		$index = sprintf("%02d",$i);
	}
}

$smarty->assign("option_per_values",$array_per_values);
$smarty->assign("option_per_id",$index);
$smarty->assign("option_per_output",$array_per_output);

$smarty->assign("data",date("d/m/Y"));

$smarty->assign('campo', $conf->campos('controle_horas_adicionais'));

$smarty->assign('revisao_documento', 'V3');

$smarty->assign("classe",CSS_FILE);

$smarty->display('relatorios_horas_adicionais.tpl');
?>