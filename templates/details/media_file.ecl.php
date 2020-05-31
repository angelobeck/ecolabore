'text'={
'caption'={
'pt'={
1='Arquivo (Baixar)'
2=1
}
'en'={
1='File (Download)'
}
}
}
'html'='[scope(`file`){]
<div>
[if $editable {]
<a class="button" role="button" href="[$url]">[text:field_media_add_file]</a>
[}else{]
<div>[$filename]</div>
<a class="button" role="button" href="[$url_download]" download="[$filename]">[text:action_download]</a>
[$downloads] [text:field_media_downloads]
[}]
</div>
[}]'
