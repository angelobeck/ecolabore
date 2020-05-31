
constructor (element){
element.setAttribute ("tabindex", 0);
element.setAttribute ("role", "menu");
element.addEventListener ("keydown", this.eventKeyDown);
this.element = element;
this.elementInFocus = false;
};
