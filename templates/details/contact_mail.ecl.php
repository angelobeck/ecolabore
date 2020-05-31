'local'={
'filters'='contactMail'
}
'text'={
'caption'={
'pt'={
1='Email'
2=1
}
'en'={
1='Email'
}
}
}
'html'='[if $mail{]<div>
<a href="mailto:[$mail]">[$mail]</a>
</div>
[}'
