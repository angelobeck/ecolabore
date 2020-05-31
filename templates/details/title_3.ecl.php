'text'={
'caption'={
'pt'={
1='Título nível 3'
2=1
}
'en'={
1='Header 3'
}
}
}
'html'='[if($url){ `<a href="`; $url; `">`; nl; }
`<h3`; lang($title); `>`; text($title); `</h3>`; nl;
if($url){ `</a>`; nl; }'
