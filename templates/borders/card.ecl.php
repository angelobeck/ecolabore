'text'={
'caption'={
'pt'={
1='Cartão'
2=1
}
'en'={
1='Card'
}
}
}
'html'='
<!-- card -->
<div [
inline_class(`mod position-$position??left wd-sm-$wd-sm??12 wd-md-$wd-md wd-lg-$wd-lg wd-xl-$wd-xl`);
inline_style(`padding-top:$mod-padding-top; padding-right:$mod-padding-right; padding-bottom:$mod-padding-bottom; padding-left:$mod-padding-left `);
]>
<div [
inline_class(`box box-display-block $box-scheme $box-shadow`);
inline_style(`padding:$box-padding; padding-right:$box-horizontal-padding; padding-left:$box-horizontal-padding; font-size:$box-font-size; border-radius:$box-border-radius; background-color:$box-background-color; min-height:$box-min-height`);
]>
<span class="sr-only">[text:layout_card_start]</span>
[$value]
[personalite]
</div>
</div>
<!-- /card -->
'
