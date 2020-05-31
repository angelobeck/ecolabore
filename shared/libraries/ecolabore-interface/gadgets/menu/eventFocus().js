
me.eventFocus = function (){
if (!me.nodeInFocus){
if (!me.firstNode)
return;

me.nodeInFocus = me.firstNode;
}

me.nodeInFocus.element.className = "focus";
me.hasFocus = true;

me.nodeCheckActions (me);
};
