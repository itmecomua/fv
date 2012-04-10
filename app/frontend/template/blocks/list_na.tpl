            <div class="left_menu_top">
                <div class="left_menu_bot">
                    <div class="left_menu_mid">
                        <div class="list_menu">
                            <div>
                                <ul>
                                    <li class="left_menu_01_1">
                                        <div class="but1 active" id="list_na_button_tourtypes">
                                            <a onclick="javascript: tabsListNa('tourtypes','countries');" href="javascript:void(0);">Страны</a>
                                        </div>
                                    </li>
                                    <li class="left_menu_01_2">
                                        <div class="but1" id="list_na_button_countries">
                                            <a onclick="javascript: tabsListNa('countries','tourtypes');" href="javascript:void(0);">Типы туров</a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div id="list_na_tabs_tourtypes">
                            {show_module     module="countries" view='listlft'}
                        </div>
                        <div id="list_na_tabs_countries" style="display: none;">
                            {show_module     module="tours" view='listtourtypelft'}
                        </div>
                    </div>
                </div>
            </div>
<script type="text/javascript">
{literal}
    function tabsListNa(show,hide)
    {
        jQuery('#list_na_tabs_'+hide).hide();
        jQuery('#list_na_tabs_'+show).show();
        
        jQuery('#list_na_button_'+hide).removeClass("active");
        jQuery('#list_na_button_'+show).addClass("active");
    }
{/literal}
</script>