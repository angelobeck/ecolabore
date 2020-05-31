
get next(){
if(this.parent.nodes.length > this.index + 1)
return this.parent.nodes[this.index + 1];
else
return false;
};
