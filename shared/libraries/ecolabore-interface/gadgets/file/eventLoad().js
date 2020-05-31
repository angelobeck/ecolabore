
me.eventLoad = function (){
me.buttonOpen = document.getElementById("action-open");
me.controlChoose = document.getElementById("upload");
// me.controlChoose.addEventListener ("input", me.eventInput);

me.buttonUpload = document.getElementById("action-upload");
me.buttonUpload.addEventListener ("click", me.actionUpload);

me.buttonDownload = document.getElementById("action-download");
};

window.addEventListener ("load", me.eventLoad);
