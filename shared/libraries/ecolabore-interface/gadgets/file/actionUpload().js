
me.actionUpload = function (){
if (!me.controlChoose.files.length)
return;

var request = new XMLHttpRequest();
request.onreadystatechange = function(){
if(request.readyState == 4){
actions.treeRefreshCurrent.exec();
if (this.responseText)
gadgets.message.replaceText (this.responseText);
gadgets.message.progress (0);
me.controlChoose.value = '';
}
};

request.upload.addEventListener("progress", me.eventProgress);

var formData = new FormData();
for (var i = 0; i < me.controlChoose.files.length; i++)
{
formData.append ("file_" + i, me.controlChoose.files[i]);
}

var param = btoa (gadgets.address.element.value);
param = param.replace(/[=]/g, "");
request.open("POST", interface.url.upload + "/_location-" + param);
request.send(formData);
};
