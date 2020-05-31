<?php

class eclFilter_adminDomains_selectLang
{ // class eclFilter_adminDomains_selectLang

static function create ($fieldName, $control, $formulary)
{ // function create
global $store;
$document = $formulary->document;

if (!isset ($control['flags']['target']))
return;

$item = $formulary->appendChild ($control);

// name
$item->data['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$item->data['type'] = $control['flags']['type'];
else
$item->data['type'] = 'select';

if (isset ($formulary->data['flags']['default_lang']))
$value = $formulary->data['flags']['default_lang'];
elseif (defined ('SYSTEM_DEFAULT_LANGUAGE'))
$value = SYSTEM_DEFAULT_LANGUAGE;
else
$value = 'en';

$names = $store->control->scandir ('t', 'labels/lang');
foreach ($names as $lang)
{ // each lang
$data = $store->control->read ('labels/lang/' . $lang);
$item->appendChild ($data, array ('value' => $lang))
->active ($lang == $value);
} // each lang
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $store;

if (!isset ($formulary->received[$fieldName][0]))
return;

$formulary->data['flags']['default_lang'] = $formulary->received[$fieldName];
} // function save

} // class eclFilter_adminDomains_selectLang

?>