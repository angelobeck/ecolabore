
me.eventLoad = function(){
me.layout = document.getElementById ("layout");
me.element = document.getElementById ("dialogs");
me.dialogsById = {};
var children = document.getElementsByClassName ("dialog");
for (var i = 0; i < children.length; i++){
var child = children[i];
if (child.id && child.id.length > 0)
me.dialogsById[child.id] = child;
}

};

window.addEventListener ("load", me.eventLoad);