
get selected(){
var selection = this.element.getAttribute ("aria-selected");
if (selection == "false" || selection == false)
return false;

return true;
};
