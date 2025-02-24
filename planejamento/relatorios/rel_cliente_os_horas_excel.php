<?php
/*
		Relatório de Cliente x OS x Horas	
		
		Criado por Carlos Abreu
		
		local/Nome do arquivo:
		../planejamento/relatorios/rel_cliente_os_horas_excel.php
		
		Versão 0 --> VERSÃO INICIAL : 02/03/2006		
		Versão 1 --> atualização classe banco de dados - 22/01/2015 - Carlos Abreu
		Versão 2 --> Inclusão dos campos reg_del nas consultas - 20/11/2017 - Carlos Abreu
*/

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

require_once(implode(DIRECTORY_SEPARATOR,array('..','..','config.inc.php')));

$db = new banco_dados;

$dt_dataini = $_POST["dataini"];
$dt_datafim = $_POST["datafim"];

$sql = "SELECT empresas.empresa, empresas.cidade, os.os, funcionarios.id_funcionario, funcionarios.funcionario, SUM(TIME_TO_SEC(apontamento_horas.hora_normal)) AS HN, SUM(TIME_TO_SEC(apontamento_horas.hora_adicional)) AS HA, SUM(TIME_TO_SEC(apontamento_horas.hora_adicional_noturna)) AS HAN ";
$sql .= "FROM ".DATABASE.".empresas, ".DATABASE.".ordem_servico, ".DATABASE.".funcionarios, ".DATABASE.".apontamento_horas ";
$sql .= "WHERE ordem_servico.id_empresa = empresas.id_empresa ";
$sql .= "AND empresas.reg_del = 0 ";
$sql .= "AND ordem_servico.reg_del = 0 ";
$sql .= "AND funcionarios.reg_del = 0 ";
$sql .= "AND apontamento_horas.reg_del = 0 ";
$sql .= "AND ordem_servico.id_os = apontamento_horas.id_os "; 
$sql .= "AND apontamento_horas.id_funcionario = funcionarios.id_funcionario ";
$sql .= "AND apontamento_horas.data BETWEEN '" . php_mysql($dt_dataini) . "' AND '" . php_mysql($dt_datafim) . "' ";
$sql .= "GROUP BY apontamento_horas.id_funcionario, ordem_servico.id_os, empresas.id_empresa ";
$sql .= "ORDER BY empresas.empresa, empresas.cidade, ordem_servico.os, funcionarios.funcionario ";

$db->select($sql,'MYSQL',true);

$cont_horas = $db->array_select;

$filtro = '';

//CABEÇALHO
$conteudo = "<table width=\"100%\" border=\"1\">";

$conteudo .= "<tr><td colspan=6>Período: " . $dt_dataini . " - " . $dt_datafim . "</td></tr>";

if(!in_array($_SESSION["id_funcionario"],array('6','1229')))
{
	$conteudo .= "<tr><td><b>Cliente</b></td><td><b>OS</b></td><td><b>Funcionário</b></td><td colspan=3><b>Horas</b></td></tr>";

	$conteudo .= "<tr><td> </td><td> </td><td> </td><td><b>HN</b></td><td><b>HA</b></td><td><b>Total</b></td></tr>";
}
else
{
	$conteudo .= "<tr><td><b>Cliente</b></td><td><b>OS</b></td><td><b>Funcionário</b></td><td><b>Contrato</b></td><td colspan=3><b>Horas</b></td></tr>";

	$conteudo .= "<tr><td> </td><td> </td><td> </td><td> </td><td><b>HN</b></td><td><b>HA</b></td><td><b>Total</b></td></tr>";		
}

$str_empresa = "";
$str_os = "";
$str_funcionario = "";

$int_oshn = 0;
$int_osha = 0;
$int_clihn = 0;
$int_cliha = 0;
$int_tthn = 0;
$int_ttha = 0;

foreach($cont_horas as $reg_horas)
{	
	//Forma os grupos de ordenação
	if($str_os!==$reg_horas["os"])
	{
		$str_os = $reg_horas["os"];

		if($int_oshn>0)
		{
			if(!in_array($_SESSION["id_funcionario"],array('6','1229')))
			{
				//Apresenta o subtotal por OS
				$conteudo .= "<tr><td> </td><td><b>SUBTOTAL OS:</b></td><td> </td><td><b>" . sec_to_time($int_oshn) . "</b></td><td><b>" . sec_to_time($int_osha) . "</b></td><td><b>" . sec_to_time($int_oshn+$int_osha) . "</b></td></tr>";
			}
			else
			{
				$conteudo .= "<tr><td> </td><td><b>SUBTOTAL OS:</b></td><td> </td><td> </td><td><b>" . sec_to_time($int_oshn) . "</b></td><td><b>" . sec_to_time($int_osha) . "</b></td><td><b>" . sec_to_time($int_oshn+$int_osha) . "</b></td></tr>";
			}
		}
		
		//Reinicia os subtotais por empresa
		$int_oshn = 0;
		$int_osha = 0;

	}
	else
	{
		$str_os = "";		
	}	

	if($str_empresa!==$reg_horas["empresa"] . " - " . $reg_horas["cidade"])
	{
		$str_empresa = $reg_horas["empresa"] . " - " . $reg_horas["cidade"];
	
		if($int_clihn>0)
		{
			//Apresenta o subtotal por empresa
			if(!in_array($_SESSION["id_funcionario"],array('6','1229')))
			{
				$conteudo .= "<tr><td><b>TOTAL CLIENTE:</b></td><td> </td><td> </td><td><b>" . sec_to_time($int_clihn) . "</b></td><td><b>" . sec_to_time($int_cliha) . "</b></td><td><b>" . sec_to_time($int_clihn+$int_cliha) . "</b></td></tr>";
				$conteudo .= "<tr><td colspan=6> </td></tr>";
			}
			else
			{
				$conteudo .= "<tr><td><b>TOTAL CLIENTE:</b></td><td> </td><td> </td><td> </td><td><b>" . sec_to_time($int_clihn) . "</b></td><td><b>" . sec_to_time($int_cliha) . "</b></td><td><b>" . sec_to_time($int_clihn+$int_cliha) . "</b></td></tr>";
				$conteudo .= "<tr><td colspan=7> </td></tr>";					
			}
		}

		//Reinicia os subtotais por empresa
		$int_clihn = 0;
		$int_cliha = 0;
	}
	else
	{
		$str_empresa = "";
	}
	
	/*
	if(in_array($_SESSION["id_funcionario"],array('6','1229')))
	{
		$sql = "SELECT * FROM ".DATABASE.".salarios ";
		$sql .= "WHERE salarios.id_funcionario = '" . $reg_horas["id_funcionario"] . "' ";
		$sql .= "AND DATE_FORMAT(data , '%Y%m%d' ) <= '".str_replace("-","",date('Y-m-d'))."' ";
		$sql .= "AND salarios.reg_del = 0 ";
		$sql .= "ORDER BY id_salario DESC, data DESC LIMIT 1 ";
		
		$db->select($sql,'MYSQL',true);
		
		$cont1 = $db->array_select[0];
	}
	*/

	$str_funcionario = $reg_horas["funcionario"];	

	//Calcula os subtotais/totais (em seg)
	$int_oshn += $reg_horas["HN"];
	$int_clihn += $reg_horas["HN"];

	$int_osha += $reg_horas["HA"]+$reg_horas["HAN"];
	$int_cliha += $reg_horas["HA"]+$reg_horas["HAN"];	
	
	$int_tthn += $reg_horas["HN"];
	$int_ttha += $reg_horas["HA"]+$reg_horas["HAN"];

	//if(!in_array($_SESSION["id_funcionario"],array('6','1229')))
	//{
		$conteudo .= "<tr><td>" . $str_empresa .  "</td><td>" . $str_os . "</td><td>" . $str_funcionario . "</td><td>" . sec_to_time($reg_horas["HN"]) . "</td><td>" . sec_to_time($reg_horas["HA"]) . "</td><td>" . sec_to_time($reg_horas["HA"]+$reg_horas["HN"])  . "</td></tr>";
	//}
	//else
	//{
		//$conteudo .= "<tr><td>" . $str_empresa .  "</td><td>" . $str_os . "</td><td>" . $str_funcionario . "</td><td>" . $cont1[" tipo_contrato"] . "</td><td>" . sec_to_time($reg_horas["HN"]) . "</td><td>" . sec_to_time($reg_horas["HA"]) . "</td><td>" . sec_to_time($reg_horas["HA"]+$reg_horas["HN"])  . "</td></tr>";
	//}

	$str_empresa = $reg_horas["empresa"] . " - " . $reg_horas["cidade"];
	$str_os = $reg_horas["os"];

}

//Apresenta o subtotal por OS
//if(!in_array($_SESSION["id_funcionario"],array('6','1229')))
//{
	$conteudo .= "<tr><td> </td><td><b>SUBTOTAL OS:</b></td><td> </td><td><b>" . sec_to_time($int_oshn) . "</b></td><td><b>" . sec_to_time($int_osha) . "</b></td><td><b>" . sec_to_time($int_oshn+$int_osha) . "</b></td></tr>";
	$conteudo .= "<tr><td><b>TOTAL CLIENTE:</b></td><td> </td><td> </td><td><b>" . sec_to_time($int_clihn) . "</b></td><td><b>" . sec_to_time($int_cliha) . "</b></td><td><b>" . sec_to_time($int_clihn+$int_cliha) . "</b></td></tr>";
	$conteudo .= "<tr><td colspan=6> </td></tr>";

	//Apresenta o subtotal por empresa
	$conteudo .= "<tr><td><b>TOTAL DIRETO/PRODUÇÃO:</b></td><td> </td><td> </td><td><b>" . sec_to_time($int_tthn) . "</b></td><td><b>" . sec_to_time($int_ttha) . "</b></td><td><b>" . sec_to_time($int_tthn+$int_ttha) . "</b></td></tr>";
	$conteudo .= "<tr><td colspan=6> </td></tr>";
	$conteudo .= "<tr><td colspan=6> </td></tr>";
//}
//else
//{
	//$conteudo .= "<tr><td> </td><td><b>SUBTOTAL OS:</b></td><td> </td><td> </td><td><b>" . sec_to_time($int_oshn) . "</b></td><td><b>" . sec_to_time($int_osha) . "</b></td><td><b>" . sec_to_time($int_oshn+$int_osha) . "</b></td></tr>";
	//$conteudo .= "<tr><td><b>TOTAL CLIENTE:</b></td><td> </td><td> </td><td> </td><td><b>" . sec_to_time($int_clihn) . "</b></td><td><b>" . sec_to_time($int_cliha) . "</b></td><td><b>" . sec_to_time($int_clihn+$int_cliha) . "</b></td></tr>";
	//$conteudo .= "<tr><td colspan=7> </td></tr>";

	//Apresenta o subtotal por empresa
	//$conteudo .= "<tr><td><b>TOTAL DIRETO/PRODUÇÃO:</b></td><td> </td><td> </td><td> </td><td><b>" . sec_to_time($int_tthn) . "</b></td><td><b>" . sec_to_time($int_ttha) . "</b></td><td><b>" . sec_to_time($int_tthn+$int_ttha) . "</b></td></tr>";
	//$conteudo .= "<tr><td colspan=7> </td></tr>";
	//$conteudo .= "<tr><td colspan=7> </td></tr>";
//}


//CUSTOS INDIRETOS - ADMINISTRATIVO
$sql = "SELECT empresas.empresa, empresas.cidade, ordem_servico.os, funcionarios.funcionario, SUM(TIME_TO_SEC(apontamento_horas.hora_normal)) AS HN, SUM(TIME_TO_SEC(apontamento_horas.hora_adicional)) AS HA, SUM(TIME_TO_SEC(apontamento_horas.hora_adicional_noturna)) AS HAN ";
$sql .= "FROM ".DATABASE.".empresas, ".DATABASE.".ordem_servico, ".DATABASE.".funcionarios, ".DATABASE.".apontamento_horas ";
$sql .= "WHERE ordem_servico.id_empresa = empresas.id_empresa ";
$sql .= "AND empresas.reg_del = 0 ";
$sql .= "AND ordem_servico.reg_del = 0 ";
$sql .= "AND funcionarios.reg_del = 0 ";
$sql .= "AND apontamento_horas.reg_del = 0 ";
$sql .= "AND ordem_servico.id_os = apontamento_horas.id_os "; 
$sql .= "AND apontamento_horas.id_funcionario = funcionarios.id_funcionario ";
$sql .= "AND apontamento_horas.data BETWEEN '" . php_mysql($dt_dataini) . "' AND '" . php_mysql($dt_datafim) . "' ";
$sql .= "GROUP BY apontamento_horas.id_funcionario, ordem_servico.id_os, empresas.id_empresa ";
$sql .= "ORDER BY empresas.empresa, empresas.cidade, ordem_servico.os, funcionarios.funcionario ";

$db->select($sql,'MYSQL',true);

$cont_admind = $db->array_select;

$str_empresa = "";
$str_os = "";
$str_funcionario = "";

$int_admhn = 0;
$int_admha = 0;
$int_indhn = 0;
$int_indha = 0;
$int_tthn = 0;
$int_ttha = 0;

foreach($cont_admind as $reg_admind)
{	
	//Forma os grupos de ordenação
	if($str_os!==$reg_admind["os"])
	{
		$str_os = $reg_admind["os"];

		if($int_admhn>0)
		{
				if($reg_admind["os"]=="6")
				{
					//Apresenta o subtotal ADM
					$conteudo .= "<tr><td><b>TOTAL ADMINISTRATIVO:</b></td><td> </td><td> </td><td><b>" . sec_to_time($int_admhn) . "</b></td><td><b>" . sec_to_time($int_admha) . "</b></td><td><b>" . sec_to_time($int_admhn+$int_admha) . "</b></td></tr>";
					$conteudo .= "<tr><td colspan=6> </td></tr>";					
				}
		}
	}
	else
	{
		$str_os = "";		
	}	

	if($str_empresa!==$reg_admind["empresa"] . " - " . $reg_admind["cidade"])
	{
		$str_empresa = $reg_admind["empresa"] . " - " . $reg_admind["cidade"];
	}
	else
	{
		$str_empresa = "";
	}

	$str_funcionario = $reg_admind["funcionario"];	

	//Calcula os subtotais/totais (em seg)
	if(in_array($reg_admind["os"],array('1','3','4')))
	{
		$int_admhn += $reg_admind["HN"];
		$int_admha += $reg_admind["HA"]+$reg_admind["HAN"];			
	}
	else
	{
		$int_indhn += $reg_admind["HN"];
		$int_indha += $reg_admind["HA"]+$reg_admind["HAN"];	
	}


	$conteudo .= "<tr><td>" . $str_empresa .  "</td><td>" . $str_os . "</td><td>" . $str_funcionario . "</td><td>" . sec_to_time($reg_admind["HN"]) . "</td><td>" . sec_to_time($reg_admind["HA"]) . "</td><td>" . sec_to_time($reg_admind["HA"]+$reg_admind["HN"])  . "</td></tr>";


	$str_empresa = $reg_admind["empresa"] . " - " . $reg_admind["cidade"];
	$str_os = $reg_admind["os"];

}

//Apresenta o subtotal por OS
$conteudo .= "<tr><td><b>TOTAL INDIRETO/PRODUÇÃO:</b></td><td> </td><td> </td><td><b>" . sec_to_time($int_indhn) . "</b></td><td><b>" . sec_to_time($int_indha) . "</b></td><td><b>" . sec_to_time($int_indhn+$int_indha) . "</b></td></tr>";

echo $conteudo;

?>