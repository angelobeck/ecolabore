<?php

/*
* Valid control flags:
* control_type = date
* control_filter = timestamp
* control_target
* control_field_name
* control_help
* control_help_msg
* 
* The default value for this field is aways "now"
*/

class eclFilter_timestamp
{ // class eclFilter_timestamp

static function create ($fieldName, $control, $formulary)
{ // function create
global $io, $store;

$item = $formulary->appendChild ($control);

// name
$item->data['name'] = $fieldName;

// control_type
if (isset ($control['flags']['control_type']))
$item->data['type'] = $control['flags']['control_type'];
else
$item->data['type'] = 'date';

// help
if (isset ($control['flags']['control_help']) and !isset ($control['flags']['control_help_msg']))
$item->data['control_help_msg'] = 'systemFormulary_filterDateHelp';

// target
if (isset ($control['flags']['control_target']))
$value = $formulary->getField ($control['flags']['control_target']);

if (!$value)
$value = TIME;

$valueY = date ('Y', $value);
$valueM = date ('m', $value);
$valueD = date ('j', $value);
$valueH = date ('H', $value);
$valueI = date ('i', $value);
$valueI = intval ($valueI / 5) * 5;
$local = array ('name' => $fieldName);
$year = $item->appendChild (false, $local);
$month = $item->appendChild (false, $local);
$day = $item->appendChild (false, $local);
$hour = $item->appendChild (false, $local);
$minutes = $item->appendChild (false, $local);

for ($y = 2030; $y > 1969; $y--)
{ // each year
$local = array ('value' => $y);
if ($y == $valueY)
$local['active'] = 1;
$year->appendChild (false, $local);
} // each year

for ($m = 12; $m > 0; $m--)
{ // each month
$local = array ('value' => $m);
if ($m == $valueM)
$local['active'] = 1;
$month->appendChild ($store->control->read ('labels/date/month' . $m), $local);
} // each month

for ($d = 31; $d > 0; $d--)
{ // each day
$local = array ('value' => $d);
if ($d == $valueD)
$local['active'] = 1;
$day->appendChild (false, $local);
} // each day

for ($h = 23; $h >= 0; $h--)
{ // each hour
$value = str_pad ($h, '0', STR_PAD_LEFT);
$local = array ('value' => $value);
$hour->appendChild (false, $local)
->active ($h == $valueH);
} // each hour

for ($i = 55; $i >= 0; $i -= 5)
{ // each minute
$value = str_pad ($i, '0', STR_PAD_LEFT);
$local = array ('value' => $value);
if ($i == $valueI)
$local['active'] = 1;
$minutes->appendChild (false, $local);
} // each minute
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $io;
$received = $formulary->received;

if (
isset ($received[$fieldName . '_year']) and isset ($received[$fieldName . '_month']) and isset ($received[$fieldName . '_day'])
)
{ // received value
$y = intval ($received[$fieldName . '_year']);
$m = intval ($received[$fieldName . '_month']);
$d = intval ($received[$fieldName . '_day']);

isset ($received[$fieldName . '_hours']) ? $hours = intval ($received[$fieldName . '_hours']) : $hours = 0;
isset ($received[$fieldName . '_minutes']) ? $minutes = intval ($received[$fieldName . '_minutes']) : $minutes = 0;
$value = mktime ($hours, $minutes, 0, $m, $d, $y);
} // received value
else
$value = TIME;

// target
if (isset ($control['flags']['control_target']))
$formulary->setField ($control['flags']['control_target'], $value);
} // function save

} // class eclFilter_timestamp

?>