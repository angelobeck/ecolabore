'flags'={
'modLayout_base'='simple'
'modLayout_from'='templates'
'modLayout_name'='dialog_close'
}
'html'='[script]
[if($document.module){]
window.opener.EcolaboreEditor.insertModule(''[$document.module]'');
[}elseif ($document.script){ 
$document.script; 
}else{]
window.opener.location.assign(window.opener.location.href);

[}]
window.close();
[/script]'
