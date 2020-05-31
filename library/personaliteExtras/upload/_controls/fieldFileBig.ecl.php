'html'='
<tr><td>
<label for="[$name]"[inline_lang]>[text]</label>
</td><td[if!$help{] colspan="2"[}]>
<input type="file" id="[$name]" name="[$name]"[if($accept){ ` accept="`; $accept; `"`; }] class="input">
<br>
<button onclick="uploadBigFile(); return false">[text:action_submit]</button>
<button onclick="window.close(); return false">[text:action_cancel]</button>
[if $help{ `</td><td>`; nl; help(`form`); }]
</td></tr>
<tr><td colspan=3>
<progress id="[$name]_progressbar" value="0" max="100"></progress>
<div id="[$name]_progressvalue">0%</div> 
</tr></td>
[cut:script upload_big_file]

function uploadProgress (event)
{ // uploadProgress
if(event.lengthComputable){
var percent = Math.round(event.loaded * 100 / event.total);
document.getElementById("[$name]_progressbar").value = percent;
document.getElementById("[$name]_progressvalue").innerHTML = percent + "%";
}
} // uploadProgress

function uploadBigFile ()
{ // uploadBigFile
var request = new XMLHttpRequest();
request.onreadystatechange = function(){
if(request.readyState == 4){
setTimeout(function(){
window.opener.humperstilshen.refresh();
window.close();
}, 500);
}
};

request.upload.addEventListener("progress", uploadProgress, false);

var formData = new FormData();
formData.append ("file", document.getElementById("[$name]").files[]0]);
request.open("POST", "[$url]");
request.send(formData);

return false;
} // uploadBigFile

[/cut]
'
