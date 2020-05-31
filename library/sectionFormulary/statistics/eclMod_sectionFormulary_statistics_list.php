<?php

class eclMod_sectionFormulary_statistics_list
{ // class eclMod_sectionFormulary_statistics_list

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;
$me = $document->application->parent;

$mod->data = $store->control->read ('sectionFormulary_statistics_list');

if (!isset ($me->data['extras']['formulary']))
return;

$children = [];
foreach ($store->domainContent->children ($me->domainId, MODE_FORM, $me->id) as $child)
{ // each child
if ($child['marker'] == 255)
continue;

$children[] = $child;
} // each child
if (!$children)
return;
$total = count ($children);

foreach ($me->data['extras']['formulary'] as $fieldName => $field)
{ // each field
switch ($field['filter'])
{ // switch filter
case 'checkbox':
$counter = 0;
foreach ($children as $child)
{ // each child
if (isset ($child['local']['form'][$fieldName]['value']) and $child['local']['form'][$fieldName]['value'])
$counter++;
} // each child

$item = $mod->appendChild ();
$item->data['caption'] = $field['caption'];
$item->data['units'] = strval ($counter);
$item->data['percent'] = strval (round ( ($counter / $total) * 100, 1));
break;

case 'select':
case 'radio':
$values = [];
foreach ($children as $child)
{ // each child
if (isset ($child['local']['form'][$fieldName]['value']))
{ // set value
if (isset ($values[$child['local']['form'][$fieldName]['value']]))
$values[$child['local']['form'][$fieldName]['value']]++;
else
$values[$child['local']['form'][$fieldName]['value']] = 1;
} // set value
} // each child

$select = $mod->appendChild ($field);
$select->data['header'] = 1;

foreach ($field['options'] as $value => $caption)
{ // each option
$option = $select->appendChild ();
$option->data['caption'] = $caption;
if (isset ($values[$value]))
{ // set values
$option->data['units'] = strval ($values[$value]);
$option->data['percent'] = strval (round ( ($values[$value] / $total) * 100, 1));
} // set values
else
{ // empty
$option->data['units'] = '0';
$option->data['percent'] = '0';
} // empty
} // each option
} // switch filter
} // each field

$mod->enabled = true;
} // function setModule

} // class eclMod_sectionFormulary_statistics_list

?>