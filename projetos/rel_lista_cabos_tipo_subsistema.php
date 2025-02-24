<?php
define('FPDF_FONTPATH','../includes/font/');
require("../includes/fpdf.php");
require("../includes/tools.inc.php");
include("../includes/conectdb.inc.php");

class PDF extends FPDF
{
//Page header
function Header()
{

	$this->Image($this->Logotipocliente(),13,15,60,25);
//	$this->Image($this->Logotipocliente(),13,23,60,12);

	//$this->Line(20,27.5,70,27.5);
	
	//$this->Image("../logotipos/logo_horizontal.jpg",23,30,45,7.5);
    //Arial bold 12
    //Titulo(Largura,Altura,Texto,Borda,Quebra de Linha,Alinhamento,Preenchimento
	//$this->Ln(1);
	
	$this->SetFont('Arial','',6);
	//Informações do Centro de Custo
	$this->Cell(66,8,'',0,0,'L',0); // CÉLULA LOGOTIPO 146
	$this->SetFont('Arial','B',12);
	$this->Cell(140,8,$this->Cliente(),1,1,'C',0); // CÉLULA CLIENTE
	
	$this->Image("../logotipos/logo_horizontal.jpg",219,17,59,10);
	
	$this->SetFont('Arial','B',10);
	$this->Cell(66,5.5,'',0,0,'L',0); // CÉLULA LOGOTIPO 
	$this->HCell(140,5.5,$this->Subsistema() . " / " .$this->Area() ,1,1,'C',0); // CÉLULA AREA / SUBSISTEMA

	$this->Cell(66,5.5,'',0,0,'L',0); // CÉLULA LOGOTIPO
	$this->SetFont('Arial','B',10);
	$this->Cell(140,5.5,"LISTA DE CABOS",1,0,'C',0); // CÉLULA COMPONENTE
	
	
	$X = $this->GetX();
	$this->Cell(64,5.5,'',1,0,'C',0);
	$this->SetX($X);
	$this->SetFont('Arial','',5);
	$this->Cell(5,5.5,'Nº: ',0,0,'L',0);
	$this->SetFont('Arial','B',8);
	$this->Cell(55,5.5,$this->Numdvm(),0,1,'C',0);

	$this->Cell(66,5.5,'',0,0,'L',0); // CÉLULA LOGOTIPO

	$this->SetFont('Arial','B',10);
	$this->HCell(140,5.5,$this->Titulo(),1,0,'C',0);
	
	$X = $this->GetX();
	$this->Cell(30,5.5,'',1,0,'C',0);
	$this->SetFont('Arial','',5);
	$this->SetX($X);
	$this->Cell(10,5.5,'DATA: ',0,0,'L',0);
	$this->SetFont('Arial','B',6);
	$this->Cell(20,5.5,$this->Emissao(),0,0,'L',0);
	
	$X = $this->GetX();
	$this->Cell(14,5.5,'',1,0,'C',0);
	$this->SetFont('Arial','',5);
	$this->SetX($X);
	$this->Cell(6,5.5,'REV: ',0,0,'L',0);
	$this->SetFont('Arial','B',6);
	$this->Cell(8,5.5,$this->Revisao(),0,0,'R',0);
	
	
	$X = $this->GetX();
	$this->Cell(20,5.5,'',1,0,'C',0);
	$this->SetFont('Arial','',4);
	$this->SetX($X);
	$this->Cell(8,5.5,'FL: ',0,0,'L',0);
	$this->SetFont('Arial','B',6);
	$this->Cell(10,5.5,$this->PageNo().' / {nb}',0,1,'R',0);
	
	$this->SetFont('Arial','B',8);
	$this->HCell(66,5.5,$this->unidade(),1,0,'C',0); // CÉLULA LOGOTIPO
	$this->HCell(140,5.5,$this->Titulo2(),1,0,'C',0);

	$X = $this->GetX();
	$this->Cell(64,5.5,'',1,0,'C',0);
	$this->SetFont('Arial','',5);
	$this->SetX($X);
	$this->Cell(17,5.5,'Nº CLIENTE: ',0,0,'L',0);
	$this->SetFont('Arial','B',8);
	$this->Cell(30,5.5,$this->Numcliente(),0,1,'C',0);	
	
	$this->SetFont('Arial','',9);
    //Seta a espessura da linha
	$this->SetLineWidth(0.5);
	//Seta a cor da linha
	$this->SetDrawColor(0,0,0);

	/*
	
	$this->Line(20,15,280,15); // LINHA SUPERIOR
	$this->Line(20,45,280,45); // LINHA INFERIOR
	$this->Line(20,15,20,45); // LINHA ESQUERDA
		
	//$this->Line(20,15,20,280); // LINHA ESQUERDA
	//$this->Line(20,280,195,280); // LINHA INFERIOR pagina
	$this->Line(280,15,280,45); // LINHA DIREITA
	//$this->Line(195,15,195,280); // LINHA DIREITA 
	$this->Line(80,15,80,45); // LINHA LOGOTIPO aqui
	$this->Line(220,15,220,45); // LINHA DOC / FOLHA
	*/

	//LINHAS NOVAS - 20/07/2006
	$this->Line(10,15,280,15); // LINHA SUPERIOR
	$this->Line(10,45,280,45); // LINHA INFERIOR
	$this->Line(10,15,10,45); // LINHA ESQUERDA
		
	//$this->Line(20,15,20,280); // LINHA ESQUERDA
	//$this->Line(20,280,195,280); // LINHA INFERIOR pagina
	$this->Line(280,15,280,45); // LINHA DIREITA
	//$this->Line(195,15,195,280); // LINHA DIREITA 
	$this->Line(76,15,76,45); // LINHA LOGOTIPO aqui
	$this->Line(216,15,216,45); // LINHA DOC / FOLHA
	//ATÉ AQUI

	$this->SetLineWidth(0,5);
	
	$this->Ln(2);
	
	$this->SetXY(10,48);
}

//Page footer
function Footer()
{ 
}
}

$db = new banco_dados;
$db->db = 'ti';
$db->conexao_db();

if($_POST["disciplina"]!='')
{
	
	$sql = "SELECT * FROM ".DATABASE.".setores ";
	$sql .= "WHERE id_setor = '".$_POST["disciplina"]."' ";
	$registro = mysql_query($sql,$db->conexao) or die("Não foi possível fazer a seleção.");
	$cont = mysql_fetch_array($registro);
	$disciplina = $cont["setor"];
	$abrdisc = $cont["abreviacao"];
	
	$filtro = "AND cabos.id_disciplina = '".$_POST["disciplina"]."' ";
	
}
else
{
	$disciplina = 'GERAL';
	$abrdisc = 'GER';
	$filtro = "";
}


if($_POST["id_area"]!='')
{


	$filtro .= "AND area.id_area = '" . $_POST["id_area"] . "' ";

}
else
{
	$area = 'GERAL';
	$filtro .= "";
}


session_cache_limiter('private');
session_start();

$sql_rev0 = "SELECT * FROM ".DATABASE.".revisao_cliente ";
$sql_rev0 .= "WHERE id_os = '".$_SESSION["id_os"]."' ";
$sql_rev0 .= "AND tipodoc = '".$_POST["relatorio"]."' ";
//$sql_rev0 .= "AND numero_cliente = '".$_POST["numero_cliente"]."' ";
$sql_rev0 .= "AND numeros_interno = '".$_POST["numeros_interno"]."' ";
$sql_rev0 .= "ORDER BY versao_documento ASC LIMIT 1 ";

$reg_rev0 = mysql_query($sql_rev0,$db->conexao) or die("Não foi possível fazer a seleção.2" . $sql_rev0);

$revis0 = mysql_fetch_array($reg_rev0);

$sql_rev = "SELECT * FROM ".DATABASE.".revisao_cliente ";
$sql_rev .= "WHERE id_os = '".$_SESSION["id_os"]."' ";
$sql_rev .= "AND tipodoc = '".$_POST["relatorio"]."' ";
$sql_rev .= "AND versao_documento NOT LIKE '".$revis0["versao_documento"]."' ";
//$sql_rev .= "AND numero_cliente = '".$_POST["numero_cliente"]."' ";
$sql_rev .= "ORDER BY versao_documento DESC LIMIT 5 ";

$reg_rev = mysql_query($sql_rev,$db->conexao) or die("Não foi possível fazer a seleção.2" . $sql);


$sql = "SELECT * FROM ".DATABASE.".caminho_docs, ".DATABASE.".OS ";
$sql .= "WHERE caminho_docs.id_os = '".$_SESSION["id_os"]."' ";
$sql .= "AND caminho_docs.id_os = OS.id_os ";

$registro = mysql_query($sql,$db->conexao) or die("Não foi possível fazer a seleção.2" . $sql);

$path1 = mysql_fetch_array($registro);

$path = str_replace('\\','/',$path1["caminho_pasta"]);

$caminho = "/home/dt_arqtec/".$path."/".$path1["os"]."-DOCS_EMITIDOS/".$path1["os"]."-".$abrdisc."/";

$pasta = explode("/",$_SERVER['SCRIPT_FILENAME']);


//Instanciation of inherited class
$pdf=new PDF('L','mm',A4);
$pdf->SetAutoPageBreak(false,10);
$pdf->SetMargins(10,15);
$pdf->SetLineWidth(0.2);

$sql1 = "SELECT OS, logotipo, OS.descricao AS osdesc, empresas.empresa, unidades.descricao AS unidade FROM ".DATABASE.".OS, ".DATABASE.".empresas, ".DATABASE.".unidades ";
$sql1 .= "WHERE id_os = '" . $_SESSION["id_os"] . "' ";
$sql1 .= "AND OS.id_empresa = empresas.id_empresa ";
$sql1 .= "AND empresas.id_unidade = unidades.id_unidade ";
$registro1 = mysql_query($sql1,$db->conexao) or die("Não foi possível a seleção dos dados" . $sql1);
$reg1 = mysql_fetch_array($registro1);


$sql_local = "SELECT locais.nr_sequencia, equipamentos.cd_local, area.ds_divisao, area.ds_area FROM Projetos.locais, Projetos.equipamentos, Projetos.area ";
$sql_local .= "WHERE locais.id_equipamento = equipamentos.id_equipamentos ";
$sql_local .= "AND locais.id_area = area.id_area ";
$sql_local .= "AND locais.id_local = '" . $_POST["id_local"] . "' ";

$registro = mysql_query($sql_local,$db->conexao) or die("Não foi possível a seleção dos dados" . $sql);
$reg = mysql_fetch_array($registro);

//Seta o cabeçalho
//$pdf->departamento="ENGENHARIA";

$pdf->cliente=$reg1["empresa"]; // Cliente
$pdf->subsistema = $reg["ds_divisao"]; // DIVISÃO
$pdf->area = $reg["ds_area"]; // ÁREA
$pdf->logotipocliente = $reg1["logotipo"]; // logotipo Cliente

$pdf->numeros_interno = $_POST["numeros_interno"];

$pdf->numero_cliente = $_POST["numero_cliente"];

$pdf->unidade= $reg1["unidade"];

$pdf->versao_documento = $_POST["versao_documento"];

$pdf->titulo = $reg["cd_local"] . " " . $reg["nr_sequencia"];
$pdf->titulo2 = $reg1["osdesc"];

$pdf->emissao=date('d/m/Y');
//$pdf->versao_documento=$data_ini . " á " . $datafim;

$pdf->AliasNbPages();

$pdf->AddPage('L');

//$pdf->Ln(2);

$pdf->SetLineWidth(0.5);

$pdf->Line(10,15,10,195); // LINHA ESQUERDA
$pdf->Line(10,195,280,195); // LINHA INFERIOR pagina
$pdf->Line(280,15,280,195); // LINHA DIREITA
$pdf->SetLineWidth(0.2);

// Página de rosto abaixo
$pdf->SetXY(10,70);

$pdf->SetFont('Arial','BU',20);
$pdf->Cell(280,10,"LISTA DE CABOS",0,1,'C',0);
$pdf->SetFont('Arial','BU',16);
$pdf->Cell(280,10,$disciplina,0,1,'C',0);
$pdf->Ln(5);
$pdf->SetFont('Arial','B',16);
$pdf->Cell(280,10, $reg["ds_divisao"] ,0,1,'C',0);
$pdf->Ln(5);
$pdf->Cell(280,10, $reg["ds_area"] ,0,1,'C',0);
$pdf->Ln(5);
$pdf->Cell(280,10, $reg["cd_local"] . " " . $reg["nr_sequencia"] ,0,1,'C',0);

//REVISÕES
$pdf->SetFont('Arial','B',8);

$y = 155;

$pdf->SetXY(25,$y);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(50,4,'CONTROLE DE REVISÕES',0,1,'L',0);
$pdf->SetFont('Arial','',6);

$pdf->Ln(1);

$numregs = 4 - mysql_num_rows($reg_rev);

//células em branco
for($a=0;$a<=$numregs;$a++)
{
	$y += 4;
	$pdf->SetXY(25,$y);
	$pdf->Cell(10,4,'',1,0,'C',0);
	$pdf->Cell(70,4,'',1,0,'C',0);
	$pdf->Cell(20,4,'',1,0,'C',0);
	$pdf->Cell(20,4,'',1,0,'C',0);
	$pdf->Cell(20,4,'',1,0,'C',0);
	$pdf->Cell(20,4,'',1,0,'C',0);
}


while($revis = mysql_fetch_array($reg_rev))
{
	$sql_exe = "SELECT abreviacao FROM ".DATABASE.".Funcionarios ";
	$sql_exe .= "WHERE id_funcionario = '".$revis["id_executante"]."' ";
	$regexe = mysql_query($sql_exe,$db->conexao) or die("Não foi possível a seleção dos dados" . $sql_exe);
	$executante = mysql_fetch_array($regexe);
	
	$sql_ver = "SELECT abreviacao FROM ".DATABASE.".Funcionarios ";
	$sql_ver .= "WHERE id_funcionario = '".$revis["id_verificador"]."' ";
	$regver = mysql_query($sql_ver,$db->conexao) or die("Não foi possível a seleção dos dados" . $sql_ver);
	$verificador = mysql_fetch_array($regver);
	
	$sql_apr = "SELECT abreviacao FROM ".DATABASE.".Funcionarios ";
	$sql_apr .= "WHERE id_funcionario = '".$revis["id_aprovador"]."' ";
	$regapr = mysql_query($sql_apr,$db->conexao) or die("Não foi possível a seleção dos dados" . $sql_apr);
	$aprovador = mysql_fetch_array($regapr);
	
	$y += 4;
	
	$pdf->SetXY(25,$y);
	$pdf->Cell(10,4,$revis["versao_documento"],1,0,'C',0);
	$pdf->Cell(70,4,$revis["alteracao"],1,0,'C',0);
	$pdf->Cell(20,4,mysql_php($revis["data_emissao"]),1,0,'C',0);
	$pdf->Cell(20,4,$executante["abreviacao"],1,0,'C',0);
	$pdf->Cell(20,4,$verificador["abreviacao"],1,0,'C',0);
	$pdf->Cell(20,4,$aprovador["abreviacao"],1,1,'C',0);
	
}


$sql_exe0 = "SELECT abreviacao FROM ".DATABASE.".Funcionarios ";
$sql_exe0 .= "WHERE id_funcionario = '".$revis0["id_executante"]."' ";
$regexe0 = mysql_query($sql_exe0,$db->conexao) or die("Não foi possível a seleção dos dados" . $sql_exe0);
$contexe = mysql_fetch_array($regexe0);
$executante0 = $contexe["abreviacao"];

$sql_ver0 = "SELECT abreviacao FROM ".DATABASE.".Funcionarios ";
$sql_ver0 .= "WHERE id_funcionario = '".$revis0["id_verificador"]."' ";
$regver0 = mysql_query($sql_ver0,$db->conexao) or die("Não foi possível a seleção dos dados" . $sql_ver);
$contver = mysql_fetch_array($regver0);
$verificador0 = $contver["abreviacao"];

$sql_apr0 = "SELECT abreviacao FROM ".DATABASE.".Funcionarios ";
$sql_apr0 .= "WHERE id_funcionario = '".$revis0["id_aprovador"]."' ";
$regapr0 = mysql_query($sql_apr0,$db->conexao) or die("Não foi possível a seleção dos dados" . $sql_apr);
$contapr = mysql_fetch_array($regapr0);
$aprovador0 = $contapr["abreviacao"];

$y += 4;

$pdf->SetXY(25,$y);

$pdf->Cell(10,4,$revis0["versao_documento"],1,0,'C',0);
$pdf->Cell(70,4,$revis0["alteracao"],1,0,'C',0);
$pdf->Cell(20,4,mysql_php($revis0["data_emissao"]),1,0,'C',0);
$pdf->Cell(20,4,$executante0,1,0,'C',0);
$pdf->Cell(20,4,$verificador0,1,0,'C',0);
$pdf->Cell(20,4,$aprovador0,1,0,'C',0);

$pdf->SetXY(25,$y+4);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(10,4,'REV.',1,0,'C',0);
$pdf->Cell(70,4,'ALTERAÇÃO',1,0,'C',0);
$pdf->Cell(20,4,'DATA',1,0,'C',0);
$pdf->Cell(20,4,'EXEC.',1,0,'C',0);
$pdf->Cell(20,4,'VERIF.',1,0,'C',0);
$pdf->Cell(20,4,'APROV.',1,0,'C',0);

/*
$pdf->AddPage('L');

$pdf->SetXY(10,48);


// TÍTULOS
$pdf->SetFont('Arial','B',8);
//IMPRIME AS BORDAS
$pdf->Cell(30,10,"",1,0,'C',0);
$pdf->Cell(30,10,"",1,0,'C',0);
$pdf->Cell(35,10,"",1,0,'C',0);
$pdf->Cell(35,10,"",1,0,'C',0);
$pdf->Cell(30,10,"",1,0,'C',0);
$pdf->Cell(65,10,"",1,0,'C',0);
$pdf->Cell(45,10,"",1,0,'C',0);

$pdf->SetXY(10,48);

//IMPRIME OS TEXTOS DOS CABEÇALHOS
$pdf->Cell(30,5,"IDENTIFICAÇÃO",0,0,'C',0);

$pdf->Cell(30,5,"FORMAÇÃO",0,0,'C',0);
		
$pdf->Cell(35,5,"DE",0,0,'C',0);

$pdf->Cell(35,5,"PARA",0,0,'C',0);

$pdf->Cell(30,5,"COMPR",1,0,'C',0);

$pdf->Cell(65,5,"TRECHO",0,0,'C',0);

$pdf->Cell(45,5,"OBSERVAÇÃO",0,1,'C',0);

//IMPRIME O SUBCABEÇALHO
$pdf->Cell(30,5,"CABO",0,0,'C',0);

$pdf->Cell(30,5,"",0,0,'C',0);
		
$pdf->Cell(35,5,"",0,0,'C',0);

$pdf->Cell(35,5,"",0,0,'C',0);

$pdf->Cell(15,5,"PROJ.",1,0,'C',0);
$pdf->Cell(15,5,"MON.",1,0,'C',0);

$pdf->Cell(65,5,"",0,0,'C',0);

$pdf->Cell(45,5,"",0,1,'C',0);
$pdf->SetFont('Arial','',8);

$pdf->Ln(2);
*/


$sql = "SELECT * FROM Projetos.area, Projetos.subsistema ";
$sql .= "WHERE subsistema.id_area = area.id_area ";
$sql .= "AND area.id_os = '" . $_SESSION["id_os"] . "' ";
//$sql .= "AND subsistema.id_subsistema = '" .$_POST["id_subsistema"] . "' ";
$sql .= "ORDER BY nr_subsistema ";
$regsub = mysql_query($sql,$db->conexao) or die("Não foi possível a seleção dos dados" . $sql);

$flag = 0;

//Rotina para criar um array com os tipos/finalidades de cabos existentes
$sql_cabos_finalidades = "SELECT cabos_finalidades.ds_finalidade FROM Projetos.cabos_finalidades ";
$sql_cabos_finalidades .= "ORDER BY cabos_finalidades.ds_finalidade ";

$cont_cabos_finalidades = mysql_query($sql_cabos_finalidades,$db->conexao);

$n_cabo = 0;

//Cria o array
while($reg_cabos_finalidades = mysql_fetch_array($cont_cabos_finalidades))
{
	$ds_cabos_finalidades[$n_cabo] = $reg_cabos_finalidades["ds_finalidade"];

	$n_cabo++;
}



/*
while ($subsistema = mysql_fetch_array($regsub))
{
	
		if($pdf->GetY()>180)
		{
			$pdf->AddPage();
			// TÍTULOS
			$pdf->SetXY(10,48);
			$pdf->SetFont('Arial','B',8);
			//IMPRIME AS BORDAS
			$pdf->Cell(30,10,"",1,0,'C',0);
			$pdf->Cell(30,10,"",1,0,'C',0);
			$pdf->Cell(35,10,"",1,0,'C',0);
			$pdf->Cell(35,10,"",1,0,'C',0);
			$pdf->Cell(30,10,"",1,0,'C',0);
			$pdf->Cell(65,10,"",1,0,'C',0);
			$pdf->Cell(45,10,"",1,0,'C',0);
			
			$pdf->SetXY(10,48);
			
			//IMPRIME OS TEXTOS DOS CABEÇALHOS
			$pdf->Cell(30,5,"IDENTIFICAÇÃO",0,0,'C',0);
			
			$pdf->Cell(30,5,"FORMAÇÃO",0,0,'C',0);
					
			$pdf->Cell(35,5,"DE",0,0,'C',0);
			
			$pdf->Cell(35,5,"PARA",0,0,'C',0);
			
			$pdf->Cell(30,5,"COMPR",1,0,'C',0);
			
			$pdf->Cell(65,5,"TRECHO",0,0,'C',0);
			
			$pdf->Cell(45,5,"OBSERVAÇÃO",0,1,'C',0);
			
			//IMPRIME O SUBCABEÇALHO
			$pdf->Cell(30,5,"CABO",0,0,'C',0);
			
			$pdf->Cell(30,5,"",0,0,'C',0);
					
			$pdf->Cell(35,5,"",0,0,'C',0);
			
			$pdf->Cell(35,5,"",0,0,'C',0);
			
			$pdf->Cell(15,5,"PROJ.",1,0,'C',0);
			$pdf->Cell(15,5,"MON.",1,0,'C',0);
			
			$pdf->Cell(65,5,"",0,0,'C',0);
			
			$pdf->Cell(45,5,"",0,1,'C',0);
			$pdf->SetFont('Arial','',8);
			
			$pdf->Ln(2);
		}
		
		if($subsistema["subsistema"]!=$subsis)
		{
			if($pdf->GetY()>180)
			{
				$pdf->AddPage();
				// TÍTULOS
				$pdf->SetXY(10,48);
				$pdf->SetFont('Arial','B',8);
				//IMPRIME AS BORDAS
				$pdf->Cell(30,10,"",1,0,'C',0);
				$pdf->Cell(30,10,"",1,0,'C',0);
				$pdf->Cell(35,10,"",1,0,'C',0);
				$pdf->Cell(35,10,"",1,0,'C',0);
				$pdf->Cell(30,10,"",1,0,'C',0);
				$pdf->Cell(65,10,"",1,0,'C',0);
				$pdf->Cell(45,10,"",1,0,'C',0);
				
				$pdf->SetXY(10,48);
				
				//IMPRIME OS TEXTOS DOS CABEÇALHOS
				$pdf->Cell(30,5,"IDENTIFICAÇÃO",0,0,'C',0);
				
				$pdf->Cell(30,5,"FORMAÇÃO",0,0,'C',0);
						
				$pdf->Cell(35,5,"DE",0,0,'C',0);
				
				$pdf->Cell(35,5,"PARA",0,0,'C',0);
				
				$pdf->Cell(30,5,"COMPR",1,0,'C',0);
				
				$pdf->Cell(65,5,"TRECHO",0,0,'C',0);
				
				$pdf->Cell(45,5,"OBSERVAÇÃO",0,1,'C',0);
				
				//IMPRIME O SUBCABEÇALHO
				$pdf->Cell(30,5,"CABO",0,0,'C',0);
				
				$pdf->Cell(30,5,"",0,0,'C',0);
						
				$pdf->Cell(35,5,"",0,0,'C',0);
				
				$pdf->Cell(35,5,"",0,0,'C',0);
				
				$pdf->Cell(15,5,"PROJ.",1,0,'C',0);
				$pdf->Cell(15,5,"MON.",1,0,'C',0);
				
				$pdf->Cell(65,5,"",0,0,'C',0);
				
				$pdf->Cell(45,5,"",0,1,'C',0);
				$pdf->SetFont('Arial','',8);
				
				$pdf->Ln(2);
			}
			$pdf->Cell(285,10, $subsistema["subsistema"] ,0,1,'L',0);
		}
		
		$subsis = $subsistema["subsistema"];
*/
		
		
//AQUIIIII
		

		$contagem_fin = 0;
						
		//Loop nos tipos/finalidade
		while($ds_cabos_finalidades[$contagem_fin])
		{
		/*
		
			if($pdf->GetY()>180)
			{
				$pdf->AddPage();
				// TÍTULOS
				$pdf->SetXY(10,48);
				$pdf->SetFont('Arial','B',8);
				//IMPRIME AS BORDAS
				$pdf->Cell(30,10,"",1,0,'C',0);
				$pdf->Cell(30,10,"",1,0,'C',0);
				$pdf->Cell(35,10,"",1,0,'C',0);
				$pdf->Cell(35,10,"",1,0,'C',0);
				$pdf->Cell(30,10,"",1,0,'C',0);
				$pdf->Cell(65,10,"",1,0,'C',0);
				$pdf->Cell(45,10,"",1,0,'C',0);
				
				$pdf->SetXY(10,48);
				
				//IMPRIME OS TEXTOS DOS CABEÇALHOS
				$pdf->Cell(30,5,"IDENTIFICAÇÃO",0,0,'C',0);
				
				$pdf->Cell(30,5,"FORMAÇÃO",0,0,'C',0);
						
				$pdf->Cell(35,5,"DE",0,0,'C',0);
				
				$pdf->Cell(35,5,"PARA",0,0,'C',0);
				
				$pdf->Cell(30,5,"COMPR",1,0,'C',0);
				
				$pdf->Cell(65,5,"TRECHO",0,0,'C',0);
				
				$pdf->Cell(45,5,"OBSERVAÇÃO",0,1,'C',0);
				
				//IMPRIME O SUBCABEÇALHO
				$pdf->Cell(30,5,"CABO",0,0,'C',0);
				
				$pdf->Cell(30,5,"",0,0,'C',0);
						
				$pdf->Cell(35,5,"",0,0,'C',0);
				
				$pdf->Cell(35,5,"",0,0,'C',0);
				
				$pdf->Cell(15,5,"PROJ.",1,0,'C',0);
				$pdf->Cell(15,5,"MON.",1,0,'C',0);
				
				$pdf->Cell(65,5,"",0,0,'C',0);
				
				$pdf->Cell(45,5,"",0,1,'C',0);
				$pdf->SetFont('Arial','',8);
				
				$pdf->Ln(2);
			}
			*/
/*			
				if($pdf->GetY()>180)
				{
					$pdf->AddPage();
				}
				//$pdf->Cell(285,10, $subsistema["subsistema"] ,0,1,'L',0);
*/			

		
			// Mostra os funcionários
			$sql = "SELECT * FROM Projetos.cabos, Projetos.cabos_tipos, Projetos.cabos_finalidades, Projetos.subsistema, Projetos.area ";
			$sql .= "WHERE cabos.id_subsistema = subsistema.id_subsistema ";
			$sql .= "AND subsistema.id_area = area.id_area ";
			$sql .= "AND area.id_os = '" . $_SESSION["id_os"] . "' ";
			$sql .= "AND cabos.id_cabo_tipo = cabos_tipos.id_cabo_tipo ";
			$sql .= "AND cabos.id_origem_local = '" . $_POST["id_local"] . "' ";
			$sql .= "AND cabos_tipos.id_cabo_finalidade = cabos_finalidades.id_cabo_finalidade ";
			$sql .= "AND cabos_finalidades.ds_finalidade = '" . $ds_cabos_finalidades[$contagem_fin]. "' ";
			$sql .= $filtro;
			$sql .= "ORDER BY cabos.identificacao_cabo ";
			
			$registro = mysql_query($sql,$db->conexao) or die("Não foi possível fazer a seleção." . $sql);

			if(mysql_num_rows($registro)>0)
			{
//				if($contagem_fin > 0)
//				{

					$pdf->AddPage();
					
				//Espaçamento
				//$pdf->Cell(20,5, '',0,1,'L',0);

					// TÍTULOS
					$pdf->SetXY(10,48);
					$pdf->SetFont('Arial','B',8);
					//IMPRIME AS BORDAS
					$pdf->Cell(30,10,"",1,0,'C',0);
					$pdf->Cell(65,10,"",1,0,'C',0);
					$pdf->Cell(35,10,"",1,0,'C',0);
					$pdf->Cell(35,10,"",1,0,'C',0);
					$pdf->Cell(30,10,"",1,0,'C',0);
					$pdf->Cell(30,10,"",1,0,'C',0);
					$pdf->Cell(45,10,"",1,0,'C',0);
					
					$pdf->SetXY(10,48);
					
					//IMPRIME OS TEXTOS DOS CABEÇALHOS
					$pdf->Cell(30,5,"IDENTIFICAÇÃO",0,0,'C',0);
					
					$pdf->Cell(65,5,"FORMAÇÃO",0,0,'C',0);
							
					$pdf->Cell(35,5,"DE",0,0,'C',0);
					
					$pdf->Cell(35,5,"PARA",0,0,'C',0);
					
					$pdf->Cell(30,5,"COMPR",1,0,'C',0);
					 
					$pdf->Cell(30,5,"TRECHO",0,0,'C',0);
					
					$pdf->Cell(45,5,"OBSERVAÇÃO",0,1,'C',0);
					
					//IMPRIME O SUBCABEÇALHO
					$pdf->Cell(30,5,"CABO",0,0,'C',0);
					
					$pdf->Cell(65,5,"",0,0,'C',0);
							
					$pdf->Cell(35,5,"",0,0,'C',0);
					
					$pdf->Cell(35,5,"",0,0,'C',0);
					
					$pdf->Cell(15,5,"PROJ.",1,0,'C',0);
					$pdf->Cell(15,5,"MON.",1,0,'C',0);
					
					$pdf->Cell(30,5,"",0,0,'C',0);
					
					$pdf->Cell(45,5,"",0,1,'C',0);
					$pdf->SetFont('Arial','',8);
					
					$pdf->Ln(2);
//				}

				
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(80,5, $ds_cabos_finalidades[$contagem_fin] ,0,1,'L',0);
				$pdf->SetFont('Arial','',8);
			}
			
			
			while ($cabos = mysql_fetch_array($registro))
			{

				if($pdf->GetY()>180)
				{
					$pdf->AddPage();
					// TÍTULOS
					$pdf->SetXY(10,48);
					$pdf->SetFont('Arial','B',8);
					//IMPRIME AS BORDAS
					$pdf->Cell(30,10,"",1,0,'C',0);
					$pdf->Cell(65,10,"",1,0,'C',0);
					$pdf->Cell(35,10,"",1,0,'C',0);
					$pdf->Cell(35,10,"",1,0,'C',0);
					$pdf->Cell(30,10,"",1,0,'C',0);
					$pdf->Cell(30,10,"",1,0,'C',0);
					$pdf->Cell(45,10,"",1,0,'C',0);
					
					$pdf->SetXY(10,48);
					
					//IMPRIME OS TEXTOS DOS CABEÇALHOS
					$pdf->Cell(30,5,"IDENTIFICAÇÃO",0,0,'C',0);
					
					$pdf->Cell(65,5,"FORMAÇÃO",0,0,'C',0);
							
					$pdf->Cell(35,5,"DE",0,0,'C',0);
					
					$pdf->Cell(35,5,"PARA",0,0,'C',0);
					
					$pdf->Cell(30,5,"COMPR",1,0,'C',0);
					
					$pdf->Cell(65,5,"TRECHO",0,0,'C',0);
					
					$pdf->Cell(45,5,"OBSERVAÇÃO",0,1,'C',0);
					
					//IMPRIME O SUBCABEÇALHO
					$pdf->Cell(30,5,"CABO",0,0,'C',0);
					
					$pdf->Cell(30,5,"",0,0,'C',0);
							
					$pdf->Cell(35,5,"",0,0,'C',0);
					
					$pdf->Cell(35,5,"",0,0,'C',0);
					
					$pdf->Cell(15,5,"PROJ.",1,0,'C',0);
					$pdf->Cell(15,5,"MON.",1,0,'C',0);
					
					$pdf->Cell(30,5,"",0,0,'C',0);
					
					$pdf->Cell(45,5,"",0,1,'C',0);
					$pdf->SetFont('Arial','',8);
					
					$pdf->Ln(2);
				}
	
				$sql0 = "SELECT * FROM Projetos.processo, Projetos.dispositivos, Projetos.funcao, Projetos.componentes, Projetos.malhas, Projetos.subsistema, Projetos.area ";
				$sql0 .= "WHERE componentes.id_malha = malhas.id_malha ";
				$sql0 .= "AND componentes.id_funcao = funcao.id_funcao ";
				$sql0 .= "AND componentes.id_dispositivo = dispositivos.id_dispositivo ";
				$sql0 .= "AND malhas.id_processo = processo.id_processo ";
				$sql0 .= "AND malhas.id_subsistema = subsistema.id_subsistema ";
				$sql0 .= "AND subsistema.id_area = area.id_area ";
				$sql0 .= "AND componentes.id_componente = '".$cabos["id_origem_comp"]."' ";
				
				$regis0 = mysql_query($sql0,$db->conexao) or die("Não foi possível fazer a seleção." . $sql1);
				$origcomp = mysql_fetch_array($regis0);
				
				if($origcomp["processo"]!='D')
				{
					$nrmalha = '';
					$tam = strlen($origcomp["nr_malha"]);
					for($y=0;$y<3-$tam;$y++)
					{
						$nrmalha = $nrmalha . '0';
					}
					$nrmalha = $nrmalha . $origcomp["nr_malha"];
				}
				else
				{
					$nrmalha = $origcomp["nr_malha"];
				}
				
				if($origcomp["omit_proc"])
				{
					$processo = '';
				}
				else
				{
					$processo = $origcomp["processo"];
				}
				
				if($origcomp["nr_malha_seq"]!='')
				{
					$nrseq = '.'.$origcomp["nr_malha_seq"];
				}
				else
				{
					$nrseq = ' ';
				}
				
				if($origcomp["funcao"]!="")
				{
					$modificador =" ". $origcomp["funcao"];
				}
				else
				{
					if($origcomp["comp_modif"])
					{
						$modificador = ".".$origcomp["comp_modif"];
					}
					else
					{
						$modificador = " ";
					}
				}
	
				$sql1 = "SELECT * FROM ".DATABASE.".setores, Projetos.area, Projetos.locais  ";
				$sql1 .= "LEFT JOIN Projetos.equipamentos ON (Projetos.locais.id_equipamento = Projetos.equipamentos.id_equipamentos) ";
				$sql1 .= "WHERE Projetos.locais.id_disciplina = ".DATABASE.".setores.id_setor ";
				$sql1 .= "AND ".DATABASE.".setores.setor = 'ELÉTRICA' ";
				$sql1 .= "AND locais.id_area = area.id_area ";
				$sql1 .= "AND locais.id_local = '".$cabos["id_origem_local"]."' ";
				$sql1 .= "ORDER BY cd_local, nr_sequencia, ds_equipamento ";
				
				$regis1 = mysql_query($sql1,$db->conexao) or die($sql);
				
				$origlocal = mysql_fetch_array($regis1);
	
				if($nrmalha==0)
				{
					$nrmalha ="";
				}
										
				$origem = $processo . $origcomp["dispositivo"] . "  " .$nrmalha.$nrseq.$modificador." ".$origlocal["nr_area"] . " " .$origlocal["cd_local"] . " " . $origlocal["nr_sequencia"];
	
				$sql2 = "SELECT * FROM Projetos.processo, Projetos.dispositivos, Projetos.funcao, Projetos.componentes, Projetos.malhas, Projetos.subsistema, Projetos.area ";
				$sql2 .= "WHERE componentes.id_malha = malhas.id_malha ";
				$sql2 .= "AND componentes.id_funcao = funcao.id_funcao ";
				$sql2 .= "AND componentes.id_dispositivo = dispositivos.id_dispositivo ";
				$sql2 .= "AND malhas.id_processo = processo.id_processo ";
				$sql2 .= "AND malhas.id_subsistema = subsistema.id_subsistema ";
				$sql2 .= "AND subsistema.id_area = area.id_area ";
				$sql2 .= "AND componentes.id_componente = '".$cabos["id_destino_comp"]."' ";
				
				$regis2 = mysql_query($sql2,$db->conexao) or die("Não foi possível fazer a seleção." . $sql1);
				$destcomp = mysql_fetch_array($regis2);
				
				if($destcomp["processo"]!='D')
				{
					$nrmalha = '';
					$tam = strlen($destcomp["nr_malha"]);
					for($y=0;$y<3-$tam;$y++)
					{
						$nrmalha = $nrmalha . '0';
					}
					$nrmalha = $nrmalha . $destcomp["nr_malha"];
				}
				else
				{
					$nrmalha = $destcomp["nr_malha"];
				}
				
				if($destcomp["omit_proc"])
				{
					$processo = '';
				}
				else
				{
					$processo = $destcomp["processo"];
				}
				
				if($destcomp["nr_malha_seq"]!='')
				{
					$nrseq = '.'.$destcomp["nr_malha_seq"];
				}
				else
				{
					$nrseq = ' ';
				}
				
				if($destcomp["funcao"]!="")
				{
					$modificador =" ". $destcomp["funcao"];
				}
				else
				{
					if($destcomp["comp_modif"])
					{
						$modificador = ".".$destcomp["comp_modif"];
					}
					else
					{
						$modificador = " ";
					}
				}
	
				$sql3 = "SELECT * FROM ".DATABASE.".setores, Projetos.area, Projetos.locais  ";
				$sql3 .= "LEFT JOIN Projetos.equipamentos ON (Projetos.locais.id_equipamento = Projetos.equipamentos.id_equipamentos) ";
				$sql3 .= "WHERE Projetos.locais.id_disciplina = ".DATABASE.".setores.id_setor ";
				$sql3 .= "AND ".DATABASE.".setores.setor = 'ELÉTRICA' ";
				$sql3 .= "AND locais.id_area = area.id_area ";
				$sql3 .= "AND locais.id_local = '".$cabos["id_destino_local"]."' ";
				$sql3 .= "ORDER BY cd_local, nr_sequencia, ds_equipamento ";
				
				$regis3 = mysql_query($sql3,$db->conexao) or die($sql);
				
				$destlocal = mysql_fetch_array($regis3);
	
				if($nrmalha==0)
				{
					$nrmalha ="";
				}
				
				$destino = $processo . $destcomp["dispositivo"] . "  " .$nrmalha.$nrseq.$modificador." ".$destlocal["nr_area"] . " " .$destlocal["cd_local"] . " " . $destlocal["nr_sequencia"];
	
				$pdf->Cell(30,5,$cabos["identificacao_cabo"],1,0,'C',0);
				$pdf->Cell(65,5,$cabos["ds_formacao"],1,0,'C',0);
				$pdf->Cell(35,5,$origem,1,0,'C',0);
				$pdf->Cell(35,5,$destino,1,0,'C',0);
				$pdf->Cell(15,5,$cabos["nr_comprimento"],1,0,'C',0);
				$pdf->Cell(15,5,'0',1,0,'C',0);
				$pdf->Cell(30,5,$cabos["ds_trecho"],1,0,'C',0);
				$pdf->Cell(45,5,$cabos["ds_observacao"],1,1,'C',0);
			
			}
			$contagem_fin++;	
		}

		
		$pdf->Ln(3);
		// Libera a memória
		
$db->fecha_db();
		
$pdf->Output();

if($_POST["emissao"]=='1')

{

	$pdf->Output('../projetos/pdftemp/' . $_POST["numeros_interno"] .'_'. $_POST["numero_cliente"] .'_'.$_POST["versao_documento"] . '.pdf',F);
	
	
	copy('/'.$pasta[1].'/'.$pasta[2].'/'.$pasta[3].'/'.$pasta[4].'/pdftemp/'. $_POST["numeros_interno"] .'_'.$_POST["numero_cliente"] .'_'.$_POST["versao_documento"] . '.pdf',$caminho.$_POST["numeros_interno"] .'_'.$_POST["numero_cliente"] .'_'.$_POST["versao_documento"].'.pdf');

}


?>