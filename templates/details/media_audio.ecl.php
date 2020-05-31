'text'={
'caption'={
'pt'={
1='Áudio (Reproduzir e Baixar)'
2=1
}
'en'={
1='Audio (Play and Download)'
}
}
}
'html'='[scope(`audio`){]
<div>
[if $editable {]
<a class="button" role="button" href="[$url]">[text:field_media_add_audio]</a>
[}else{]
<button onclick="gadgets.audio.play(''[$url_play]'', this)" data-play="[text:action_play]" data-pause="[text:action_pause]" aria-live="polite">[text:action_play]</button>
[$play] [text:field_media_plays]
<a class="button" role="button" href="[$url_download]" download="[$filename]">[text:action_download]</a>
[$downloads] [text:field_media_downloads]
[}]
</div>
[}]'
