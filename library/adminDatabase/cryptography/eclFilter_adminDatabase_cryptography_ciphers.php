<?php

class eclFilter_adminDatabase_cryptography_ciphers
{ // class eclFilter_adminDatabase_cryptography_ciphers

static function create ($fieldName, $control, $formulary)
{ // function create
$document = $formulary->document;

$item = $formulary->appendChild ($control);

// name
$item->data['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$item->data['type'] = $control['flags']['type'];
else
$item->data['type'] = 'select';

// target
if (!isset ($control['flags']['target']))
return;

$target = $control['flags']['target'];

if (defined ($target))
$value = constant ($target);
elseif (isset ($control['flags']['default_value']))
$value = $control['flags']['default_value'];
else
$value = '';

foreach (openssl_get_cipher_methods() as $cipher)
{ // each algorithm
$item->appendChild (array (
'value' => $cipher, 
'caption' => $document->textMerge ($cipher), 
'active' => $cipher == $value, 
));
} // each algorithm
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $io;

if (!isset ($formulary->received[$fieldName]))
return;

$value = $formulary->received[$fieldName];

// target
if (isset ($control['flags']['target']))

$io->systemConstants->set ($control['flags']['target'], $value);
} // function save

} // class eclFilter_adminDatabase_cryptography_ciphers

?>