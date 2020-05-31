<?php

class eclFilter_section_specialFields
{ // class eclFilter_section_specialFields

static function create ($fieldName, $control, $formulary)
{ // function create
global $store;

if (!isset ($formulary->data['flags']['section_type']))
return;

if (!isset ($control['flags']['sufix']))
return;

$special = $store->control->read ('section' . ucfirst ($formulary->data['flags']['section_type']) . $control['flags']['sufix']);

if (isset ($special['children']))
$formulary->insertControlChildren ($special);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $store;

if (!isset ($formulary->data['flags']['section_type']))
return;

if (!isset ($control['flags']['sufix']))
return;

$special = $store->control->read ('section' . ucfirst ($formulary->data['flags']['section_type']) . $control['flags']['sufix']);

if (isset ($special['children']))
$formulary->insertControlChildren ($special);
} // function save

} // class eclFilter_section_specialFields

?>