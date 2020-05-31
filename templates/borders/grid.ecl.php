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
<!-- grid -->
<div [
inline_class(`mod position-$position??left wd-sm-$wd-sm??12 wd-md-$wd-md wd-lg-$wd-lg wd-xl-$wd-xl`);
inline_style(`padding-top:$mod-padding-top; padding-right:$mod-padding-right; padding-bottom:$mod-padding-bottom; padding-left:$mod-padding-left `);
]>
<div [
inline_class(`box box-display-block $box-scheme $box-shadow`);
inline_style(`padding:$box-padding; padding-right:$box-horizontal-padding; padding-left:$box-horizontal-padding; font-size:$box-font-size; border-radius:$box-border-radius; background-color:$box-background-color; min-height:$box-min-height`);
]>
<div[inline_class:list list-layout-$list-layout??grid list-align-$list-align col-sm-$col-sm col-md-$col-md col-lg-$col-lg col-xl-$col-xl list-column-gap-$list-column-gap list-row-gap-$list-row-gap details-horizontal-align-$details-hhorizontal-align details-vertical-align-$details-vertical-align]>
[$value]
</div>
[personalite]
</div>
</div>
<!-- /grid -->
'
