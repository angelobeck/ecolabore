'text'={
'caption'={
'pt'={
1='Título nível 6'
2=1
}
'en'={
1='Header 6'
}
}
}
'html'='[if($url){ `<a href="`; $url; `">`; nl; }
if(!$url and $editable){ `<h6 `; lang($title); `data-name="`; $id; `_title" data-mode="field" data-placeolder="`; text(`field_title`); `" onfocus="EcolaboreEditor.eventFocus(event)" onblur="EcolaboreEditor.eventBlur(event)" contenteditable>`; text($title); `</h6>`; nl; }
else { `<h6`; lang($title); `>`; text($title); `</h6>`; nl; }
if($url){ `</a>`; nl; }'
