
me.next = function (){
if (!me.queue[me.index]){
me.queue = [];
me.index = 0;
me.loading = false;
return;
}

var request = new XMLHttpRequest();
request.onreadystatechange = function() {
if (this.readyState == 4) {
if (this.status != 200){
if (me.queue[me.index].error)
me.queue[me.index].error();
me.index ++;
me.next();
return;
}

var answer = JSON.parse(this.responseText);
if (!answer){
if (me.queue[me.index].error)
me.queue[me.index].error();
me.index++;
me.next();
return;
}

if (me.queue[me.index].callback)
me.queue[me.index].callback(answer);
me.index ++;
me.next();
return;
}
}

if (me.queue[me.index].data){
request.open("POST", me.queue[me.index].url, true);
request.send (JSON.stringify (me.queue[me.index].data));
}else{
request.open("GET", me.queue[me.index].url, true);
request.send ();
}
};