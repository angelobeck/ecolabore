'html'='<html lang="[$document.lang]">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=[$document.charset]">
<meta http-equiv="Content-Security-Policy" content="[$document.content_security_policy]">
<meta name="title" content="[text$document.title or $document.caption]">
[if($document.description){ `<meta name="description" content="`; text($document.description); `">`; nl; }]
[if($document.keywords){ `<meta name="keywords" content="`; text($document.keywords); `">`; nl; }]
<meta name="date" content="[$document.date]">
<meta name="generator" content="[$system.generator] v[$system.version]@[$system.release]">
<meta name="format-detection" content="telephone=no">
<meta name="viewport" content="width=device-width, initial-scale=1">
[mod(`languages_header`){  if($url_rss){]
<link rel="alternate" type="application/rss+xml" lang="[$document.lang]" href="[$url_rss]" title="[text $rss]">
[}if($url_canonical){]
<meta name="robots" content="noindex">
<link rel="canonical" href="[$url_canonical]">
[}list{loop{]
<link rel="alternate" href="[$url]" hreflang="[$lang]">
[}}}]
<link rel="stylesheet" type="text/css" href="[$document.url_styles]">
<script src="[shared:libraries/ecolabore-basics.js]"></script>
[paste:headerlinks]
<title>[text$document.title or $document.caption]</title>
[paste:style]
</head>
<body [if($document.scheme){ ` class="`; $document.scheme; `"`; }]>

<!-- layout_system_bar -->
<div id="layout_system_bar" class="system system-bar">

[paste:bar_left_icons]
[scope(`home`){]
<!-- layout_system_title -->
[if($editable){]
<span><span class="bar" data-name="[$id]_title" data-mode="field" onfocus="EcolaboreEditor.eventFocus(event)" contenteditable>[text $title]</span></span>
[}elseif($url){]
<a href="[$url]"><span class="bar">[text $title]</span></a>
[}else{]
<span><span class="bar">[text $title]</span></span>
[}]
<!-- /layout_system_title -->
[} // scope]

</div>
<!-- /layout_system_bar -->

<!-- layout_document -->
<div id="layout_document">
<a name="topo"></a>

[mod:layout]
</div>
<!-- /layout_document -->

[mod:editor]
[mod:instructor]
<!-- layout system icons -->
<div id="layout_system_icons" class="system system-bar">

[paste:bar_right_icons]
[paste:editor_icon]
[paste:instructor_icon]
<a href="javascript:gadgets.humperstilshen.actionOpen(''menu'')"  id="humperstilshen" role="button" aria-live="polite" aria-labelledby="humperstilshen_accessibility_label">
<img src="[shared:icons/ecolabore-flower.svg]" alt="[text:layout_assistant_open]" id="humperstilshen_accessibility_label">
</a>
</div>
<!-- /layout_system_icons -->

<!-- layout baloom -->
<div id="layout_baloom" hidden>

[mod(`humperstilshen`){ list{loop{]

<!-- humperstilshen [$name] -->
<div id="humperstilshen_[$name]" class="system baloom ecl-humperstilshen[if($alert){ ` alert`; } if($is_formulary){] wd-sm-11 wd-md-8 wd-lg-5 wd-xl-4 [}else{] wd-sm-10 wd-md-6 wd-lg-3 wd-xl-2 [}]
" hidden>

[if($is_formulary){
if($icon){
if($caption){ `<div class="caption">`; text; `</div>`; nl; }]
<table border="0"><tr><td>
<img src="[$icon]" alt="[text $icon_description]">
</td><td>
[text $content]
</td></tr></table>
[ }else{
if($caption){ `<div class="caption">`; text; `</div>`; nl; }
text($content); 
}
list{ loop{ field; }}
}else{ 
list{ loop{ mod($name); }}
}
if($add_close_button){]
<hr>
<span class="center">
<button onclick="gadgets.humperstilshen.actionClose(''[$return_id]'')">[text:action_close]</button>
</span>
[}elseif($add_confirm_button){]
<hr>
<span class="center">
<button onclick="gadgets.humperstilshen.actionConfirm(''[$url]'')">[text:action_ok]</button>
<button onclick="gadgets.humperstilshen.actionClose()">[text:action_cancel]</button>
</span>
[}]
</div>
<!-- /humperstilshen_[$name] -->

[} }
}]
[mod(`humperstilshen_submenu`){ list{ loop{]
<!-- humperstilshen [$name] -->
<div id="humperstilshen_[$name]" class="system baloom ecl-humperstilshen wd-sm-11 wd-md-8 wd-lg-5 wd-xl-4 " hidden>

[list{ loop{ mod($name); }}]

<hr>
<span class="center">
<button onclick="gadgets.humperstilshen.actionClose()">[text:action_close]</button>
</span>
</div>
<!-- /humperstilshen_[$name] -->
[}}}]
</div>
<!-- /layout_baloom -->

[paste:script]
</body>
</html>'
