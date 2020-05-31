<?php

class eclFilter_submit
{ // class eclFilter_submit

static function create ($fieldName, $control, $formulary)
{ // function create
global $store;
$item = $formulary->document->createListItem ();

if (isset ($control['children']))
{ // children exists
foreach ($control['children'] as $name)
{ // each submit item
if ($name[0] == '_')
$name = 'system_form' . $name;
$data = $store->control->read ($name);
if (!isset ($data['flags']['field_name']))
continue;
if (!isset ($data['text']['caption']))
continue;
if ($data['flags']['field_name'] == 'clear_language' and (!isset ($formulary->data['text']['caption']) or count ($formulary->data['text']['caption']) == 1))
continue;
$local['name'] = $formulary->prefix . 'command_' . $data['flags']['field_name'];
$item->appendChild ($data, $local);
} // each submit item
} // children exists
else
{ // self submit
if (isset ($control['flags']['field_name']))
$item->appendChild ($control, array ('name' => $formulary->prefix . 'command_' . $control['flags']['field_name']));
else
$item->appendChild ($control);
} // self submit
$item->data['type'] = 'submit';
return $item;
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
} // function save

} // class eclFilter_submit

?>