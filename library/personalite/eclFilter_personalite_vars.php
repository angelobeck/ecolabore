<?php

class eclFilter_personalite_vars
{ // class eclFilter_personalite_vars

static function create ($fieldName, $control, $formulary)
{ // function create
$local['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$local['type'] = $control['flags']['type'];
else
$local['type'] = 'textarea';

$buffer = CRLF;
if (isset ($formulary->data['local']))
{ // format output
foreach ($formulary->data['local'] as $key => $value)
{ // each variable
$buffer .= '$' . $key . ':' . $value . ';' . CRLF;
} // each variable
} // format output

$local['value'] = $formulary->htmlSpecialChars ($buffer);
$formulary->appendChild ($control, $local);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
if (isset ($formulary->received[$fieldName][0]))
$buffer = $formulary->received[$fieldName];
else
return $formulary->data['local'] = [];

$local = [];
foreach (explode (";", $buffer) as $line)
{ // each line
$value = '';
$parts = explode (':', $line, 2);
switch (count ($parts))
{ // switch parts
case 2:
$value = trim ($parts[1]);

case 1:
$key = substr (trim ($parts[0]), 1);
if (!is_string ($key) or !strlen ($key))
continue 2;

$local[$key] = $value;
} // switch parts
} // each line

$formulary->data['local'] = $local;
} // function save

} // class eclFilter_personalite_vars

?>