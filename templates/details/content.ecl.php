'local'={
'filters'='content'
}
'text'={
'caption'={
'pt'={
1='Conteúdo'
2=1
}
'en'={
1='Content'
}
}
}
'html'='<div[if $editable{] id="stack_[$id]" [cut:footerscript editor_setStack]

EcolaboreEditor.stack = document.getElementById("stack_[$id]");
EcolaboreEditor.fields[]"[$id]_content"] = EcolaboreEditor.stack;
[/cut]
 data-name="[$id]_content" data-mode="stack"[}]>[text($content, $editable)]</div>
'
