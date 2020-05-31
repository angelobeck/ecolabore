
me.eventLoad = function (){
var elements = document.getElementsByClassName("ecl-listbox");

for (var i = 0; i < elements.length; i++){
	element = elements[i];
	if(!element.id)
	continue;
	
	gadgets[element.id] = new listInterface (element);
}

};

window.addEventListener ("load", me.eventLoad);
