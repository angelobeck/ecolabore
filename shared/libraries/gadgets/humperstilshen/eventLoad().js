
me.eventLoad = function (){
me.baloom = document.getElementById ("layout_baloom");

var alert = me.baloom.getElementsByClassName ("ecl-humperstilshen alert");

if (alert.length)
me.actionOpen (alert[0].id.substr(15));
};

window.addEventListener ("load", me.eventLoad);
