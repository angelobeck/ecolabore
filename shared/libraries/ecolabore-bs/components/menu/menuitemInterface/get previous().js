
get previous(){
if(this.index > 0)
return this.parent.nodes[this.index - 1];

return false;
};
