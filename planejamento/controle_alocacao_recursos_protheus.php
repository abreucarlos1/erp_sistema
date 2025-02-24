<?php
/*
		Formulário de Alocação recursos protheus
		
		Criado por Carlos Abreu
		
		local/Nome do arquivo:
		
		../planejamento/controle_alocacao_recursos_protheus.php
		
		Versão 0 --> VERSÃO INICIAL - 02/03/2006
		Versão 1 --> Atualização Lay-out | Smarty : 21/07/2008
		Versao 2 --> Atualização classe banco de dados - 22/01/2015 - Carlos Abreu
		Versão 3 --> Atualização layout - Carlos Abreu - 31/03/2017		
*/	

require_once(implode(DIRECTORY_SEPARATOR,array('..','config.inc.php')));
	
require_once(INCLUDE_DIR."include_form.inc.php");

//VERIFICA SE O USUARIO POSSUI ACESSO AO MÓDULO 
//previne contra acesso direto	
if(!verifica_sub_modulo(217))
{
	nao_permitido();
}

$filtro = '';

$coordenador = false;

function preencherec($id_equipe)
{
	$resposta = new xajaxResponse();
	
	$db = new banco_dados;
		
	$sql = "SELECT AE8_RECURS, AE8_DESCRI FROM AE8010 WITH(NOLOCK), AFA010 WITH(NOLOCK), AED010 WITH(NOLOCK) ";
	$sql .= "WHERE AE8010.D_E_L_E_T_ = '' ";
	$sql .= "AND AFA010.D_E_L_E_T_ = '' ";
	$sql .= "AND AED010.D_E_L_E_T_ = '' ";
	$sql .= "AND AE8_RECURS = AFA_RECURS ";
	$sql .= "AND AE8_EQUIP = AED_EQUIP ";
	
	if($id_equipe!=-1)
	{
		$sql .= "AND AE8_EQUIP = '".$id_equipe."' ";	
	}	
	
	$sql .= "AND AE8_ATIVO = '1' ";
	$sql .= "GROUP BY AE8_RECURS, AE8_DESCRI ";
	$sql .= "ORDER BY AE8_DESCRI ";
	
	$db->select($sql,'MSSQL', true);
	
	$limp = "xajax.$('recurso').length = null";
	
	$resposta->addScript($limp);
	
	$comb = "xajax.$('recurso').options[xajax.$('recurso').length] = new Option('TODOS OS RECURSOS','-1');";

	foreach($db->array_select as $recurs)
	{
		$comb .= "xajax.$('recurso').options[xajax.$('recurso').length] = new Option('".$recurs["AE8_DESCRI"] ."','". $recurs["AE8_RECURS"] ."');";
	}

	$resposta->addScript($comb);

	return $resposta;
}

$xajax->registerFunction("preencherec");

$xajax->processRequests();

$smarty->assign("xajax_javascript",$xajax->printJavascript(XAJAX_DIR));

?>

<script src="<?php echo INCLUDE_JS ?>validacao.js"></script>

<?php

$conf = new configs();

$db = new banco_dados;

$array_equipe_values = NULL;
$array_equipe_output = NULL;

$array_recurso_values = NULL;
$array_recurso_output = NULL;

if($coordenador)
{
	$array_equipe_values[] = "-1";
	$array_equipe_output[] = "TODAS AS EQUIPES";
}


$sql = "SELECT AED_EQUIP, AED_DESCRI FROM AE8010 WITH(NOLOCK), AFA010 WITH(NOLOCK), AED010 WITH(NOLOCK) ";
$sql .= "WHERE AE8010.D_E_L_E_T_ = '' ";
$sql .= "AND AFA010.D_E_L_E_T_ = '' ";
$sql .= "AND AED010.D_E_L_E_T_ = '' ";
$sql .= "AND AE8_RECURS = AFA_RECURS ";
$sql .= "AND AE8_EQUIP = AED_EQUIP ";
$sql .= "GROUP BY AED_EQUIP, AED_DESCRI ";
$sql .= "ORDER BY AED_DESCRI ";

$db->select($sql,'MSSQL', true);
	 
foreach($db->array_select as $regs)
{
	$array_equipe_values[] = $regs["AED_EQUIP"];
	$array_equipe_output[] = trim($regs["AED_DESCRI"]);
}

$sql = "SELECT AE8_RECURS, AE8_DESCRI FROM AE8010 WITH(NOLOCK), AFA010 WITH(NOLOCK), AED010 WITH(NOLOCK) ";
$sql .= "WHERE AE8010.D_E_L_E_T_ = '' ";
$sql .= "AND AFA010.D_E_L_E_T_ = '' ";
$sql .= "AND AED010.D_E_L_E_T_ = '' ";
$sql .= "AND AE8_RECURS = AFA_RECURS ";
$sql .= "AND AE8_EQUIP = AED_EQUIP ";
$sql .= "AND AE8_ATIVO = '1' ";
$sql .= "GROUP BY AE8_RECURS, AE8_DESCRI ";
$sql .= "ORDER BY AE8_DESCRI ";

$db->select($sql,'MSSQL', true);

if($db->numero_registros_ms>0)
{
	$array_recurso_values[] = "-1";
	$array_recurso_output[] = "TODOS OS RECURSOS";
}
						 
foreach($db->array_select as $regs)
{
	$array_recurso_values[] = $regs["AE8_RECURS"];
	$array_recurso_output[] = trim($regs["AE8_DESCRI"]);
}

$smarty->assign("option_equipe_values",$array_equipe_values);
$smarty->assign("option_equipe_output",$array_equipe_output);

$smarty->assign("option_recurso_values",$array_recurso_values);
$smarty->assign("option_recurso_output",$array_recurso_output);

$campo[1] = "ALOCAÇÃO RECURSOS - PROTHEUS";

$smarty->assign("campo",$campo);

$smarty->assign("revisao_documento",'V3');

$smarty->assign("classe",CSS_FILE);

$smarty->display('controle_alocacao_recursos_protheus.tpl');

?>