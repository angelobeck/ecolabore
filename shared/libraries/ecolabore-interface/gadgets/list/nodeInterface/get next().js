
get next(){
if(this.parent.children.length > this.index + 1)
return this.parent.children[this.index + 1];
else
return false;
};
