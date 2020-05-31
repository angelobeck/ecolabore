<?php

/*
* control_type = font
* control_filter = font
* control_field_name
* control_target
* font-family-enabled = 1 / 0
* font-weight-enabled, font-size-enabled, line-height-enabled
* 
* This filter aways clears empty fields
*/

class eclFilter_personaliteApearance_font
{ // class eclFilter_personaliteApearance_font

static function create ($fieldName, $control, $formulary)
{ // function create
$local['url'] = $formulary->document->url (array ($formulary->document->domain->name, '-personalite', 'apearance', 'font'));
// name
$local['name'] = $fieldName;

// Type
$local['type'] = 'font_testable';

// help
if (isset ($control['flags']['help']) and !isset ($control['flags']['help_msg']))
$local['help_msg'] = 'system_msg_filterFontHelp';

// target
if (!isset ($control['flags']['target']))
return;

$target = $control['flags']['target'];

foreach (array ('font-name', 'font-weight', 'font-size', 'line-height') as $property)
{ // each property
if (!isset ($control['flags'][$property . '-enable']))
{ // disabled
$local[$property . '-value'] = 'disabled';
continue;
} // disabled

$local[$property . '-enabled'] = 1;
$local[$property . '-value'] = str_replace (QUOT, TIC, $formulary->getField ('local/' . $target . '-' . $property));

// default
$default = str_replace (QUOT, TIC, $formulary->getField ('defaults/' . $target . '-' . $property));
if (isset ($default[0]) and $default[0] == '$')
$local[$property . '-from'] = str_replace ('-', '_', substr ($default, 1));
else
$local[$property . '-default'] = $default;

// Current
if (isset ($local[$property . '-value']))
$local[$property . '-current'] = $local[$property . '-value'];
elseif (isset ($local[$property . '-default']))
$local[$property . '-current'] = $local[$property . '-default'];
} // each property

if (isset ($local['font-name-value']))
{ // font family
$font = $formulary->document->render->block ('fonts/' . $local['font-name-value']);
if (isset ($font['local']['font-stack']))
$local['font-family-value'] = $font['local']['font-stack'];
} // font family

// class
if (isset ($control['flags']['field_name']))
{ // class
$local['class'] = $control['flags']['field_name'];
$local['target'] = str_replace ('-', '_', $control['flags']['field_name']);
} // class

$formulary->appendChild ($control, $local);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
if (!isset ($control['flags']['target']))
return;

$received = $formulary->received;
$target = $control['flags']['target'];

foreach (array ('font-name', 'font-weight', 'font-size', 'line-height') as $property)
{ // each property
if (!isset ($control['flags'][$property . '-enable']))
continue;

$name = $fieldName . '_' . str_replace ('-', '_', $property);
if (isset ($received[$name][0]))
$formulary->setField ('local/' . $target . '-' . $property, $received[$name]);
else
$formulary->setField ('local/' . $target . '-' . $property, false);
} // each property
} // function save

} // class eclFilter_personaliteApearance_font

?>