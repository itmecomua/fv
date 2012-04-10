<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="20" height="31"><img alt=" " src="{$fvConfig->get('path.images')}corner-left-top.gif" width="20" height="31" id="corner_left_{$moduleID}"></td>
		<td width="50%" class="heading selected"  id="left_{$moduleID}">Новости</td>
		<td width="25" height="31"><img alt=" " src="{$fvConfig->get('path.images')}corner-middle-unsel.gif" width="20" height="31" id="middle_left_{$moduleID}"></td>
		<td width="20%" class="heading unselected" id="middle_{$moduleID}"><A href="javascript: void(0);" onclick="selectTab('middle', '{$moduleID}')">Еще</A></td>	
		<td width="20" height="31"><img alt=" " src="{$fvConfig->get('path.images')}corner-devide-unsel.gif" width="20" height="31" id="middle_right_{$moduleID}"></td>
		<td width="25%" class="heading unselected"><A href="#">Все</A></td>	
		<td width="20" height="31"><img alt=" " src="{$fvConfig->get('path.images')}corner-right-top.gif" width="20" height="31"></td>
	</tr>
	
	<TR>
		<TD class="left-border">&nbsp;</TD>
		<TD colspan="5">
			<DIV id="content{$moduleID}_left" style="display: block;">
				Сокращенный вид<BR /><BR /><BR />
			</DIV><DIV id="content{$moduleID}_middle" style="display: none;">
				Более расширенный вид<BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR />
			</DIV></TD>
		<td class="right-border">&nbsp;</td>
	</TR>

	<tr>
		<td width="20" height="31"><img alt=" " src="{$fvConfig->get('path.images')}corner-left-bottom.gif" width="20" height="31"></td>
		<TD colspan="5" class="bottom-line">&nbsp;</TD>
		<td width="20" height="31"><img alt=" " src="{$fvConfig->get('path.images')}corner-right-bottom.gif" width="20" height="31"></td>
	</tr>

</TABLE>