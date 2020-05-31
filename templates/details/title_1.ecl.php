'text'={
'caption'={
'pt'={
1='Título nível 1'
2=1
}
'en'={
1='Header 1'
}
}
}
'html'='[if($url){ `<a href="`; $url; `">`; nl; }
if(!$url and $editable){ `<h1 `; lang($title); `data-name="`; $id; `_title" data-mode="field" data-title="`; text(`field_title`); `" onfocus="EcolaboreEditor.eventFocus(event)" onblur="EcolaboreEditor.eventBlur(event)" contenteditable>`; text($title); `</h1>`; nl; }
else { `<h1`; lang($title); `>`; text($title); `</h1>`; nl; }
if($url){ `</a>`; nl; }'
