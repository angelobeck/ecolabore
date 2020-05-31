'text'={
'caption'={
'pt'={
1='Vídeo (Reproduzir)'
2=1
}
'en'={
1='Video (Play)'
}
}
}
'html'='[scope(`video`){]
<div>
[if $editable {]
<a class="button" href="[$url]">[text:field_media_add_video]</a>
[}else{]
<video src="[$url_play]" controls></video>
[$plays] [text:detail_plays]
[}]
</div>
[}]'
