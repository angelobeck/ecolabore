
set selected(selection){
if (selection == "false" || selection == false){
this.element.setAttribute ("aria-selected", false);
}else{
this.element.setAttribute ("aria-selected", true);
}
};
