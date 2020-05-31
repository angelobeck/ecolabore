<?php

class eclFilter_personaliteFields_phone
{ // class eclFilter_personaliteFields_phone

static function create ($fieldName, $control, $formulary)
{ // function create
$document = $formulary->document;

$control['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$control['type'] = $control['flags']['type'];
else
$control['type'] = 'phone';
$list = array ('country', 'area', 'number');

if (!isset ($formulary->data['phone']) and isset ($control['local']['share_user_data']) and isset ($formulary->data['share_user_data']))
{ // use user phone
foreach ($list as $item)
{ // each item
if (!isset ($document->user->data['local']['phone'][$item]))
continue;

$value = $document->user->data['local']['phone'][$item];
if ($document->charset != 'UTF-8')
$value = mb_convert_encoding ($value, $formulary->document->charset, 'UTF-8');

$control['phone_' . $item] = $formulary->htmlSpecialChars ($value);
} // each item
} // use user phone
else
{ // from formulary
foreach ($list as $item)
{ // each item
$value = $formulary->getField ('local/phone/' . $item);

if ($value === false)
continue;

$control['phone_' . $item] = $value;
} // each item
} // from formulary

$formulary->appendChild ($control);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
unset ($formulary->data['local']['phone']);

if (isset ($formulary->received[$fieldName . '_country'][0]))
{ // filter received country
$value = $formulary->received[$fieldName . '_country'];
if (!preg_match ('/^[+]?[0-9]+$/', $value))
$formulary->setErrorMsg ($control, $fieldName, 'personaliteFields_phone_alertInvalidCountryCode', $value);

$formulary->setField ('local/phone/country', $value);
} // received country

if (isset ($formulary->received[$fieldName . '_area'][0]))
{ // filter received area
$value = $formulary->received[$fieldName . '_area'];
if (!preg_match ('/^[0-9]+$/', $value))
$formulary->setErrorMsg ($control, $fieldName, 'personaliteFields_phone_alertInvalidAreaCode', $value);

$formulary->setField ('local/phone/area', $value);
} // received area

if (isset ($formulary->received[$fieldName . '_number'][0]))
{ // filter received number
$value = $formulary->received[$fieldName . '_number'];
if (!preg_match ('/^[0-9]+$/', $value))
$formulary->setErrorMsg ($control, $fieldName, 'personaliteFields_phone_alertInvalidNumber', $value);

$formulary->setField ('local/phone/number', $value);
} // received number
} // function save

static function view ($fieldName, $control, $formulary)
{ // function view
$document = $formulary->document;

// type
$control['type'] = 'view';

if (!isset ($formulary->data['local']['phone']) or !$formulary->data['local']['phone'])
return;

$tel = [];
if (isset ($formulary->data['local']['phone']['country']))
$tel[] = $formulary->data['local']['phone']['country'];
if (isset ($formulary->data['local']['phone']['area']))
$tel[] = $formulary->data['local']['phone']['area'];
if (isset ($formulary->data['local']['phone']['number']))
$tel[] = $formulary->data['local']['phone']['number'];

if (!$tel)
return;

$control['content'] = $document->textMerge (implode (' ', $tel));
$control['url'] = 'tel:' . implode ('-', $tel);
$formulary->appendChild ($control);
} // function view

static function column ($document, $data, $name, $field, $url)
{ // function column
if (!isset ($formulary->data['local']['phone']))
return array ('caption' => $document->textMerge ('-'));

if (isset ($formulary->data['local']['phone']['country']))
$tel[] = $formulary->data['local']['phone']['country'];
if (isset ($formulary->data['local']['phone']['area']))
$tel[] = $formulary->data['local']['phone']['area'];
if (isset ($formulary->data['local']['phone']['number']))
$tel[] = $formulary->data['local']['phone']['number'];

return array (
'caption' => $document->textMerge (implode (' ', $tel)), 
'url' => 'tel:' . implode ('-', $tel)
);
} // function column

} // class eclFilter_personaliteFields_phone

?>