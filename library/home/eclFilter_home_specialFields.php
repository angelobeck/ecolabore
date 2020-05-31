<?php

class eclFilter_home_specialFields
{ // class eclFilter_home_specialFields

static function create ($fieldName, $control, $formulary)
{ // function create
global $store;

if (!isset ($formulary->data['flags']['home_type']))
return;

if (!isset ($control['flags']['sufix']))
return;

$special = $store->control->read ('home' . ucfirst ($formulary->data['flags']['home_type']) . $control['flags']['sufix']);

if (isset ($special['children']))
$formulary->insertControlChildren ($special);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $store;

if (!isset ($formulary->data['flags']['home_type']))
return;

if (!isset ($control['flags']['sufix']))
return;

$special = $store->control->read ('home' . ucfirst ($formulary->data['flags']['home_type']) . $control['flags']['sufix']);

if (isset ($special['children']))
$formulary->insertControlChildren ($special);
} // function save

} // class eclFilter_home_specialFields

?>