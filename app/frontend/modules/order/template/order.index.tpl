<div class="block_a_wrap">
    <div class="block_a_lft">
        <div class="block_a_rt">
            <div class="block_a_md">
                <h2>Турзаявка</h2>
            </div>
        </div>
    </div>
    <div class="block_a_bd">
        <div class="h_pad1">
            <br />
            <p style="text-align:center" class="MsoNormal">
                <b>
                    <span style="color: #FF0000">
                        У вас нет времени на поиск тура?
                    </span>
                </b>
                <span style="color: #FF0000;">
                <br>
                    <b>
                        Хотите нестандартный тур или услугу?
                    </b>
                </span>
                <br>
                <b>
                    Оставьте заявку. Мы предложим вам лучшие варианты.
                    <br>
                    Выбирайте и отдыхайте с удовольствием.
                    <br>
                    <span style="color: #FF0000;">
                        Услуга бесплатная!
                    </span>
                </b>
            </p>
            <br />


            <form class="form_c" action="/order/save" method="post" id="frmOrder">
                    <fieldset class="form_c_twocol" >
                        <legend>Ваше имя*</legend>
                        <label><input type="text" name="update[name]" id="order_name"/></label>
                        <legend>Телефон:*</legend>
                        <label><input type="text" name="update[phone]" id="order_phone"/></label>                        
                    </fieldset>
                    <fieldset>
                        <legend>E-mail*</legend>
                        <label><input type="text" name="update[email]" id="order_email"/></label>
                    </fieldset>
                    <fieldset>
                        <legend>Тур в страну:*</legend>
                        <label><input type="text" name="update[country]" id="order_country"/></label>
                    </fieldset>
                    <fieldset>
                        <legend>Город:*</legend>
                        <label><input type="text" name="update[city]" id="order_city"/></label>
                    </fieldset>
                    <fieldset class="form_c_twocol" >
                        <legend>Количество взрослых:</legend>
                        <label><input type="text" name="update[cnt_adult]" id="order_cnt_adult"/></label>
                        <legend>Количество детей:</legend>
                        <label><input type="text" name="update[cnt_child]" id="order_cnt_child"/></label>
                    </fieldset>
                    <fieldset class="form_c_twocol" >
                        <legend>Бюджет поездки:</legend>
                        <label><input type="text" name="update[budget]" id="order_budget"/></label>
                        <legend>Продолжительность:</legend>
                        <label><input type="text" name="update[duration]" id="order_duration"/></label>
                    </fieldset>
                    <fieldset>
                        <legend>Желательные даты:</legend>
                        <div class="form_c_data">
                            <span>c:</span>
                            <input type="text" name="update[date_fr]" id="order_date_fr" class="_datepicker" />
                            <span>до:</span>
                            <input type="text" name="update[date_to]" id="order_date_to" class="_datepicker" />
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>Уровень отеля:</legend>
                        <div class="form_c_inline">
                            <input type="checkbox" name="update[hotel_type_3]" id="order_hotel_type_3"/><span>3*</span>
                            <input type="checkbox" name="update[hotel_type_4]" id="order_hotel_type_4"/><span>4*</span>
                            <input type="checkbox" name="update[hotel_type_5]" id="order_hotel_type_5"/><span>5*</span>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>Питание:</legend>
                        <div class="form_c_inline">
                            <input type="checkbox" name="update[meal_breakfast]" id="order_meal_breakfast"/><span>завтрак</span>
                            <input type="checkbox" name="update[meal_pansion]" id="order_meal_pansion"/><span>завтрак, обед и ужин</span>
                            <input type="checkbox" name="update[meal_half_pansion]" id="order_meal_half_pansion"/><span>завтрак и обед</span>
                            <input type="checkbox" name="update[meal_ai]" id="order_meal_ai"/><span>все включено</span>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>Пожелания:</legend>
                        <label><textarea name="update[wish_text]" id="order_wish_text"></textarea></label>
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
                                <input type="submit" value="Отправить" onclick="javascript: handlerForm.init('#frmOrder','#orderMsg').ajax(); return false;" />
                            </div>    
                        </label>
                    </fieldset>      
                    <div id="orderMsg"></div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
{literal}
    jQuery("._datepicker").datepicker({dateFormat: "dd.mm.yy"})
{/literal}
</script>