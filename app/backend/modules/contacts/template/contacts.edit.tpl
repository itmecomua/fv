<h1>{$fvConfig->getModuleName()}</h1>
<div class="h_clear">
    <h2>{if $ex->isNew()}Добавление{else}Редактирование{/if}</h2>
</div>
<div class="h_clear">
    {* Кнопка вернутся к списку *}
    {$fvModule->getReturn()}
    {* /Кнопка вернутся к списку *}
</div>
<div class="form">
<form method="post" action="/backend/{$module}/save/">
    <div class="form_stage">
        <label>Заголовок для телефонов</label>
        <input  name="m[phonetitle]" 
                id="phonetitle" 
                value="{$ex->getPhoneTitle()}" 
                type="text" 
                />
    </div>
	<div class="form_stage">
        <label>Телефоны:</label>
        <input 	name="m[phone]" 
				id="phone" 
				value="{$ex->getPhone()}" 
				type="text" 
                />
    </div>
    <div class="form_stage">
        <label>Заголовок для адреса:</label>
        <input 	name="m[addresstitle]" 
				id="addresstitle" 
				value="{$ex->getAddressTitle()}" 
				type="text" 
		/>
    </div>
    <div class="form_stage">
        <label>Адрес:</label>
        <textarea   name="m[address]" 
                    id="address" 
                    >{$ex->getAddress()}</textarea>
    </div>

    <div class="form_stage">
        <label>Показывать ?</label>
	    Да
        <input 	name="m[is_show]"   
                value="1" 	   
                type="radio"  
                {if $ex->isShow()}
                checked="checked" 
                {/if} 
                />
        Нет
	    <input  name="m[is_show]"   
                value="0" type="radio"	
                {if !$ex->isShow()}
                checked="checked" 
                {/if}  
                />
    </div>
    <div class="buttonpanel">
	    <input  name="save" 			
                value="Сохранить" 				
                type="submit"	
                />
	    
        <input	name="id"
                id="id" 		
          		value="{$ex->getPk()}" 			
                type="hidden"	
                />
	    <input 	name="redirect"	
                id="redirect" 	 		
                value="" 						
                type="hidden"	
                />
    </div>
</form>
</div>
<br>
<div id='buffer' style='display:none;'></div>