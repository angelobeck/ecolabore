'text'={
'caption'={
'pt'={
1='Título nível 2'
2=1
}
'en'={
1='Header 2'
}
}
}
'html'='[if($url){ `<a href="`; $url; `">`; nl; }
`<h2`; lang($title); `>`; text($title); `</h2>`; nl;
if($url){ `</a>`; nl; }'
