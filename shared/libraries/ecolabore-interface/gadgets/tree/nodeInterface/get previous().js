
get previous(){
if(this.index > 0)
return this.parent.children[this.index - 1];

return false;
};
