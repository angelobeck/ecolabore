
get nextVisible(){

if (!this.group.hidden && this.children.length > 0)
return this.children[0];

if (this.next)
return this.next;

var node = this;

while (true){
node = node.parent;
if (node === me.firstNode)
return false;

if (node.next)
return node.next;
}

};
