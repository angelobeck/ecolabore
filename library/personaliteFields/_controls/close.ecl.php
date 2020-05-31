'flags'={
'modLayout_base'='simple'
'modLayout_from'='control'
'modLayout_name'='dialogFields_close'
'modHumperstilshen_enabled'=0
}
'html'='[script]
[if($document.field_replace){]
window.opener.dinamicSelect.replace ("[$document.field_name]", "[text $document.field_caption]", "[$document.field_filter]", "[$document.field_content]");
[}else{]
window.opener.dinamicSelect.append ("[$document.field_name]", "[text $document.field_caption]", "[$document.field_filter]", "[$document.field_content]");
[}]
window.close();
[/script]
'
