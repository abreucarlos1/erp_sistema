<?php
/*
	Formulário de cópia de perfis de usuários
	
	Criado por Carlos Abreu
	
	local/Nome do arquivo: 
	../administracao/perfis.php
	
	Versão 0 --> VERSÃO INICIAL : 20/05/2021

*/
require_once(implode(DIRECTORY_SEPARATOR,array('..','config.inc.php')));
	
require_once(INCLUDE_DIR."include_form.inc.php");

require_once(INCLUDE_DIR."antiInjection.php");

//VERIFICA SE O USUARIO POSSUI ACESSO AO MÓDULO 
//previne contra acesso direto	
if(!verifica_sub_modulo(498))
{
	nao_permitido();
}

function atualizatabela_permissoes($idUsuario, $origemDestino)
{
    $resposta = new xajaxResponse();
	
	$db = new banco_dados();
    
    $divLista = $origemDestino == 1 ? 'divListaOrigem' : 'divListaDestino';
 
    if (empty($idUsuario))
    {
        $resposta->addAssign($divLista, 'innerHTML', '');
        
        return $resposta;
    }
    
    $sql =
    "SELECT
			DISTINCT submodulos.id_sub_modulo, submodulos.sub_modulo
		FROM
			".DATABASE.".permissoes
	      	JOIN(
	        	SELECT sub_modulo, id_sub_modulo FROM ".DATABASE.".sub_modulos WHERE visivel = 1 AND reg_del = 0
	      	) submodulos
	      	ON submodulos.id_sub_modulo = permissoes.id_sub_modulo
			WHERE id_usuario = {$idUsuario}
	    ORDER BY
	        submodulos.sub_modulo";
    
    $xml = new XMLWriter();
    
    $xml->setIndent(false);
    $xml->openMemory();
    $xml->startElement('rows');
    
    $db->select($sql, 'MYSQL', function($reg, $i) use(&$xml){
        $xml->startElement('row');
        $xml->writeAttribute('id', $reg["id_sub_modulo"]);
        $xml->writeElement('cell', $i+1);
        $xml->writeElement('cell', $reg["id_sub_modulo"]);
        $xml->writeElement('cell', $reg["sub_modulo"]);
        $xml->endElement();	
    });
    
    $xml->endElement();
    
    $conteudo = $xml->outputMemory(false);
    
    
    $resposta->addScript("grid('".$divLista."', true, '450', '".$conteudo."');");
    
    return $resposta;
}

function copiar($dados_form)
{
    $resposta = new xajaxResponse();
    $db = new banco_dados();
    
    $idUsuarioOrigem  = $dados_form['selUsuariosOrigem'];
    $idUsuarioDestino = $dados_form['selUsuariosDestino'];
    
    if (empty($idUsuarioOrigem) || empty($idUsuarioDestino))
    {
        $resposta->addAlert('Por favor, selecione o usuário de Origem e usuário de Destino corretamente! ');
    }
    else
    {
        //Copia todas as permissões do usuário de origem para o usuário destino ignorando as já existentes
        $isql =
        "INSERT INTO
			".DATABASE.".permissoes
			(id_usuario, id_sub_modulo, permissao)
		SELECT
			".$idUsuarioDestino.", id_sub_modulo, permissao
		FROM
		".DATABASE.".permissoes
		WHERE
			id_usuario = ".$idUsuarioOrigem."
			AND id_sub_modulo NOT IN (SELECT id_sub_modulo FROM ".DATABASE.".permissoes WHERE reg_del = 0 AND id_usuario = ".$idUsuarioDestino.");";
        
        $db->insert($isql, 'MYSQL');
        
        if ($db->erro == '')
        {
            $resposta->addAlert('Permissoes do perfil copiadas corretamente!');
            $resposta->addScript("xajax_atualizatabela_permissoes(document.getElementById('selUsuariosDestino').value)");
        }
        else
            $resposta->addAlert('Houve uma falha ao tentar copiar as permissões do perfil! ');
    }
    
    return $resposta;
}

$xajax->registerFunction("atualizatabela_permissoes");
$xajax->registerFunction("copiar");

$xajax->processRequests();

$smarty->assign("xajax_javascript",$xajax->printJavascript(XAJAX_DIR));

$conf = new configs();

$array_func_values[] = "0";
$array_func_output[] = "SELECIONE";

$sql = "SELECT
			funcionario, id_usuario
		FROM
			".DATABASE.".funcionarios
			JOIN(
				SELECT id_funcionario, id_usuario, email FROM ".DATABASE.".usuarios #WHERE reg_del = 0
			) usuarios
			ON usuarios.id_usuario = funcionarios.id_usuario
		WHERE
			reg_del = 0
		ORDER BY
			funcionario";

$db->select($sql,'MYSQL',true);

if($db->erro!='')
{
    $resposta->addAlert($db->erro);
}

foreach($db->array_select as $regs)
{
    $array_func_values[] = $regs["id_usuario"];
    $array_func_output[] = $regs["funcionario"].' - '.$regs["id_usuario"];
}

$smarty->assign("option_func_values",$array_func_values);
$smarty->assign("option_func_output",$array_func_output);
$smarty->assign("revisao_documento","V2");

$smarty->assign('larguraTotal', 1);

$smarty->assign("nome_empresa",NOME_EMPRESA);

$smarty->assign("campo",$conf->campos('controle_emails'));

$smarty->assign("botao",$conf->botoes());

$smarty->assign("classe",CSS_FILE);

$smarty->display('perfis.tpl');

?>
<script src="<?php echo INCLUDE_JS ?>validacao.js"></script>
<script src="<?php echo INCLUDE_JS ?>dhtmlx_403/codebase/dhtmlx.js"></script>

<script>
function trocarOrigemDestino()
{
	origem = document.getElementById('selUsuariosOrigem').value;
	destino = document.getElementById('selUsuariosDestino').value;

	document.getElementById('selUsuariosOrigem').value = destino;
	document.getElementById('selUsuariosDestino').value = origem;

	xajax_atualizatabela_permissoes(destino, 1);
	xajax_atualizatabela_permissoes(origem, 2);
}

function grid(tabela, autoh, height, xml)
{
	mygrid = new dhtmlXGridObject(tabela);

	mygrid.enableAutoHeight(autoh,height);
	mygrid.enableRowsHover(true,'cor_mouseover');

	mygrid.setHeader(" ,ID,MODULO");
	mygrid.setInitWidths("50,50,*");
	mygrid.setColAlign("left,left,left");
	mygrid.setColTypes("ro,ro,ro");
	mygrid.setColSorting("str,str,str");

	mygrid.setSkin("dhx_skyblue");
    mygrid.enableMultiselect(true);
    mygrid.enableCollSpan(true);	
	mygrid.init();
	
	mygrid.loadXMLString(xml);
}
</script>

<?php

?>