'local'={
'filters'='contactAddress'
}
'text'={
'caption'={
'pt'={
1='Endereço'
2=1
}
'en'={
1='Address'
}
}
}
'html'='[scope(`address`){
if($street){ `<p>`; $street; if($number){ `, `; $number; } ` `; $complement; `</p>`; nl; }
if($district){ `<p>`; $district; `</p>`; nl; }
if($city or $state){ `<p>`; $city; ` - `; $state; ` - `; $postal_code; `</p>`; nl; }
}'
