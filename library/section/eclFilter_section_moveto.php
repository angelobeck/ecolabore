<?php

class eclFilter_section_moveto
{ // class eclFilter_section_moveto

static function create ($fieldName, $control, $formulary)
{ // function create
global $store;

$control['type'] = 'list';
$control['name'] = $fieldName;

$parentId = $formulary->data['parent_id'];
$id = $formulary->data['id'];

$item = $formulary->appendChild ($control);

// Main menu
$item->appendChild ('section_moveToMain')
->set ('value', '0')
->active ($parentId == 0);

// Footer menu
$item->appendChild ('section_moveToFooter')
->set ('value', '1')
->active ($parentId == 1);

foreach ($store->domainContent->mode ($formulary->data['domain_id'], MODE_SECTION) as $data)
{ // each section
if ($data['id'] == $id)
continue;

if (isset ($data['flags']['section_type']) and ($data['flags']['section_type'] == 'folder' or $data['flags']['section_type'] == 'menu'))
$item->appendChild ($data)
->set ('value', $data['id'])
->active ($data['id'] == $parentId);
} // each section
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $store;

if (!isset ($formulary->received[$fieldName]))
return;

$id = strval ($formulary->received[$fieldName]);

if ($id == 0 or $id == 1)
return $formulary->data['parent_id'] = $id;

$data = $store->domainContent->openById ($formulary->data['domain_id'], $id);
if (!$data or $data['mode'] != MODE_SECTION or !isset ($data['flags']['section_type']))
return;

if ($data['flags']['section_type'] == 'folder' or $data['flags']['section_type'] == 'menu')
$formulary->data['parent_id'] = $id;
} // function save

} // class eclFilter_section_moveto

?>