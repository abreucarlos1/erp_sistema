<?php
/*
		Formulário de Relatorio Fechamento	
		
		Criado por Carlos Abreu  
		
		local/Nome do arquivo:
		../financeiro/relatorio_fachamento.php
	
		Versão 0 --> VERSÃO INICIAL : 10/03/2006
		Versão 1 --> atualização classe banco de dados - 21/01/2015 -  Carlos Abreu
		Versão 2 --> alteração do caminho diretorio financeiro - Carlos Abreu - 06/07/2016
		Versão 3 --> Atualização layout - 20/07/2016		
		Versão 4 --> atualização layout - Carlos Abreu - 28/03/2017
*/
require_once(implode(DIRECTORY_SEPARATOR,array('..','config.inc.php')));
	
require_once(INCLUDE_DIR."include_form.inc.php");

//VERIFICA SE O USUARIO POSSUI ACESSO AO MÓDULO 
//previne contra acesso direto	
if(!verifica_sub_modulo(574))
{
	nao_permitido();
}

function atualizatabela()
{
	$resposta = new xajaxResponse();

	$db = new banco_dados;
	
	$xml = new XMLWriter();
	 
	$dh = opendir(DOCUMENTOS_FINANCEIRO.COMPROVANTES_FECHAMENTO); 
	
	// loop que busca todos os arquivos até que não encontre mais nada - Cria um array 
	while (false !== ($filename = readdir($dh))) 
	{
		$filename_array = explode(" ", $filename);
		
		if($filename_array[0]=='FECHAMENTO')
		{
			// verificando se o arquivo é .pdf 
			if (substr($filename,-4) == ".pdf") 
			{ 
				$periodo_ordem = $filename_array[1] . " " . $filename_array[0];	

				$filearray[$filename] = $periodo_ordem;
		
			}
		}
	}

	if($filearray)
	{
		arsort($filearray);
	}

	$numeroarquivos = sizeof($filearray);	
	
	$xml->openMemory();
	$xml->setIndent(false);
	$xml->startElement('rows');
	
	//Loop para preencher a tabela de arquivos.	
	for($x=0;$x<$numeroarquivos;$x++)
	{
		//Divide o array
		$eachfile = each($filearray);
		
		//Seta o nome do arquivo
		$filename = $eachfile[0];
		
		//Explode o nome do arquivo em um array
		$arquivo = explode(" ",$filename);
		
		$tipo = $arquivo[0];
		
		$periodo = $arquivo[1];
		
		$dt_geracao = $arquivo[2];
		
		$xml->startElement('row');
			$xml->writeElement('cell','<a href="../includes/documento.php?documento='.DOCUMENTOS_FINANCEIRO.COMPROVANTES_FECHAMENTO.$filename.'&janela=NO"><img src="'.DIR_IMAGENS.'file_pdf.png" alt="Clique p/ visualizar" border=0></a>');
			$xml->writeElement('cell',$tipo);
			$xml->writeElement('cell',substr($periodo,4,2) . "/" . substr($periodo,0,4) . " - " . substr($periodo,-2,2) . "/" . substr($periodo,-6,4));
			$xml->writeElement('cell',substr($dt_geracao,0,2) . "/" . substr($dt_geracao,2,2) . "/" . substr($dt_geracao,4,4) . " " . date("H:i:s",filemtime(DOCUMENTOS_FINANCEIRO.COMPROVANTES_FECHAMENTO.$filename)));
			$xml->writeElement('cell','<img src="'.DIR_IMAGENS.'apagar.png" alt="Deletar" onclick=excluir("'.str_replace(" ","%20%",$filename).'"); width="16" height="16" border="0">');
		$xml->endElement();	
	} 

	$xml->endElement();
	
	$conteudo = $xml->outputMemory(false);

	$resposta->addScript("grid('arquivos', true, '550', '".$conteudo."');");
	
	return $resposta;
}

function excluir($file)
{
	$resposta = new xajaxResponse();
			
	if(unlink(DOCUMENTOS_FINANCEIRO.COMPROVANTES_FECHAMENTO . $file))
	{
		$resposta->addAlert('Excluido com sucesso.');
	}
	else
	{
		$resposta->addAlert('Erro ao excluir arquivo.');	
	}

	$resposta->addScript("xajax_atualizatabela();");
	
	
	return $resposta;
}


function arquivo($dados_form)
{
	$resposta = new xajaxResponse();
	
	$datas = explode(",",$dados_form["periodo"]);
	
	if(file_exists(DOCUMENTOS_FINANCEIRO.COMPROVANTES_FECHAMENTO . "FECHAMENTO " . str_replace("-","",$datas[0]) . "-" . str_replace("-","",$datas[1]) . " " . date("dmY") . '.pdf'))
	{
		$resposta->addScript('gerar_arquivo(true)');
	}
	else
	{
		$resposta->addScript('gerar_arquivo(false)');
	}
	
	return $resposta;
}

$xajax->registerFunction("atualizatabela");

$xajax->registerFunction("excluir");

$xajax->registerFunction("arquivo");

$xajax->processRequests();

$smarty->assign("xajax_javascript",$xajax->printJavascript(XAJAX_DIR));

$smarty->assign("body_onload","xajax_atualizatabela();");

$conf = new configs();

$array_periodo_values[] = '';

$array_periodo_output[] = 'SELECIONE O PERÍODO';

$sql = "SELECT periodo FROM ".DATABASE.".fechamento_folha ";
$sql .= "WHERE fechamento_folha.reg_del = 0 ";
$sql .= "GROUP BY fechamento_folha.periodo ";
$sql .= "ORDER BY fechamento_folha.periodo DESC ";

$db->select($sql,'MYSQL',true);

foreach($db->array_select as $cont_periodo)
{
	
	$array_periodo = explode(",",$cont_periodo["periodo"]);
	$per_dataini = substr($array_periodo[0],-2,2) . "/" . substr($array_periodo[0],0,4);
	$per_datafin = substr($array_periodo[1],-2,2) . "/" . substr($array_periodo[1],0,4);
	
	$array_periodo_values[] = $cont_periodo["periodo"];
	$array_periodo_output[] = $per_dataini . " - " . $per_datafin;
		
}

$smarty->assign("option_periodo_values",$array_periodo_values);

$smarty->assign("option_periodo_output",$array_periodo_output);

$smarty->assign("revisao_documento","V4");

$smarty->assign("campo",$conf->campos('relatorio_fechamento'));

$smarty->assign("botao",$conf->botoes());

$smarty->assign("classe",CSS_FILE);

$smarty->display('relatorio_fechamento.tpl');

?>

<script src="<?php echo INCLUDE_JS ?>validacao.js"></script>

<script src="<?php echo INCLUDE_JS ?>dhtmlx_403/codebase/dhtmlx.js"></script>

<script>

function excluir(filename)
{
	var file = filename.replace(/%20%/g," ");
	
	if(confirm('Tem certeza que deseja apagar o arquivo '+file+' ?'))
	{
		xajax_excluir(file);
	}
}

function grid(tabela, autoh, height, xml)
{
	mygrid = new dhtmlXGridObject(tabela);
	mygrid.enableAutoHeight(autoh,height);
	mygrid.enableRowsHover(true,'cor_mouseover');

	mygrid.setHeader('Arquivo,Tipo,Período,Gerado,E');
	mygrid.setInitWidths("*,*,*,*,50");
	mygrid.setColAlign("center,center,center,center,center");
	mygrid.setColTypes("ro,ro,ro,ro,ro");
	mygrid.setColSorting("str,str,str,str,str");
	
	mygrid.setSkin("dhx_skyblue");
    mygrid.enableMultiselect(true);
    mygrid.enableCollSpan(true);	
	mygrid.init();
	mygrid.loadXMLString(xml);
}

function gerar_arquivo(gerar)
{
	if(gerar)
	{
		if(confirm('O Relatório selecionado já existe no arquivo, e pode ser visualizado através do botão "Arquivo". Deseja continuar e substituir o anterior?'))
		{
			document.getElementById('frm').submit();					
		}
	}
	else
	{
		document.getElementById('frm').submit();	
	}

}

</script>