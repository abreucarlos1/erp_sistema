<sma<smarty>include file="`$smarty.const.TEMPLATES_DIR`html_conf.tpl"</smarty>
<smarty>include file="`$smarty.const.TEMPLATES_DIR`cabecalho.tpl"</smarty>
<div id="frame" style="width: 100%; height: 700px">
<form name="frm_rel" id="frm_rel" action="relatorios/rel_controle_funcxosxconthoras.php" method="POST" style="margin:0px; padding:0px;">
	<table width="100%" border="0">        
        <tr>
          <td width="116" rowspan="2" valign="top" class="espacamento">
		  <table width="100%" border="0">
				<tr>
					<td valign="middle"><input name="btninserir" id="btninserir" type="submit" class="class_botao" value="Gerar relatório"/></td>
				</tr>
				<tr>
					<td valign="middle"><input name="btnvoltar" id="btnvoltar" type="button" class="class_botao" value="Voltar" onclick="history.back();" /></td>
				</tr>
			</table></td>
        </tr>        
        <tr>
          <td colspan="2" valign="top"  class="espacamento">
		  <table width="100%" border="0">
				<tr>
					<td colspan="2"><label class="labels">PERÍODO</label></td>
					</tr>
				<tr>
					<td width="42%">
						<table width="100%" border="0">
							<tr>
								<td width="24%"><label for="dataini" class="labels">Data inicial</label><br />
                                <input name="dataini" type="text" class="caixa" id="dataini" size="10" placeholder="Data ini." onkeypress="transformaData(this, event);" onkeyup="return autoTab(this,'datafim', 10);" />
                                </td>
							</tr>
							<tr>
								<td align="left"><label class="labels">Data final</label><br />
                                <input name="datafim" type="text" class="caixa" id="datafim" size="10" placeholder="Data fin." onkeypress="transformaData(this, event);" onkeyup="return autoTab(this,'escolhaos', 10);"  />
                                </td>
							</tr>
						</table>
						</td>
				</tr>
				<tr>
					<td colspan="2"><label for="escolhaos" class="labels">COORDENADOR</label><br />
                    	<select name="escolhaos" class="caixa" id="escolhaos" onkeypress="return keySort(this);">
						<smarty>html_options values=$option_coordenador_values output=$option_coordenador_output</smarty>
						</select>
                    </td>
			   </tr>
			</table></td>
        </tr>
      </table>
</form>
</div>
<smarty>include file="`$smarty.const.TEMPLATES_DIR`footer_root.tpl"</smarty>