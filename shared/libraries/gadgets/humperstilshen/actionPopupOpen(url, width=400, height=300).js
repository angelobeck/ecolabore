
me.actionPopupOpen = function (url, width=480, height=300){
me.actionClose();

var left = (screen.width) ? (screen.width - width)/2 : (800 - width) / 2;
var top = (screen.height) ? (screen.height - height)/2 : (600 - height) / 2;
window.open (url, "", "resizable,scrollbars,dependent,chrome,width=" + width + ",height=" + height + ",top="+top+",left="+left);
};
