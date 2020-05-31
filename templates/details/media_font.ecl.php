'local'={
'filters'='fonts'
}
'text'={
'caption'={
'pt'={
1='Fonte (exibir família)'
2=1
}
'en'={
1='Font (display family)'
}
}
}
'html'='[scope(`fonts`){]
<div>
[if($regular-url){]
[style]
@font-face {
font-family:[$font-family];
src:[$font-stack-start]url("[$regular-url]") format("[$regular-format]");
}
[/style]
<p class="caption">[text:detail_font_regular]</p>
<h3 style="font-family:[$font-stack]; font-weight:normal; font-style:normal">[text $demonstration]</h3>
[}]
[if($italic-url){]
[style]
@font-face {
font-family:[$font-family];
src:[$font-stack-start]url("[$italic-url]") format("[$italic-format]");
font-weight:normal;
font-style:italic;
}
[/style]
<p class="caption">[text:detail_font_italic]</p>
<h3 style="font-family:[$font-stack]; font-weight:normal; font-style:italic">[text $demonstration]</h3>
[}]
[if($bold-url){]
[style]
@font-face {
font-family:[$font-family];
src:[$font-stack-start]url("[$bold-url]") format("[$bold-format]");
font-weight:bold;
font-style:normal;
}
[/style]
<p class="caption">[text:detail_font_bold]</p>
<h3 style="font-family:[$font-stack]; font-weight:bold">[text $demonstration]</h3>
[}]
[if($bold-italic-url){]
[style]
@font-face {
font-family:[$font-family];
src:[$font-stack-start]url("[$bold-italic-url]") format("[$bold-italic-format]");
font-weight:bold;
font-style:italic;
}
[/style]
<p class="caption">[text:detail_font_bold_italic]</p>
<h3 style="font-family:[$font-stack]; font-style:italic; font-weight:bold">[text $demonstration]</h3>
[}]

[if($add){]
<a href="[$add]" class="button">[text:detail_font_favorite_add]</a>
[}elseif($remove){]
<a href="[$remove]" class="button">[text:detail_font_favorite_remove]</a>
[}]
<label>[text:detail_font_face]<br>
<textarea style="width:100%; height:10rem" class="input">
[if($regular-url){]
@font-face {
font-family:[$font-family];
src:[$font-stack-start]url("[$regular-url]") format("[$regular-format]");
font-display:swap;
}
[}]
[if($italic-url){]
@font-face {
font-family:[$font-family];
src:[$font-stack-start]url("[$italic-url]") format("[$italic-format]");
font-weight:normal;
font-style:italic;
font-display:swap;
}
[}]
[if($bold-url){]
@font-face {
font-family:[$font-family];
src:[$font-stack-start]url("[$bold-url]") format("[$bold-format]");
font-weight:bold;
font-style:normal;
font-display:swap;
}
[}]
[if($bold-italic-url){]
@font-face {
font-family:[$font-family];
src:[$font-stack-start]url("[$bold-italic-url]") format("[$bold-italic-format]");
font-weight:bold;
font-style:italic;
font-display:swap;
}
[}]
</textarea>
</label>
<label>[text:detail_font_stack]<input type="text" value="[$font-stack]" class="input"></label>
</div>
[}]'
