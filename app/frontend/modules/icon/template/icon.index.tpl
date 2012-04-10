<div class="block_a_wrap">
	<div class="block_a_lft">
		<div class="block_a_rt">
			<div class="block_a_md">
				<a href="/services">Наши услуги</a>
			</div>
		</div>
	</div>
	<div class="block_a_bd">


<ul class="icons_wrap">
{foreach from=$list item=ex}
<li>
    <div class="ic_img_wrp">
    <img src="{$ex->getImageSrc(true)}"
         alt="{$ex->getName()|escape}"
         onclick="javascript:
         {if $ex->isTarget()}
            openInNewWindow('/{$ex->getURL()}');
         {else}
             window.location.href='/{$ex->getURL()}'
         {/if}"
         />
    </div>
    <b  onclick="javascript:
        {if $ex->isTarget()}
            openInNewWindow('/{$ex->getURL()}');
        {else}
            window.location.href='/{$ex->getURL()}'
        {/if}"
    >{$ex->getName()}</b>
</li>
{/foreach}
</ul>

	</div>
</div>

<script type="text/javascript">
{literal}
    function openInNewWindow(href) {
        var newWindow = window.open(href, '_blank');
        newWindow.focus();
        return false;
    }
{/literal}
</script>