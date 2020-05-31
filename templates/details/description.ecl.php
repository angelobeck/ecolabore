'local'={
'filters'='description'
}
'text'={
'caption'={
'pt'={
1='Descrição'
2=1
}
'en'={
1='Description'
}
}
}
'html'='[if($description or $editable){]
<p[if $editable{] data-name="[$id]_description" data-mode="field" onfocus="EcolaboreEditor.eventFocus(event)" onblur="EcolaboreEditor.eventBlur(event)" [if!$description{] class="editor-empty-field"[}]contenteditable[} `>`; text($description); `</p>`; }'
