'html'='
[style]
#layout_panel_main { right:2rem; }
.ecl-editor-button { width:2em; height:2rem; }
.editor-empty-field { display:inline-block; width:1em; height:1em; background-image:url("[shared:icons/editor/pen.svg]"); background-repeat:no-repeat; background-size:1em 1em; }
/* .editor-empty-field[data-placeholder]:after { content:"(" attr(data-placeholder) ")"; opacity:.8; } */
[/style]
<!-- layout_editor -->
<div id="layout_editor" class="system">
[text]
[list{ loop{]
<a href="[$url]">
<img src="[$url_icon]" alt="[text]" class="ecl-editor-button">
</a>
[}}]
</div>
<!-- /layout_editor -->

'
'text'={
'caption'={
'pt'={
1='Inserir'
2=1
}
'en'={
1='Insert'
}
}
}
'children'={
#='~editorH2'
#='~editorH3'
#='~editorH4'
#='~editorP'
#='~editorLO'
#='~editorLA'
#='~editorLU'
#='~editorHR'
#='~editorA'
#='~editorImg'
#='~editorBox'
#='~editorTable'
#='~editorVideo'
#='~editorAudio'
#='~editorFile'
#='~editorHtml'
}
