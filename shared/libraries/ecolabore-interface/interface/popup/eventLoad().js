
me.eventLoad = function(){

me.element = document.getElementById("action-open");
me.element.onclick = function () {
window.open (me.action, '', 'location=no, menubar=no, personalbar=no, status=yes, toolbar=no');
};


};

window.addEventListener ("load", me.eventLoad);