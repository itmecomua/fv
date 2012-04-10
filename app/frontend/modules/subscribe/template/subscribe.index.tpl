<div class="border_simple">
<div class="tabs_a_wrap">
    <ul class="h_taw3">
        <li>
            <a class="countrytabs first " href="/agents" >
                <span class="countrytabs_txt">Агентам </span>
                <span class="countrytabs_selected">выбраный пункт</span>
            </a>
        </li>
        <li>
            <a class="countrytabs activeTabs" href="/subscribe" >
                <span class="countrytabs_txt">Подписка на рассылку</span>
                <span class="countrytabs_selected">выбраный пункт</span>
            </a>
        </li>
        <li>
            <a class="countrytabs" href="/agency_contract" >
                <span class="countrytabs_txt">Агентский договор</span>
                <span class="countrytabs_selected">выбраный пункт</span>
            </a>
        </li>
        <li>
            <a class="countrytabs" href="/infotour" >
                <span class="countrytabs_txt">Рекламные туры</span>
                <span class="countrytabs_selected">выбраный пункт</span>
            </a>
        </li>
        <li class="h_taw4">
            <a class="countrytabs last" href="/stock" >
                <span class="countrytabs_txt">Акции</span>
                <span class="countrytabs_selected">выбраный пункт</span>
            </a>
        </li>
    </ul>
</div>

    
<div class="block_a_wrap">
	<div class="block_a_lft">
		<div class="block_a_rt">
			<div class="block_a_md">
				<h2>Подписаться на рассылку</h2>
			</div>
		</div>
	</div>
	<div class="block_a_bd">
        <div class="h_pad1">         
            <form class="form_a" action="/subscribe/subscribe" method="post" id="frmSubscribe">
                    <fieldset>
                        <legend>Имя*</legend>
                        <label><input type="text" name="update[name]" id="subscr_name"/></labeld>
                    </fieldset>
                    <fieldset>
                        <legend>E-mail*</legend>
                        <label><input type="text" name="update[email]" id="subscr_email"/></label>
                    </fieldset>
                    <fieldset>
                        <legend>Телефон*</legend>
                        <label><input type="text" name="update[phone]" id="subscr_phone"/></label>
                    </fieldset>
                    <fieldset>
                        <legend>Страна*</legend>
                        <label><input type="text" name="update[country]" id="subscr_country"/></label>
                    </fieldset>
                    <fieldset>
                        <legend>Компания</legend>
                        <label><input type="text" name="update[company]" id="subscr_company"/></label>
                    </fieldset>
                    <fieldset>
                        <legend>Должность</legend>
                        <label><input type="text" name="update[post]" id="subscr_post"/></label>
                    </fieldset>
                    <fieldset>
                        <legend>&nbsp;</legend>
                        <label>* - поля обязательные для заполнения</label>
                    </fieldset>
                    <fieldset>
                        <legend>&nbsp;</legend>
                        <label>
                            <div class="but1">
                                <input type="submit" value="Подписаться" onclick="javascript: handlerForm.init('#frmSubscribe','#subscribemsg').ajax(); return false;" />
                            </div>    
                        </label>
                    </fieldset>      
                    <div id="subscribemsg"></div>
            </form>
        </div>
	</div>
</div>
<br />
<div class="block_a_wrap">
	<div class="block_a_lft">
		<div class="block_a_rt">
			<div class="block_a_md">
				<h2>Отписаться от рассылки</h2>
			</div>
		</div>
	</div>
	<div class="block_a_bd">
        <div class="h_pad1">
            <form class="form_a" action="/subscribe/unsubscribe" method="post" id="frmUnsubscribe">
                    <fieldset>
                        <legend>Имя*</legend>
                        <label><input type="text" name="update[name]" id="unsubscr_name"/></label>
                    </fieldset>
                    <fieldset>
                        <legend>E-mail*</legend>
                        <label><input type="text" name="update[email]" id="unsubscr_email"/></label>
                    </fieldset>   
                    <fieldset>
                        <legend>&nbsp;</legend>
                        <label>* - поля обязательные для заполнения</label>
                    </fieldset>
                    <fieldset>
                        <legend>&nbsp;</legend>
                        <label>
                            <div class="but1">    
                                <input type="submit" value="Отписаться" onclick="javascript: handlerForm.init('#frmUnsubscribe','#unsubscribemsg').ajax(); return false;"/><br />
                            </div>
                        </label>
                    </fieldset> 
                    <div id="unsubscribemsg"></div>
            </form>
        </div>
	</div>
</div>    
    

</div>