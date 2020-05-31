<?php

/*
* Valid control flags
* control_type = select | select_list | radio_group
* control_filter = options
* control_target
* control_field_name
* control_help
* control_help_msg
* control_value_cast string | int
* control_default_value
* control_mask_values
* control_clear
*/

class eclFilter_options
{ // class eclFilter_options

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

// help
if (isset ($control['flags']['help']) and !isset ($control['flags']['help_msg']))
$item->data['help_msg'] = 'system_filterSelectHelp';

// target
if (!isset ($control['flags']['target']))
return;

$value = $formulary->getField ($control['flags']['target']);

// Default value
if ($value === false and isset ($control['flags']['default_value']))
$value = $control['flags']['default_value'];

// options
if (isset ($control['options']))
{ // options exists
foreach ($control['options'] as $option)
{ // each option
$data = [];
$data['text']['caption'] = $document->textMerge ($option);
if ($option == '')
$child = $item->appendChild ('personaliteModules_000auto');
else
$child = $item->appendChild ($data);

$child->data['value'] = $option;
if ($option == $value)
$child->data['active'] = 1;

$child->data['name'] = $fieldName;
} // each child
} // children exists
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $store;

if (isset ($formulary->received[$fieldName]))
$value = trim ($formulary->received[$fieldName], ' ');
else
$value = false;
$found = false;

if (isset ($control['options']))
{ // children exists
foreach ($control['options'] as $option)
{ // each option
if ($value == $option)
{ // value found
$found = $option;
break;
} // value found
continue;
} // each child
} // children exists

$value = $found;

if ($value === false and isset ($control['flags']['default_value']))
$value = $control['flags']['default_value'];

// Clear default value
if (isset ($control['flags']['default_value']) and isset ($control['flags']['clear']) and $value === $control['flags']['default_value'])
$value = false;

// cast
if (isset ($control['flags']['cast']) and $control['flags']['cast'] == 'int')
settype ($value, 'int');

// clear
if (!isset ($control['flags']['default_value']) and isset ($control['flags']['clear']) and !$value)
$value = false;

if ($value === false and isset ($control['flags']['required']))
{ // required field
if (isset ($control['flags']['error_msg']))
return $formulary->setRequiredMsg ($control, $fieldName, $control['flags']['error_msg']);

return $formulary->setRequiredMsg ($control, $fieldName);
} // required field

// value cast
if ($value !== false and isset ($control['flags']['value_cast']) and $control['flags']['value_cast'] == 'int')
$value = intval ($value);

// target
if (isset ($control['flags']['target']))
$formulary->setField ($control['flags']['target'], $value);
} // function save

} // class eclFilter_options

?>