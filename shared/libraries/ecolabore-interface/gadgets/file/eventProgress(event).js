
me.eventProgress = function (event){
if(event.lengthComputable)
gadgets.message.progress (event.loaded / event.total);
};
