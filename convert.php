<?php

// $string = 'ÁAáaÀAàaÂAâaÃAãaÄAäaÅAåaÇCçcÉEéeÈEèeÊEêeËEëeÍIíiÌIìiÎIîiÏIïiÑNñnÓOóoÒOòoÔOôoÕOõoÖOöoÚUúuÙUùuÛUûuÜUüuÝYýyÿy';
// $string = 'AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz';
$string = '__';

$iso = mb_convert_encoding($string, 'ISO-8859-1', 'UTF-8');
$ordenar = [];
for($i = 0; $i < strlen($iso); $i += 2) {
    $ordenar[ord($iso[$i])] = ord($iso[$i + 1]);
}

ksort($ordenar);
foreach($ordenar as $key => $value) {
    // print $key . ' => ' . $value . ', // &' . mb_convert_encoding(chr($key), 'UTF-8', 'ISO-8859-1') . '; => ' . chr($value) .  '<br>';
    print $key . ' => ' . $value . ', // &' . chr($key) . '; => ' . chr($value) .  '<br>';
}
