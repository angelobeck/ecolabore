'text'={
'caption'={
'pt'={
1='Localização (em linha)'
2=1
}
'en'={
1='Location (inline)'
}
}
}
'html'='[scope(`location`){]
<p class="info">[text:field_location_at]
[list{loop{
if(!$first){ `&rarr;`; }]
<a href="[$url]">[text]</a>
[}}]
</p>
[}]
'
