<?php
function logs($banco, $conexao, $usuario, $acao,$db='', $id_registro=0)
{
	session_start();
/*
//	LOGA AS A�OES DOS USU�RIOS

$banco -> banco de inser��o dos dados
$conexao -> link da variavel $conexao
$usuario -> nome do usuario
$acao -> descri��o da a��o
$db -> banco alvo
$id_registro -> registro afetado

*/
	$formulario = explode("/",$_SERVER["PHP_SELF"]);
	
	$pagina = $formulario[count($formulario)-1];
	
	$incsql = "INSERT INTO logs.".$banco." ";
	$incsql .= " (ip, usuario, data, hora, acao, db, id_registro, formulario) ";
	$incsql .= "VALUES ('" . $_SERVER['REMOTE_ADDR'] . "', ";
	$incsql .= "'" . $usuario . "', ";
	$incsql .= "'" . date('Y-m-d') . "', ";
	$incsql .= "'" . date('H:i:s') . "', ";
	$incsql .= "'" . $acao . "', ";
	$incsql .= "'" . $db . "', ";
	$incsql .= "'" . $id_registro . "', ";
	$incsql .= "'" . $pagina. "') ";
	
	$r = mysql_query($incsql,$conexao) or die("N�o foi poss�vel a inser��o dos dados".$incsql);

}

?>
