<div class="block_a_wrap">
    <div class="block_a_lft">
        <div class="block_a_rt">
            <div class="block_a_md">
                <h2>Заказ тура</h2>
            </div>
        </div>
    </div>
    <div class="block_a_bd">
        <div class="h_pad1">

            <form class="form_a" action="/ordertour/save" method="post" id="frmordertour">
                    <fieldset>
                        <legend>Тур</legend>
                        <label>
                        <input  type="text" 
                                value="{$tour->getName()}" disabled="disabled"
                        />
                        <input  type="hidden" name="update[tour_name]" id="ordertour_tour_name"
                                value="{$tour->getName()}" 
                        />
                        
                        </label>
                    </fieldset>            
                    <fieldset>
                        <legend>Даты:</legend>
                            {foreach from=$tour->getDates() item=key name=date_time}
                            <input  
                                    type="radio" 
                                    name="update[date_tour]"
                                    value="{$key->getDateStart('Y.m.d')}"
                                    {if $smarty.foreach.date_time.first}
                                    checked="checked"
                                    {/if}
                            />
                            <span class="int_inf">{$key->getDateStart()}</span>
                            {/foreach}
                    </fieldset>
                    <fieldset>
                        <legend>Количество человек:</legend>
                        <label><input type="text" name="update[cnt_adult]" id="ordertour_cnt_adult"/></label>
                    </fieldset>
                    <fieldset>
                        <legend>ФИО*</legend>
                        <label><input type="text" name="update[name]" id="ordertour_name"/></label>
                    </fieldset>
                    <fieldset>
                        <legend>E-mail*</legend>
                        <label><input type="text" name="update[email]" id="ordertour_email"/></label>
                    </fieldset>
                    <fieldset>
                        <legend>Телефон:*</legend>
                        <label><input type="text" name="update[phone]" id="ordertour_phone"/></label>
                    </fieldset>
                    <fieldset>
                        <legend>Пожелания:</legend>
                        <label><textarea name="update[wish_text]" id="ordertour_wish_text"></textarea></label>
                    </fieldset>
                    <fieldset>
                        <legend>Введите символы*: </legend>
                        <span class="_captcha">{$captcha->render()}</span>
                        <span ><input type="text" class="fieldset-label-text" name="captcha[inputfield]"/></span>
                    </fieldset>
                    <fieldset>
                        <legend>&nbsp;</legend>
                        <label>* - поля обязательные для заполнения</label>
                    </fieldset>
                    <fieldset>
                        <legend>&nbsp;</legend>
                        <label>
                            <div class="but1">
                                <input type="submit" value="Отправить" onclick="javascript: handlerForm.init('#frmordertour','#ordertourMsg').ajax(); return false;" />
                            </div>    
                        </label>
                    </fieldset>      
                    <div id="ordertourMsg"></div>
            </form>
        </div>
    </div>
</div>