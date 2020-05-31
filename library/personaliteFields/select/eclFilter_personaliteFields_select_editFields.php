<?php

class eclFilter_personaliteFields_select_editFields
{ // class eclFilter_personaliteFields_select_editFields

static function create ($fieldName, $control, $formulary)
{ // function create
static $s = array ('&', '<', QUOT);
static $r = array ('&amp;', '&lt;', '&quot;');
static $cs = array ('UTF-8', 'ISO-8859-1');
$document = $formulary->document;
$lang = $document->lang;
$charset = $document->charset;

for ($index = 1; $index <= 30; $index++)
{ // each field
$local = array (
'caption' => $document->textMerge ('#' . $index), 
'name' => $fieldName . $index, 
'type' => 'text_small'
);
$item = $formulary->appendChild ($local);

if (!isset ($formulary->data['options'][$index]))
continue;

$text = $formulary->data['options'][$index];
if (isset ($text[$lang]))
$found = $text[$lang];
else
$found = current ($text);
if (!isset ($found[TEXT_CONTENT]))
continue;

if (!isset ($found[TEXT_CHARSET]))
$found[TEXT_CHARSET] = 0;
if ($charset != $cs[$found[TEXT_CHARSET]])
$value = mb_convert_encoding ($found[TEXT_CONTENT], $charset, $cs[$found[TEXT_CHARSET]]);
else
$value = $found[TEXT_CONTENT];

$item->data['value'] = str_replace ($s, $r, $value);
} // each field
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
$document = $formulary->document;
$received = $formulary->received;

$formulary->data['options'] = [];
for ($index = 0; $index <= 30; $index++)
{ // each option
if (!isset ($received[$fieldName . $index]) or !strlen ($received[$fieldName . $index]))
continue;

$formulary->data['options'][$index] = array ($document->lang => array (TEXT_CONTENT => $received[$fieldName . $index]));
if ($document->charset == 'ISO-8859-1')
$formulary->data['options'][$index][$document->lang][TEXT_CHARSET] = 1;
} // each option
} // function save

static function view ($fieldName, $control, $formulary)
{ // function view
$name = $control['flags']['field_name'];

$control['type'] = 'view';
if (isset ($formulary->data[$name]['value']))
$value = $formulary->data[$name]['value'];
else
$value = false;

if (isset ($control['local']['options']))
{ // options exists
foreach ($control['local']['options'] as $name => $caption)
{ // each option
if ($name != $value)
continue;

$control['content'] = $caption;
break;
} // each option
} // values exists

$formulary->appendChild ($control);
} // function view

static function column ($document, $data, $name, $field, $url)
{ // function column
if (isset ($data['local'][$name]['value']))
$value = $data['local'][$name]['value'];
else
$value = false;

if (isset ($field['options']))
{ // options exists
foreach ($field['options'] as $name => $caption)
{ // each option
if ($name != $value)
continue;

return array ('caption' => $caption);
} // each option
} // values exists

return array ('caption' => $document->textMerge ('-'));
} // function column

} // class eclFilter_personaliteFields_select_editFields

?>