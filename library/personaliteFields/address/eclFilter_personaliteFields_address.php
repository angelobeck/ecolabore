<?php

class eclFilter_personaliteFields_address
{ // class eclFilter_personaliteFields_address

static function create ($fieldName, $control, $formulary)
{ // function create
$document = $formulary->document;

$control['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$control['type'] = $control['flags']['type'];
else
$control['type'] = 'address';

$list = array ('street', 'number', 'complement', 'district', 'city', 'state', 'postal_code', 'country');

if (!isset ($formulary->data['local']['address']) and isset ($control['local']['share_user_data']) and isset ($formulary->data['share_user_data']))
{ // use user address
foreach ($list as $item)
{ // each item
if (!isset ($document->user->data['local']['address'][$item]))
continue;

$value = $document->user->data['local']['address'][$item];
if ($document->charset != 'UTF-8')
$value = mb_convert_encoding ($value, $formulary->document->charset, 'UTF-8');

$control['address_' . $item] = $formulary->htmlSpecialChars ($value);
} // each item
} // use user address
else
{ // from formulary
foreach ($list as $item)
{ // each item
$value = $formulary->getField ('local/address/' . $item);

if ($value === false)
continue;

if ($document->charset != 'UTF-8')
$value = mb_convert_encoding ($value, $formulary->document->charset, 'UTF-8');

$control['address_' . $item] = $formulary->htmlSpecialChars ($value);
} // each item
} // from formulary

$formulary->appendChild ($control);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
unset ($formulary->data['local']['address']);
$list = array ('street', 'number', 'complement', 'district', 'city', 'state', 'postal_code', 'country');
foreach ($list as $item)
{ // each item
if (!isset ($formulary->received[$fieldName . '_' . $item]))
continue;

$value = $formulary->received[$fieldName . '_' . $item];

if ($formulary->document->charset != 'UTF-8')
$value = mb_convert_encoding ($value, 'UTF-8', $formulary->document->charset);

$formulary->setField ('local/address/' . $item, $value);
} // each item
} // function save

static function view ($fieldName, $control, $formulary)
{ // function view
$document = $formulary->document;

if (!isset ($formulary->data['local']['address']))
return;

$enabled = false;
$list = array ('street', 'number', 'complement', 'district', 'city', 'state', 'postal_code', 'country');
foreach ($list as $field)
{ // verify each field
if (isset ($formulary->data['local']['address'][$field][0]))
{ // found
$enabled = true;
break;
} // found
} // verify each field
if (!$enabled)
return;

// type
if (isset ($control['flags']['view_type']))
$control['type'] = $control['flags']['view_type'];
else
$control['type'] = 'view';

$address = $formulary->data['local']['address'];
$list = array ('street', 'number', 'complement', 'district', 'city', 'state', 'postal_code', 'country');

$buffer = '';
$lines = [];
$line = [];

if (isset ($address['street'][0]))
$line[] = $address['street'];
if (isset ($address['number'][0]))
$line[] = $address['number'];
if (isset ($address['complement'][0]))
$line[] = $address['complement'];
if ($line)
$lines[] = implode (', ', $line);

if (isset ($address['district'][0]))
$lines[] = $address['district'];

$line = [];
if (isset ($address['city'][0]))
$line[] = $address['city'];
if (isset ($address['state'][0]))
$line[] = $address['state'];
if ($line)
$lines[] = implode (' - ', $line);

if (isset ($address['postal_code'][0]))
$lines[] = $address['postal_code'];
if (isset ($address['country'][0]))
$lines[] = $address['country'];

$buffer = implode ('<br>', $lines);

$control['text']['content'] = $document->textMerge ($buffer);
$formulary->appendChild ($control);
} // function view

static function scope ($render)
{ // function scope
$data['data'] = $render->getVar ('address');
return $data;
} // function scope

static function target ($document, $value, $arguments)
{ // function target
if (count ($arguments) < 2)
return;

list ($id, $field) = $arguments;
$me = $document->domain->findChild (intval ($id));
if (!$me or !$document->access (4, $me->groups))
return;

if ($document->charset != 'UTF-8')
$value = mb_convert_encoding ($value, 'UTF-8', $document->charset);
$me->data['local']['address'][$field] = $value;
} // function target

static function column ($document, $control, $data)
{ // function column
if (!isset ($formulary->data['local']['address']))
return array ('caption' => $document->textMerge ('-'));

$enabled = false;
$list = array ('street', 'number', 'complement', 'district', 'city', 'state', 'postal_code', 'country');
foreach ($list as $field)
{ // verify each field
if (isset ($formulary->data['local']['address'][$field][0]))
{ // found
$enabled = true;
break;
} // found
} // verify each field
if (!$enabled)
return array ('caption' => $document->textMerge ('-'));

$address = $data['local']['address'];

$buffer = '';
$lines = [];
$line = [];

if (isset ($address['street'][0]))
$line[] = $address['street'];
if (isset ($address['number'][0]))
$line[] = $address['number'];
if (isset ($address['complement'][0]))
$line[] = $address['complement'];
if ($line)
$lines[] = implode (', ', $line);

if (isset ($address['district'][0]))
$lines[] = $address['district'];

$line = [];
if (isset ($address['city'][0]))
$line[] = $address['city'];
if (isset ($address['state'][0]))
$line[] = $address['state'];
if ($line)
$lines[] = implode (' - ', $line);

if (isset ($address['postal_code'][0]))
$lines[] = $address['postal_code'];
if (isset ($address['country'][0]))
$lines[] = $address['country'];

$buffer = implode ('<br>', $lines);

return array ('caption' => $document->textMerge ($buffer));
} // function column

} // class eclFilter_personaliteFields_address

?>