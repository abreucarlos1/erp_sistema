<?php
/*
	Formulário de Menu Contratos/Controle
	
	Criado por Carlos Abreu  
	
	local/Nome do arquivo: ../contratos_controle/menucoordenacao.php
	
	Versão 0 --> VERSÃO INICIAL : 20/06/2013
	Versão 1 --> atualização layout - Carlos Abreu - 23/03/2017	
*/

require_once(implode(DIRECTORY_SEPARATOR,array('..','config.inc.php')));
	
require_once(INCLUDE_DIR."include_form.inc.php");

//VERIFICA SE O USUARIO POSSUI ACESSO AO MÓDULO 
//previne contra acesso direto	
if(!verifica_sub_modulo(306))
{
	nao_permitido();
}

$xajax->processRequests();

$smarty->assign("xajax_javascript",$xajax->printJavascript(XAJAX_DIR));

$smarty->assign("body_onload","xajax_monta_menu(306);"); //MÓDULO CONTRATOS

$conf = new configs();

$smarty->assign("revisao_documento","V1");

$smarty->assign("campo",$conf->campos('menucontratos'));

$smarty->assign("botao",$conf->botoes());

$smarty->assign("classe",CSS_FILE);

$template = TEMPLATES_DIR."menu.tpl";

$smarty->display($template);

?>
