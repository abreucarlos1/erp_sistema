<?php
/*
		Formulário de Relatorio diario de obra
		
		Criado por Carlos Abreu
		
		local/Nome do arquivo:		
		../planejamento/relatorio_diario_obra.php
		
		Versão 0 --> VERSÃO INICIAL - 02/03/2006
		Versão 1 --> Atualização Lay-out | Smarty : 04/08/2008
		Versão 2 --> Atualização classe banco de dados - 22/01/2015 - Carlos Abreu
		Versão 3 --> Atualização Layout : 10/04/2015 - Carlos
		Versão 4 --> Atualização layout - Carlos Abreu - 03/04/2017
		Versão 5 --> Inclusão dos campos reg_del nas consultas - 20/11/2017 - Carlos Abreu
*/
require_once(implode(DIRECTORY_SEPARATOR,array('..','config.inc.php')));
	
require_once(INCLUDE_DIR."include_form.inc.php");

//VERIFICA SE O USUARIO POSSUI ACESSO AO MÓDULO 
//previne contra acesso direto	
if(!verifica_sub_modulo(182) || !verifica_sub_modulo(185))
{
	nao_permitido();
}

$conf = new configs();

function preencheos($status)
{
	$resposta = new xajaxResponse();
	
	$db = new banco_dados;
	
	$sql = "SELECT * FROM ".DATABASE.".apontamento_horas, ".DATABASE.".ordem_servico, ".DATABASE.".empresas ";
	$sql .= "WHERE apontamento_horas.id_os = ordem_servico.id_os ";
	$sql .= "AND apontamento_horas.reg_del = 0 ";
	$sql .= "AND ordem_servico.reg_del = 0 ";
	$sql .= "AND empresas.reg_del = 0 ";
	$sql .= "AND ordem_servico.id_empresa = empresas.id_empresa ";
	$sql .= "AND ordem_servico.id_os_status = '".$status."' ";
	$sql .= "GROUP BY ordem_servico.os ";
	$sql .= "ORDER BY ordem_servico.os ";
	 
	$db->select($sql,'MYSQL',true);

	$limp = "xajax.$('escolhaos').length = null";
	
	$resposta->addScript($limp);
	
	if($coordenador)
	{
		$comb = "xajax.$('escolhaos').options[xajax.$('escolhaos').length] = new Option('TODAS AS OS','-1');";
	}
	
	foreach($db->array_select as $os)
	{
		$comb .= "xajax.$('escolhaos').options[xajax.$('escolhaos').length] = new Option('". sprintf("%05d",$os["os"])." - ".$os["ordem_servico_cliente"]." - ".$os["empresa"] ."','". $os["id_os"] ."');";
	}

	$resposta->addScript($comb);

	return $resposta;

}

$xajax->registerFunction("preencheos");

$xajax->processRequests();

$smarty->assign("xajax_javascript",$xajax->printJavascript(XAJAX_DIR));

?>

<script src="<?php echo INCLUDE_JS ?>validacao.js"></script>

<?php
$array_status_values = NULL;
$array_status_output = NULL;

$array_status_values[] = "";
$array_status_output[] = "SELECIONE O STATUS";

$sql = "SELECT * FROM ".DATABASE.".ordem_servico_status ";
$sql .= "WHERE id_os_status IN (1,2,3,13)"; //andamento / as built / encerrada / adicional
$sql .= "AND ordem_servico_status.reg_del = 0 ";

$db->select($sql,'MYSQL',true);

foreach ($db->array_select as $regs)
{
	$array_status_values[] = $regs["id_os_status"];
	$array_status_output[] = $regs["os_status"];
}

$smarty->assign("option_status_values",$array_status_values);
$smarty->assign("option_status_output",$array_status_output);

$smarty->assign('campo', $conf->campos('relatorio_diario_de_obra'));

$smarty->assign('revisao_documento', 'V5');

$smarty->assign("classe",CSS_FILE);

$smarty->display('relatorio_diario_obra.tpl');	
?>