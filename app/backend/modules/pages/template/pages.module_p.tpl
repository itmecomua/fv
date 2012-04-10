<fieldset>
    <legend>Параметры</legend>
    {foreach item=Param from=$displayParams}
        <div style="clear: both;"><label for="{$Param.name}">{$Param.label}</label><input type="text" name="parameters[{$Param.name}]" id="{$Param.name}" value="{$Param.value}" style="width: 150px;"></div>    
    {/foreach}
</fieldset>