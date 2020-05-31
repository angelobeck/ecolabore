
function humperstilshenClass()
{ // humperstilshenClass
this.baloom = document.getElementById('layout_baloom');
this.dialogs = [];
this.menus = [];
this.panels = [];
this.onLoad = false;
this.loadWait = false;
this.onAlert = false;
this.onMenuOpen = false;
this.msgIconOpen = 'Click here to opens the assistant baloom.';
this.msgIconClose = 'The assistant baloom is opened. Use down arrow to  Browse your content.';

var shuffleds = document.getElementsByClassName ("humperstilshen-shuffled");
for (var i = 0; i < shuffleds.length; i++)
{ // each shuffled
if (shuffleds[i].dataset.shuffled)
shuffleds[i].innerHTML = atob(shuffleds[i].dataset.shuffled);
} // each shuffled

this.refresh = function ()
{ // refresh
document.refresh.submit();
} // refresh

this.dialogAdd = function (name){
this.dialogs[this.dialogs.length] = name;
}

this.panelAdd = function (name){
this.panels[this.panels.length] = name;
}

this.dialogOpen = function (name){
for (var i in this.dialogs){
childName = this.dialogs[i];
myDialog = document.getElementById('humperstilshen_'+childName);
if (childName == name)
{
if (myDialog.hidden)
{
myDialog.hidden = false;
this.help ('open', name);
}else{
myDialog.hidden = true;
this.help ('close');
}
}
else
myDialog.hidden = true;
}
document.getElementById ('humperstilshen').focus();
}

this.dialogClose = function (id=false)
{
for (var i in this.dialogs){
childName = this.dialogs[i];
document.getElementById('humperstilshen_'+childName).hidden = true;
}
this.help ('close');
if (id && id != '' && document.getElementById (id))
document.getElementById (id).focus();
else
document.getElementById ('humperstilshen').focus();
return false;
}

this.panelToggle = function (name=''){
this.dialogClose();
this.menuToggle();
for (var i = 0; i < this.panels.length; i++){
var id = this.panels[i];
var panel = document.getElementById('panel-' + id + '-details');
var button = document.getElementById('panel-' + id);

if (name == id)
button.focus();

if (name == id && panel.hidden){
button.setAttribute ("aria-expanded", true);
panel.hidden = false;
}else{
button.setAttribute ("aria-expanded", false);
panel.hidden = true;
}
}
}

this.help = function (caption, name=false)
{ // help
if (caption == 'open')
{ // open
document.getElementById ('humperstilshen_accessibility_label').alt = this.msgIconClose;
this.baloom.hidden = false;
if (this.onAlert !== false)
{ // onAlert
this.onAlert();
this.onAlert = false;
this.loadWait = true;
} // onAlert
else if (this.onMenuOpen !== false)
{ // onMenuOpen
this.onMenuOpen();
this.onMenuOpen = false;
} // onMenuOpen
} // open
else
{ // close
document.getElementById ('humperstilshen_accessibility_label').alt = this.msgIconOpen;
this.baloom.hidden = true;
if (this.onLoad !== false)
{ // onLoad
this.onLoad();
this.onLoad = false;
} // onLoad
} // close
} // help

this.popUpOpen = function (url, width=300, height=480, id=false){
var left = (screen.width) ? (screen.width - width)/2 : (800 - width) / 2;
var top = (screen.height) ? (screen.height - height)/2 : (600 - height) / 2;
window.open (url, '', 'location=no, menubar=no, personalbar=no, status=no, toolbar=no, width=500, height=270, top='+top+', left='+left);
this.dialogClose(id);
}

this.confirm = function (ref){
location.replace(ref);
}

this.audio = false;
this.audioLoaded = false;
this.lastAudioButton = false;

this.play = function (uri, target=false)
{ // play

if (this.lastAudioButton && this.lastAudioButton.dataset.play)
this.lastAudioButton.innerHTML = this.lastAudioButton.dataset.play;
this.lastAudioButton = target;

if(!this.audio){
this.audio = document.createElement("audio");
document.body.appendChild (this.audio);
}

if(!this.audioLoaded || this.audioLoaded != uri){
this.audioLoaded = uri;
this.audio.src = uri;
this.audio.load();
this.audio.play();
this.audio.onended = function (){
if (humperstilshen.lastAudioButton && humperstilshen.lastAudioButton.dataset.play)
humperstilshen.lastAudioButton.innerHTML = humperstilshen.lastAudioButton.dataset.play;
if (humperstilshen.playNext)
humperstilshen.playNext();
}
if (target && target.dataset.pause)
target.innerHTML = target.dataset.pause;
return;
}

if(this.audio.readyState < 2)
return;

if(this.audio.paused || this.audio.ended){
this.audio.play();

if (target && target.dataset.pause)
target.innerHTML = target.dataset.pause;
return;
}

this.audio.pause();
if (target && target.dataset.play)
target.innerHTML = target.dataset.play;
} // play

this.menuAdd = function (id)
{ // menuAdd
this.menus[this.menus.length] = id;
} // menuAdd

this.menuToggle = function (id='')
{ // menuToggle
for (var i = 0; i < this.menus.length; i++)
{ // each menu
var currentId = this.menus[i];
var menu = document.getElementById (currentId + '_details');
if (currentId == id){
var alt = document.getElementById (id + '_alt');
if (menu.hidden){
menu.hidden = false;
alt.innerHTML = this.msgExpanded;
}else{
menu.hidden = true;
alt.innerHTML = this.msgColapsed;
document.getElementById (id).focus();
}
}else{
if (!menu.hidden){
menu.hidden = true;
document.getElementById (currentId + '_alt').innerHTML = this.msgColapsed;
}
}
} // each menu
} // menuToggle

this.sessionTTL = 1800;

this.sessionCheck = function ()
{ // sessionCheck
this.sessionRemainingTime = 0;

var request = new XMLHttpRequest();
request.onreadystatechange = function(){
if(request.readyState == 4){
humperstilshen.sessionRemainingTime = parseInt (this.responseText);
if (humperstilshen.sessionCheckTimer){
clearTimeout (humperstilshen.sessionCheckTimer);
humperstilshen.sessionCheckTimeout(); 
}
}
};
request.open("GET", this.sessionCheckURL);
request.send();

if (this.sessionCheckTimer)
clearTimeout (this.sessionCheckTimer);
this.sessionCheckTimer = setTimeout (function (){ humperstilshen.sessionCheckTimeout(); }, (humperstilshen.sessionTTL - 180) * 1000);
} // sessionCheck

this.sessionCheckTimeout = function ()
{ // sessionCheckTimeout
if (this.sessionRemainingTime == 0)
return this.refresh();

if (this.sessionRemainingTime > 200)
return setTimeout (function (){  }, (this.sessionRemainingTime - 180) * 1000);

this.sessionAsk();
} // sessionCheckTimeout

this.sessionAsk = function ()
{ // sessionAsk
document.getElementById ("humperstilshen_session_progress").value = 0;
this.dialogOpen (this.sessionDialog);
if (this.sessionProgressTimer)
clearTimeout (this.sessionProgressTimer);
this.sessionProgressTimer = setTimeout (function (){ humperstilshen.sessionProgressUpdate(); }, 1000);
} // sessionAsk

this.sessionProgressUpdate = function ()
{ // sessionProgressUpdate
var progress = document.getElementById ("humperstilshen_session_progress");
if (progress.value == 100)
return this.sessionProgressEnded ();

progress.value ++;
this.sessionProgressTimer = setTimeout (function (){ humperstilshen.sessionProgressUpdate(); }, 1000);
} // sessionProgressUpdate

this.sessionProgressEnded = function ()
{ // sessionProgressEnded
this.dialogClose();
this.sessionProgressTimer = null;
setTimeout (function (){ humperstilshen.sessionCheck(); }, 10 * 1000);
} // sessionProgressEnded

this.sessionRefresh = function ()
{ // sessionRefresh
var request = new XMLHttpRequest();
request.onreadystatechange = function(){
if(request.readyState == 4){
var sessionRemainingTime = (parseInt (this.responseText) - 180) * 1000;
setTimeout (function (){ humperstilshen.sessionCheck(); }, sessionRemainingTime);
humperstilshen.sessionProgressTimer = null;
humperstilshen.dialogClose();
}
};
request.open("GET", this.sessionRefreshURL);
request.send();
} // sessionRefresh

} // humperstilshenClass
