'html'='[cut:headerlinks]
<script src="[shared:scripts/ecolabore-instructor.js]"></script>
[/cut]
[script]

instructor = new instructorClass("[$mod.name]");
instructor.captionPlay = "[text:action_play]";
instructor.captionPause = "[text:action_pause]";
instructor.captionRepeat = "[text:action_repeat]";
[/script]
[list{loop{]
[script]
message = instructor.messageAdd ("[$name]");
[if($audio){]
message.audioAdd ("[$audio]");
[} if($instructor_autoplay){]
message.autoplay = true;
[}if($instructor_onload){]
humperstilshen.onLoad = function(){
instructor.open ("[$name]");
}
[}elseif($instructor_onalert){]
humperstilshen.onAlert = function(){
instructor.open ("[$name]");
}
[}elseif($instructor_onmenuopen){]
humperstilshen.onMenuOpen = function(){
instructor.open ("[$name]");
}
[}]
[/script]
[cut:instructor_icon]
<a href="javascript:instructor.play()" id="[$mod.name]_[$name]_icon_play" hidden>
<img src="[shared:icons/editor/play.svg]" alt="[text:navigation_instructor_play_icon]">
</a>

<a href="javascript:instructor.play()" id="[$mod.name]_[$name]_icon_pause" hidden>
<img src="[shared:icons/editor/pause.svg]" alt="[text:navigation_instructor_pause_icon]">
</a>

[/cut]
<!-- [$mod.name]_[$name] -->
<div id="[$mod.name]_[$name]" class="instructor" hidden>
<div id="[$mod.name]_[$name]_subtitles_box" class="instructor-subtitles">
<div id="[$mod.name]_[$name]_subtitles" class="instructor-subtitles-scroll">
[text $content]
</div>
</div>
<div class="instructor-controls">
[if($audio){]
<button id="[$mod.name]_[$name]_play" onclick="instructor.play()">[text:action_play]</button>
[}if($url){]
<button onclick="location.replace(''[$url]'')">[text:action_next]</button>
[cut:instructor_icon]
<a href="[$url]">
<img src="[shared:icons/editor/next.svg]" alt="[text:navigation_instructor_next_icon]">
</a>

[/cut]
[}]
<button onclick="instructor.close()">[text:action_close]</button>
</div>
</div>
<!-- [$mod.name]_[$name] -->

[}}]
'
