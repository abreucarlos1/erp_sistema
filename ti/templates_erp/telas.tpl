<smarty>include file="`$smarty.const.TEMPLATES_DIR`html_conf.tpl"</smarty>
<smarty>include file="`$smarty.const.TEMPLATES_DIR`cabecalho.tpl"</smarty>
<div id="frame" style="width: 100%; height: 700px;">
<form name="frm_tela" id="frm_tela" action="<smarty>$smarty.server.PHP_SELF</smarty>" method="POST">
	<table width="100%" border="0">               
        <tr>
        	<td width="116" valign="top" class="espacamento">
        		<table width="100%" border="0">
        			<tr>
        				<td valign="middle">
        					<input name="btninserir" type="button" class="class_botao" id="btninserir" onclick="xajax_insere(xajax.getFormValues('frm_tela'));" value="Inserir" /></td>
					</tr>
        			<tr>
        				<td valign="middle"><input name="btnvoltar" id="btnvoltar" type="button" class="class_botao" value="Voltar" onclick="history.back();" /></td>
					</tr>
       			</table>
			</td>
        	<td colspan="2" valign="top" class="espacamento">
		  <table border="0" width="100%">
				<tr>
					<td width="21%"><label for="tela" class="labels">Tela</label><br />
						<input name="tela" type="text" class="caixa" id="tela" size="50" placeholder="Tela" />
						<input name="id_tela" type="hidden" id="tela" value="" />						</td>
					</tr>
			</table>
  			<table border="0" width="100%">			  
			  <tr>
				<td><label for="busca" class="labels">Pesquisar</label><br />
					<input name="busca" type="text" class="caixa" id="busca" placeholder="Busca" onKeyUp="iniciaBusca.verifica(this);" size="50"></td>
				</tr>
			</table>		  </td>
        </tr>
      </table>
	  <div id="telas" style="width:100%;"> </div>
</form>
</div>
<smarty>include file="`$smarty.const.TEMPLATES_DIR`footer_root.tpl"</smarty>