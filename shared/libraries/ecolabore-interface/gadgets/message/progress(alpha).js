
me.progress = function (alpha){
var percent = Math.round (alpha * 100);
me.progressBar.value = percent;
me.progressStatus.innerHTML = percent + "%";
};