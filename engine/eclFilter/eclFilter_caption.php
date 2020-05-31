<?php

class eclFilter_caption
{ // class eclFilter_caption

static function create ($fieldName, $control, $formulary)
{ // function create
static $s = array ('&', '<', QUOT);
static $r = array ('&amp;', '&lt;', '&quot;');
static $cs = array ('UTF-8', 'ISO-8859-1');

$document = $formulary->document;
$item = $document->createListItem ($control);
$item->data['name'] = $fieldName;
if (isset ($control['flags']['type']))
$item->data['type'] = $control['flags']['type'];
else
$item->data['type'] = 'text';
if (isset ($control['flags']['target']) and $text = $formulary->getField ($control['flags']['target']))
{ // get field

$lang = $document->lang;
$charset = $document->charset;

if (isset ($text[$lang]))
$found = $text[$lang];
elseif (isset ($control['flags']['monolang']))
$found = current ($text);
if (isset ($found[TEXT_CONTENT]))
{ // contents exists
if (!isset ($found[TEXT_CHARSET]))
$found[TEXT_CHARSET] = 0;
if ($charset != $cs[$found[TEXT_CHARSET]])
$value = mb_convert_encoding ($found[TEXT_CONTENT], $charset, $cs[$found[TEXT_CHARSET]]);
else
$value = $found[TEXT_CONTENT];

$item->data['value'] = str_replace ($s, $r, $value);
} // contents exists
} // get field
return $item;
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
if (!isset ($formulary->received[$fieldName]) or $formulary->received[$fieldName] == '')
{ // empty field
if (isset ($control['flags']['required']))
return $formulary->setRequiredMsg ($control, $fieldName);
if (isset ($formulary->data['text']['title'][$formulary->document->lang][1]))
$caption = $formulary->data['text']['title'][$formulary->document->lang][1];
if (!isset ($caption) or !$caption)
return $formulary->setRequiredMsg ($control, $fieldName);
} // empty field
else
$caption = $formulary->received[$fieldName];
$charset = $formulary->document->charset;

$lenght = mb_strlen ($caption, $charset);
if ($lenght > 36)
{ // cut caption
$caption = mb_substr ($caption, 0, 36, $charset);
$lenght = mb_strrpos ($caption, ' ', 30, $charset);
if ($lenght)
$caption = mb_substr ($caption, 0, $lenght, $charset);
$caption .= '...';
} // cut caption

if (isset ($control['flags']['target']))
{ // set field
$current = $formulary->getField ($control['flags']['target']);
$current === false ? $current = [] : null;
$lang = $formulary->document->lang;
if (isset ($current[$lang]))
unset ($current[$lang]);
if ($caption !== false)
{ // received data
$caption = str_replace (array (CR, LF), array ('', ' '), $caption);
$current[$lang][TEXT_CONTENT] = $caption;
if ($charset == 'ISO-8859-1')
$current[$lang][TEXT_CHARSET] = 1;
} // received data
if (!$current)$current = false;
$formulary->setField ($control['flags']['target'], $current);
} // set field
} // function save

} // class eclFilter_caption

?>