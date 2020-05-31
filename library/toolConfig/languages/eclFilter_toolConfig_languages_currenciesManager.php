<?php

class eclFilter_toolConfig_languages_currenciesManager
{ // class eclFilter_toolConfig_languages_currenciesManager

static function create ($fieldName, $control, $formulary)
{ // function create
global $io, $store;
$document = $formulary->document;
$me = $document->application;
$item = $document->createListItem ($control);

// name
$item->data['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$item->data['type'] = $control['flags']['type'];
else
$item->data['type'] = 'manager';

// help
if (isset ($control['flags']['help']) and !isset ($control['flags']['help_msg']))
$item->data['help_msg'] = 'system_msg_filterDetailsHelp';

// references
$pathway = array ($document->domain->name, '-tools', 'config', 'languages', 'currencies');
$item->data['url_add'] = $document->url ($pathway);
$item->data['move-enable'] = 1;
$item->data['remove-enable'] = 1;
// $item->data['edit-enable'] = 1;

$value = $formulary->getField ('flags/currencies');
$buffer = '';
foreach (explode (CRLF, $value) as $currency)
{ // each line
$currency = trim ($currency);
if (!isset ($currency[0]))
continue;

$data = $store->control->read ('labels/currency/' . $currency);

if (isset ($data['text']['caption']))
$caption = $data['text']['caption'];
else
$caption = $document->textMerge ($currency);
$buffer .= $currency . CRLF;
$item->appendChild (false, array ('value' => $currency, 'caption' => $caption));
} // each line
$item->data['serialized'] = $buffer;
return $item;
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $store;

$buffer = [];
if (isset ($formulary->received[$fieldName . '_serialized']))
{ // received
foreach (explode (LF, $formulary->received[$fieldName . '_serialized']) as $line)
{ // each line
$line = trim ($line);
if (preg_match ('/^[a-z][a-z]$/', $line))
$buffer[] = $line;
} // each line
} // received
if ($buffer)
$buffer = implode (CRLF, $buffer);
else
$buffer = false;
$formulary->setField ('flags/currencies', $buffer);
} // function save

} // class eclFilter_toolConfig_languages_currenciesManager

?>