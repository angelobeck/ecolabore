'local'={
'filters'='img'
}
'text'={
'caption'={
'pt'={
1='Imagem'
2=1
}
'en'={
1='Image'
}
}
}
'html'='[scope(`img`){]
<div[inline_class:img-shape-$mod.img-shape??auto]>
<div[inline_class:$mod.img-smoke]>
<img src="[$url_img]"[inline_class:img-orientation-$landscape??portrait] alt="[text $description]" style="filter:[
if $img-filter-brightness {] brightness([$img-filter-brightness]%)[}
if $img-filter-contrast {] contrast([$img-filter-contrast]%)[}
if $img-filter-hue-rotate {] hue-rotate([$img-filter-hue-rotate]deg)[}
if $img-filter-saturate {] saturate([$img-filter-saturate]%)[}

if $mod.img-filter-grayscale {] grayscale([$mod.img-filter-grayscale]%)[}
if $mod.img-filter-sepia {] sepia([$mod.img-filter-sepia]%)[}
]">
</div>
</div>
[}]'
