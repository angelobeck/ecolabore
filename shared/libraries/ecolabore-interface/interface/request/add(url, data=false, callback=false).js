
me.add = function (url, data=false, callback=false){
me.queue[me.queue.length] = { "url":url, "data":data, "callback":callback, "request":false };
if (me.loading)
return;

me.next();
};
