'text'={
'caption'={
'pt'={
1='Localização (caminho completo)'
2=1
}
'en'={
1='Location (full pathway)'
}
}
}
'html'='[scope(`locationFull`){]
<div>
<span class="caption">[text:field_location]</span>
[list{loop{
if(!$first){ `&rarr;`; }]
<a href="[$url]">[text]</a>
[}}]
</div>
[}]
'
