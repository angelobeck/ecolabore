'text'={
'caption'={
'pt'={
1='Botão com legenda'
2=1
}
'en'={
1='Button with caption'
}
}
}
'html'='[if($menu){]
<a href="javascript:humperstilshen.menuToggle(''[$menu]'', ''button'')" id="[$menu]" class="button"[lang]>[text]<span class="sr-only" id="[$menu]_alt">[text:layout_menu_colapsed]</span></a>
[script]
humperstilshen.menuAdd ("[$menu]");
[/script]
<div id="[$menu]_details" class="dropdown [$mod.box-scheme]" hidden>
[list{loop{]
<a href="[$url]" class="button"[lang]>[text]</a>
[}}
if($can_edit){]
<a href="[$url]" class="button">[text:layout_menu_access]</a>
[}]
<a class="sr-only" href="javascript:humperstilshen.menuToggle()">[text:layout_menu_colapse]</a>
</div>
[}elseif($active or !$url){]
<span class="button active"[lang]>[ if $editable{]<span data-name="[$id]_caption" data-mode="field" onfocus="EcolaboreEditor.eventFocus(event)" onblur="EcolaboreEditor.eventBlur(event)" title="[text:field_caption]" contenteditable>[text]</span>[}else{ text; }]</span>
[}else{]
<a href="[$url]" class="button"[lang]>[text]</a>
[}'
