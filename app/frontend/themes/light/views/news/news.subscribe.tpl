<div class="wrapper_subscribe">
    <div class="tabs">
        <div class="tabs_unit active">
            <div class="tabs_l dib">
                <div class="tabs_r dib">
                    <div class="tabs_c dib">
                        {$Lang->subscribe}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="simple_border subscribe">
        <form action="/{$module}/savesubscribe" id="formSubscribe">
             <input type="text" name="email" class="notEmpty m_floatl" id="sub_email">
             <div class="but_l m_dib m_floatr">
                <div class="but_r m_dib">
                    <input type="button" value="{$Lang->sent}" onclick="javascript:handlerForm.init('#formSubscribe', '.result-form-subscribe').ajax()" />
                </div>
             </div>
        </form>
    <div class="result-form-subscribe" style="display: none;"></div>
    </div>        
</div>