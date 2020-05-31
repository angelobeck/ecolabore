'flags'={
'modLayout_base'='simple'
'modLayout_from'='control'
'modLayout_name'='dialogFields_load'
'modHumperstilshen_enabled'=0
}
'html'='[script]

// load configuration on startup
document.getElementById (''field_name'').value = window.opener.dinamicSelect.loadName();
document.getElementById (''field_content'').value = window.opener.dinamicSelect.loadContent();
document.controls.submit();
[/script]
<form id="controls" name="controls" action="[$document.ref]" method="post">
<input type="hidden" name="field_name" id="field_name" >
<input type="hidden" id="field_content" name="field_content"></textarea>
</form>
'
