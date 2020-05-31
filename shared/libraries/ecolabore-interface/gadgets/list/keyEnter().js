
me.keyEnter = function (){
if (!me.nodeInFocus)
return;

var data = me.nodeInFocus.data;
if (!data.type)
return;

switch (data.type){

case 'dir':
if (data.location)
gadgets.tree.actionGoLocation (data.location);
break;

case 'message':
gadgets.editor.load = data.location;
interface.popup.action = interface.url.message;
interface.popup.element.focus();
break;

case 'audio':
if (data.location)
interface.audio.play (data.location);
break;

case 'text':
gadgets.editor.load = data.location;
interface.popup.action = interface.url.editor;
interface.popup.element.focus();
break;

}
};
