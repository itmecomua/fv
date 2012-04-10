<script type="text/javascript">
{literal}
    jQuery(document).ready(function()
    {
        jQuery('#{/literal}{$id}{literal}').redactor({ focus: true });
    });
{/literal}
</script>
<textarea id="{$id}" name="{$name}" style="width: {$width}; height: {$height};">{$text}</textarea>
