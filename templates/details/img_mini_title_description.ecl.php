'local'={
'filters'='description img'
}
'text'={
'caption'={
'pt'={
1='Miniatura à esquerda + título + descrição'
2=1
}
'en'={
1='Left thumbnail + title + description'
}
}
}
'html'='<div style="display:flex; width:100%; ">
[scope(`img`){]
<div style="flex-shrink:1">
<div[inline_class:img-shape-$mod.img-shape??auto]>
<div[inline_class:$mod.img-smoke]>
[if($url){ `<a href="`; $url; `">`; nl; }]
<img src="[$url_img_mini]"[inline_class:img-orientation-$landscape??portrait] alt="[text $description]" style="filter:[
if $img-filter-brightness {] brightness([$img-filter-brightness]%)[}
if $img-filter-contrast {] contrast([$img-filter-contrast]%)[}
if $img-filter-hue-rotate {] hue-rotate([$img-filter-hue-rotate]deg)[}
if $img-filter-saturate {] saturate([$img-filter-saturate]%)[}

if $mod.img-filter-grayscale {] grayscale([$mod.img-filter-grayscale]%)[}
if $mod.img-filter-sepia {] sepia([$mod.img-filter-sepia]%)[}
]">
[if($url){ `</a>`nl; }]
</div>
</div>
</div>
[}]
<div style="flex-shrink:2">
[if $url{]<a href="[$url]">
<h4[lang $title]>[text $title]</h4>
</a>
[}else{]
<h4[lang $title]>[text $title]</h4>
[}
if($description or $editable){]
<p[if $editable{] title="[text:field_description]" data-name="[$id]_description" data-mode="field" onfocus="EcolaboreEditor.eventFocus(event)" onblur="EcolaboreEditor.eventBlur(event)" [if!$description{] class="editor-empty-field"[}]contenteditable[}]>[text $description]</p>
[}]
</div>
</div>
'
