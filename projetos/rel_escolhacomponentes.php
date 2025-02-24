<?php
/*

		Formulário de ESCOLHA DE COMPONENTES PARA ESPEC. TEC.	
		
		Criado por Carlos Abreu 
		
		local/Nome do arquivo:
		../projetos/rel_escolhacomponentes.php
		
		data de criação: 10/05/2006
		
		Versão 0 --> VERSÃO INICIAL
		Versão 1 --> Retomada do uso -   / alterado por Carlos Abreu - 10/03/2016
	
*/	
	
//Obtém os dados do usuário
session_start();
if(!isset($_SESSION["id_usuario"]) || !isset($_SESSION["nome_usuario"]))
{
	// Usuário não logado! Redireciona para a página de login
	header("Location: ../index.php");
	exit;
}
	
	
//include ("../includes/layout.php");
include ("../includes/conectdb.inc.php");
include ("../includes/tools.inc.php");

$db = new banco_dados;

?>


<html>
<head>
<title>: : . ESPECIFICAÇÃO TÉCNICA / ÁREA . : :</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<!-- Javascript para validação de dados -->
<script type="text/javascript" src="../includes/validacao.js"></script>
<!-- Javascript para declaração de variáveis / checagem do estilo - MAC/PC -->

<!-- Javascript para envio dos dados através do método GET -->
<script>

function enviar(componente)
{

	if(componente!='')
	{
		//document.forms['os'].projeto.value=projeto;
		document.forms['frm_componentes'].submit();
	}

}

//Função para redimensionar a janela.
function maximiza() {

window.resizeTo(screen.width,screen.height);
window.moveTo(0,0);
}


</script>


<link href="../classes/estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
-->
</style></head>
<body  class="body">
<center>
<form name="frm_componentes" id="frm_componentes" method="post" action="rel_espec_tec_componentes.php" target="_blank">
<table width="100%" height="10%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center">	
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tr>
        <td bgcolor="#BECCD9" align="left"></td>
      </tr>
      <tr>
        <td height="33" bgcolor="#000099" class="menu_superior"></td>
 	  </tr>
      <tr>
        <td height="25" align="left" bgcolor="#000099" class="menu_superior"> </td>
      </tr>
      <tr>
        <td align="left" bgcolor="#BECCD9" class="menu_superior"> </td>
      </tr>
	  <tr>


      </tr>
      <tr>
        <td>
		  <table width="100%" height="100%" border="0">
            <tr>
              <td> </td>
              <td> </td>
              <td> </td>
              <td> </td>
            </tr>
            <tr>
              <td> </td>
              <td> </td>
              <td> </td>
              <td> </td>
            </tr>
            <tr>
              <td colspan="4" align="center" class="kks_nivel1">ESCOLHA O COMPONENTE </td>
              </tr>
            <tr>
              <td class="btn"> </td>
              <td colspan="2" rowspan="3" class="btn"><font size="2" face="Arial, Helvetica, sans-serif">
                <select name="id_dispositivo" id="id_dispositivo" class="txt_box" onChange="enviar(this[selectedIndex].value)">
                  <option value="">SELECIONE</option>
				  <?php
						  	$sql = "SELECT * FROM Projetos.area, Projetos.subsistema, Projetos.malhas, Projetos.componentes, Projetos.especificacao_tecnica, Projetos.especificacao_padrao, Projetos.dispositivos, Projetos.processo ";
							$sql .= "WHERE area.os = '" .$_SESSION["os"] . "' ";
							$sql .= "AND area.id_area = subsistema.id_area ";
							$sql .= "AND subsistema.id_subsistema = malhas.id_subsistema ";
							$sql .= "AND malhas.id_malha = componentes.id_malha ";
							$sql .= "AND malhas.id_processo = processo.id_processo ";
							$sql .= "AND componentes.id_componente = especificacao_tecnica.id_componente ";
							$sql .= "AND especificacao_tecnica.id_especificacao_padrao = especificacao_padrao.id_especificacao_padrao ";
							$sql .= "AND especificacao_padrao.id_dispositivo = dispositivos.id_dispositivo ";
							$sql .= "GROUP BY dispositivo, nr_malha ORDER BY sequencia ";
							
							$reg = $db->select($sql,'MYSQL');
							
							while ($regs = mysqli_fetch_array($reg))
								{
									?>
                  					<option value="<?= $regs["id_dispositivo"]."#".$regs["nr_malha"] ?>"<?php if($regs["id_dispositivo"]==$_POST["id_dispositivo"]){echo 'selected';} ?>>
                    				<?= $regs["nr_malha"] . " " . $regs["dispositivo"] . " - " .$regs["ds_dispositivo"]?>
                    				</option>
                  					<?php
								}
				  ?>
                </select>
              </font></td>
              <td class="btn"> </td>
            </tr>
            <tr>
              <td class="btn"> </td>
              <td class="btn"> </td>
            </tr>
            <tr>
              <td class="btn"> </td>
              <td class="btn"> </td>
            </tr>
            <tr>
              <td colspan="4" class="btn"><input name="Inserir2" type="button" class="btn" id="Inserir2" value="VOLTAR" onclick="javascript:history.back()"></td>
              </tr>
            <tr>
              <td> </td>
              <td> </td>
              <td> </td>
              <td> </td>
            </tr>
            <tr>
              <td> </td>
              <td> </td>
              <td> </td>
              <td> </td>
            </tr>
          </table>
		</td>
      </tr>
      
    </table>
	</td>
  </tr>
</table>
</form>
</center>
</body>
</html>