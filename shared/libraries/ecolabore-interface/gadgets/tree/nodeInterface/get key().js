
get key(){
if (this.data.key)
return this.data.key;

return this.element.innerText.substr (0, 1).toLower();
};