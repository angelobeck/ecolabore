'text'={
'caption'={
'pt'={
1='T�tulo n�vel 5'
2=1
}
'en'={
1='Header 5'
}
}
}
'html'='[if($url){ `<a href="`; $url; `">`; nl; }
if(!$url and $editable){ `<h5 `; lang($title); `data-name="`; $id; `_title" data-mode="field" data-placeolder="`; text(`field_title`); `" onfocus="EcolaboreEditor.eventFocus(event)" onblur="EcolaboreEditor.eventBlur(event)" contenteditable>`; text($title); `</h5>`; nl; }
else { `<h5`; lang($title); `>`; text($title); `</h5>`; nl; }
if($url){ `</a>`; nl; }'
