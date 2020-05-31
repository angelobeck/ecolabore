'text'={
'caption'={
'pt'={
1='Botão com título'
2=1
}
'en'={
1='Button with title'
}
}
}
'html'='[if($active or !$url){]
<span class="button active"[if $editable{] data-name="[$id]_title" data-mode="field" onfocus="EcolaboreEditor.eventFocus(event)" onblur="EcolaboreEditor.eventBlur(event)" contenteditable[} lang $title]>[text $title]</span>
[}else{]
<a href="[$url]" class="button"[lang $title]>[text $title]</a>
[}'
