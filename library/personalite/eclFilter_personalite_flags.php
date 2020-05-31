<?php

class eclFilter_personalite_flags
{ // class eclFilter_personalite_flags

static function create ($fieldName, $control, $formulary)
{ // function create
$local['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$local['type'] = $control['flags']['type'];
else
$local['type'] = 'textarea';

if (isset ($formulary->data['flags']))
$local['value'] = $formulary->htmlSpecialChars (eclIo_file::array2string ($formulary->data['flags']));
$formulary->appendChild ($control, $local);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
if (isset ($formulary->received[$fieldName][0]))
$formulary->data['flags'] = eclIo_file::string2array ($formulary->received[$fieldName]);
else
$formulary->data['flags'] = [];
} // function save

} // class eclFilter_personalite_flags

?>