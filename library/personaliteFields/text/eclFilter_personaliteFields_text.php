<?php

class eclFilter_personaliteFields_text
{ // class eclFilter_personaliteFields_text

static function create ($fieldName, $control, $formulary)
{ // function create
static $s = array ('&', '<', QUOT);
static $r = array ('&amp;', '&lt;', '&quot;');
static $cs = array ('UTF-8', 'ISO-8859-1');
$document = $formulary->document;
$lang = $document->lang;
$charset = $document->charset;
$name = $control['flags']['field_name'];

// type
if (isset ($control['flags']['type']))
$control['type'] = $control['flags']['type'];
else
$control['type'] = 'text';

$control['name'] = $fieldName;

$item = $formulary->appendChild ($control);
if (!isset ($formulary->data['text'][$name]))
return;

$text = $formulary->data['text'][$name];
if (isset ($text[$lang]))
$found = $text[$lang];
else
$found = current ($text);
if (!isset ($found[TEXT_CONTENT]))
return;

if (!isset ($found[TEXT_CHARSET]))
$found[TEXT_CHARSET] = 0;
if ($charset != $cs[$found[TEXT_CHARSET]])
$value = mb_convert_encoding ($found[TEXT_CONTENT], $charset, $cs[$found[TEXT_CHARSET]]);
else
$value = $found[TEXT_CONTENT];

$item->data['value'] = str_replace ($s, $r, $value);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
if (!isset ($formulary->received[$fieldName][0]))
{ // empty
if (isset ($control['local']['required']) and $control['local']['required'])
$formulary->setRequiredMsg ($control, $fieldName);
return;
} // empty

$name = $control['flags']['field_name'];
$formulary->data['text'][$name] = array ($formulary->document->lang => array (TEXT_CONTENT => $formulary->received[$fieldName]));
if ($formulary->document->charset == 'ISO-8859-1')
$formulary->data['text'][$name][$formulary->document->lang][TEXT_CHARSET] = 1;
} // function save

static function view ($fieldName, $control, $formulary)
{ // function view
$name = $control['flags']['field_name'];

$control['type'] = 'view';

if (!isset ($formulary->data['text'][$name]))
return;

if (isset ($formulary->data['text'][$name]))
$control['content'] = $formulary->data['text'][$name];

$formulary->appendChild ($control);
} // function view

static function column ($document, $data, $name, $field, $url)
{ // function column
if (isset ($data['text'][$name]))
return array ('caption' => $data['text'][$name]);

return array ('caption' => $document->textMerge ('-'));
} // function column

} // class eclFilter_personaliteFields_text

?>