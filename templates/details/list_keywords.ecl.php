'local'={
'filters'='keywords'
}
'text'={
'caption'={
'pt'={
1='Palavras-chave'
2=1
}
'en'={
1='Keywords'
}
}
}
'html'='[scope(`keywords`){]
<div>
<span class="caption">[text:field_keywords]</span>
[list{loop{]
<a href="[$url]">[text]</a>
[}}]
</div>
[}]
'
