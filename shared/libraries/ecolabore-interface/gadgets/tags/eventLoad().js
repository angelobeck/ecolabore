
me.eventLoad = function (){


if(window.opener && window.opener.ecolabore && window.opener.ecolabore.gadgets && window.opener.ecolabore.gadgets.tags && window.opener.ecolabore.gadgets.tags.loaded){
me.children = window.opener.ecolabore.gadgets.tags.children;
me.loaded = true;
return;
}

if (!interface.url.endpoint || interface.url.endpoint == "")
return;

var command = { "command":"tags_load" };
var request = new XMLHttpRequest();
request.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
me.loaded = true;
var data = JSON.parse(this.responseText);
if (!data)
return;

if (data.children)
me.children = data.children;
}
}
request.open("POST", interface.url.endpoint, true);
request.send (JSON.stringify (command));
};

window.addEventListener ("load", me.eventLoad);
