'text'={
'caption'={
'pt'={
1='Padrão'
2=1
}
'en'={
1='Default'
}
}
}
'html'='
<!-- [$name] -->
<[$mod-semantic or `div`;
inline_class(`mod position-$position??left wd-sm-$wd-sm??12 wd-md-$wd-md wd-lg-$wd-lg wd-xl-$wd-xl`);
inline_style(`padding-top:$mod-padding-top; padding-right:$mod-padding-right; padding-bottom:$mod-padding-bottom; padding-left:$mod-padding-left`);
`>`; nl;

if($caption-display and !$caption-legend){ `<span`; lang; ` class="caption">`; text; `</span>`; nl; }

`<div`;
inline_class(`box box-display-$box-display $box-scheme $box-shadow`);
inline_style(`padding:$box-padding; padding-right:$box-horizontal-padding; padding-left:$box-horizontal-padding; font-size:$box-font-size; border-radius:$box-border-radius; background-color:$box-background-color`);
`>`; nl;

list($list);

`</div>`; nl;

if($caption-display and $caption-legend){ `<span`; lang; ` class="legend">`; text; `</span>`; nl; }
personalite;

`</`; ($mod-semantic or `div`); `>`; nl;
]
<!-- /[$name] -->
'
