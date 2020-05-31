'text'={
'caption'={
'pt'={
1='Localização'
2=1
}
'en'={
1='Location'
}
}
}
'html'='[scope(`location`){]
<div>
<span class="caption">[text:field_location]</span>
[list{loop{
if(!$first){ `&rarr;`; }]
<a href="[$url]">[text]</a>
[}}]
</div>
[}]
'
