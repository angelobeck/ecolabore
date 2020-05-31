'text'={
'caption'={
'pt'={
1='Legenda'
2=1
}
'en'={
1='Caption'
}
}
}
'html'='[if($active or !$url){]
<span class="a active"[lang]>[text]</span>
[}else{]
<a href="[$url]" [lang]>[text]</a>
[}'
