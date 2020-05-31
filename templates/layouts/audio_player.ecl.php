'flags'={
'modLayout_base'='responsive'
'modLayout_from'='templates'
'modLayout_name'='audio_player'
}
'local'={
'scheme'='system'
}
'html'='

<audio id="audio"></audio>

<button id="audio-play"> Reproduzir </button>
<button id="audio-pause"> Pausar </button>
<button id="audio-rewind"> Retroceder </button>
<button id="audio-forward"> Avan&ccedil;ar </button>
<button id="audio-previous"> Anterior </button>
<button id="audio-next"> Pr&oacute;xima </button>
<button id="audio-volume-decrease"> Reduzir volume </button>
<button id="audio-volume-increase"> Aumentar volume </button>

<br>
<label for="playlist"> Playlist </label>
[mod:list{]
<select id="playlist" size="10"  class="input">
[list{ loop{]
<option value="[$name]" data-url="[$url]"[if($first){] selected[}]>[text]</option>
[}}]

</select>
<label><input type="checkbox" id="continuous" checked>[text:field_media_continuous_play]</label>

<label for="seek"> Posi&ccedil;&atilde;o </label>
<input type="range" id="seek" min="0" max="100" step="1" value="0">

<br>
<label for="volume"> Volume </label>
<input type="range" id="volume" min="0" max="100" step="10" value="30">

<hr>
<button onclick="window.close()">[text:action_close]</button>
[}]
[style]
/* align window elements */
BODY { text-align:center; }
[/style]
[cut:headerlinks]
<script src="[shared:libraries/ecolabore-player.js]"></script>
[/cut]
'
