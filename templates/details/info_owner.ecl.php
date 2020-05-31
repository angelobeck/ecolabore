'text'={
'caption'={
'pt'={
1='Por (nome do autor)'
2=1
}
'en'={
1='By (author name)'
}
}
}
'html'='[scope(`user`, $owner_id){]
<p class="info">[text:field_info_by] [if $url{]<a href="[$url]">[text $caption]</a>[}else{ text($caption); }]</p>
[}'
