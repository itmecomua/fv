<div class="block_a_wrap">
	<div class="block_a_lft">
		<div class="block_a_rt">
			<div class="block_a_md">
				<h2>Погода</h2>
			</div>
		</div>
	</div>
	<div class="block_a_bd">
        <div class="h_pad1">

{* *}
<div>
<ul class="list_c">
<li>
    <span class="ww_left">Город</span>
    <span  class="ww_right">
    {html_options name="resort" options=$resMeteo selected=28 onchange="javascript:getMeteoData();"}
    </span>
</li>
<li>
    <span class="ww_left">Дата:</span>
    <span  class="ww_right">
    {html_options name="date" values=$dateMeteo options=$dateMeteo selected=$today onchange="javascript:getMeteoData();"}
    </span>
</li>

{* кнопка узнать погоду - нету в дизайне
<li>
    <div class="but1">
        <input type="submit" value="Узнать погоду" onclick="javascript:getMeteoData();" />
    </div>
</li>
*}

</ul>
<div id="result_meteo_data">
    {*Сюда аджяксом попадут данные метео*}
</div>
{if !$export}
<script type="text/javascript">
{literal}
function getMeteoData()
{
      var val = jQuery("select[name='resort']").val();
      var date = jQuery("select[name='date']").val();
        jQuery("#result_meteo_data").addClass("wait");
        jQuery.ajax({
            url: '/meteo',
            data: "spo_code="+val+'&date='+date,
            type: 'post',
            success: function(data)
            {
                jQuery("#result_meteo_data").html(data).removeClass("wait");
                }
            });

}
getMeteoData();
{/literal}
</script>
{/if}
</div>
{* *}

        </div>
	</div>
</div>



