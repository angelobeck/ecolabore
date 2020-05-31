
// scrollHeight retorna a altura completa do elemento
// clientHeight retorna a área visível do elemento
// scrollTop configura ou retorna o quanto o elemento foi rolado verticalmente

me.nodeScrollIntoView = function (){
element = me.nodeInFocus.element;
var totalHeight = me.element.scrollHeight;
var scrolled = me.scrolled;
var visibleHeight = me.element.clientHeight;
if (visibleHeight == totalHeight)
return;

var elementTopOffset  = 0;

for (var i = 0; i < me.element.children.length; i++){
var currentElement = me.element.children[i];
if (currentElement === element)
break;

elementTopOffset  += currentElement.clientHeight;
}
elementBottomOffset = elementTopOffset + element.clientHeight;

if (elementTopOffset < scrolled){
me.element.scrollTop = elementTopOffset;
return;
}

if (elementBottomOffset > visibleHeight + scrolled){
var difToScroll = elementBottomOffset - (visibleHeight + scrolled);
me.element.scrollTop = scrolled + difToScroll;
return;
}

me.element.scrollTop = scrolled;
};
