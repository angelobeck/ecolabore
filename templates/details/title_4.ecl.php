'text'={
'caption'={
'pt'={
1='Título nível 4'
2=1
}
'en'={
1='Header 4'
}
}
}
'html'='[if($url){ `<a href="`; $url; `">`; nl; }
if(!$url and $editable){ `<h4 `; lang($title); `data-name="`; $id; `_title" data-mode="field" data-placeolder="`; text(`field_title`); `" onfocus="EcolaboreEditor.eventFocus(event)" onblur="EcolaboreEditor.eventBlur(event)" contenteditable>`; text($title); `</h4>`; nl; }
else { `<h4`; lang($title); `>`; text($title); `</h4>`; nl; }
if($url){ `</a>`; nl; }'
