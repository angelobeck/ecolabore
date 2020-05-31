
constructor (element){
element.setAttribute ("tabindex", 0);
element.setAttribute ("role", "listbox");
element.addEventListener ("keydown", this.eventKeyDown);
this.element = element;
};
