<form id="paramsForm">
<div class="form">
    <p>размеры столбцов задаются через <SPAN class="important quote">,</SPAN>.
    Если не указана единица измерения пологается что ширина в пикселах.
    Для задания динамической ширины необходимо использовать <SPAN class="important quote">*</SPAN></p>
    <label for="name">название</label><input type="text" name="name" id="name" value="{$nodeName}" style="width: 200px;"><br />
    <label for="size">размеры столбцов</label><input type="text" name="size" id="size" value="{$nodeSize}" style="width: 200px;">
    <label for="spacer">промежуток</label><input type="text" name="spacer" id="spacer" value="{$nodeSpacer}" style="width: 200px;">
</div>
<input type="hidden" name="_nodeName" id="_nodeName" value="horisontal_layoult">
<input type="hidden" name="_nodeId" id="_nodeId" value="{$_nodeId}">
<input type="hidden" name="_xmlContent" id="_xmlContent" value="{$_xmlContent}">
<input type="hidden" name="_add" id="_add" value="{$_add}">
</form>