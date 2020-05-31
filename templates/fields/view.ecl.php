'text'={
'caption'={
'pt'={
1='Ver valor do campo'
2=1
}
'en'={
1='View field value'
}
}
}
'html'='<div class="form-control">
<div [lang] class="form-col-label label">[text]</div>
<div class="form-col-input">
[if($url){
`<a href="`; $url; `">`; text($content); `</a>`; nl;
}else{
text($content);
}]
</div>
</div>
'
