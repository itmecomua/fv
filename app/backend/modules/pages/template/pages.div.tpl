<form id="paramsForm">
<div class="form">
    <label for="name" style="width:90px;">название</label>
        <input type="text" name="name" is="name" style="width: 200px;" value="{$name}"><br/>
        
    <label for="width" style="width:90px;">ширина</label>
        <input type="text" name="width" id="width" style="width: 200px;" value="{$width}"><br/>
        
   <label for="padding" style="width:90px;">отступы внеш</label>
        <input type="text" name="padding" id="padding" style="width: 200px;" value="{$padding}"><br/>
        
   <label for="margin" style="width:90px;">отступы внутр</label>
        <input type="text" name="margin" id="margin" style="width: 200px;" value="{$margin}"><br/>
        
   <label for="floating" style="width:90px;">обтекание</label>
   <div style="width: 270px;">
        <input type="radio" name="floating" value="left" {if $floating=='left'}checked="checked"{/if}><span style="font-size: 10px;">Слева</span>
        <input type="radio" name="floating" value="right" {if $floating=='right'}checked="checked"{/if}><span style="font-size: 10px;">Справа</span>
        <input type="radio" name="floating" value="auto" {if $floating=='left' || $floating==''}checked="checked"{/if}><span style="font-size: 10px;">Авто</span>
   </div>
   
   <fieldset id="attrs">
    <legend>
        Атрибуты 
        <a href="javascript:void(0);" id="addAttr">
            <img src="/backend/img/add.png" border="0" width="16" height="16" alt="add.png (847 bytes)">
        </a>
    </legend>
    
    {foreach from=$attrs item=ex key=key}
        <div style="width: 100%;">
             <input type='text' style="width: 44%;" name="attr[{$key}][name]" value="{$ex.name}">
             <input type='text' style="width: 44%;" name="attr[{$key}][value]" value="{$ex.value}">
        </div>
    {/foreach}
    
   </fieldset>   
    
</div>
<input type="hidden" name="_nodeName" id="_nodeName" value="div">
<input type="hidden" name="_nodeId" id="__nodeId" value="{$_nodeId}">
<input type="hidden" name="_xmlContent" id="__xmlContent" value="{$_xmlContent}">
<input type="hidden" name="_add" id="_add" value="{$_add}">
</form>

<script type="text/javascript" language="javascript">
<!--
{literal}
    jQuery( "#addAttr" ).click(function(){
        var id = parseInt( Math.random()*100000 );
        var iName = 
        jQuery( '<input>' ).attr({
                                type: 'text',
                                name: 'attr['+id+'][name]'
                                }).css({
                                        width: '44%'
                                        });
        var iValue =                                 
        jQuery( '<input>' ).attr({
                                type: 'text',
                                name: 'attr['+id+'][value]'
                                }).css({
                                        width: '44%'
                                        });                                        
                                        
        var container = 
        jQuery( "<div></div>" ).css({
                                   width: '100%',
                                   dislay: "none"
                                    });
                                   
        var wrapper = jQuery( "#attrs" );
        container.append( iName ).append( iValue ).appendTo( wrapper ).fadeIn();
    })
{/literal}
-->
</script>