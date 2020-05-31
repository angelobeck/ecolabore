<?php

/*
* control_type = text_small | text | textarea_small | textarea | textarea_big | textarea_full
* control_filter = text
* control_target
* control_field_name
* control_monolang = forces a unique language
* 
* control_format_enable
* control_format_force
* control_html_enable
* control_html_force
* control_ecolabore_enable
* control_ecolabore_force
* 
* control_help
* control_help_msg
* control_required
* control_required_msg
*/

class eclFilter_text
{ // class eclFilter_text

static function create ($fieldName, $control, $formulary)
{ // function create
static $s = array ('&', '<', QUOT);
static $r = array ('&amp;', '&lt;', '&quot;');
static $cs = array ('UTF-8', 'ISO-8859-1');

$document = $formulary->document;
$item = $document->createListItem ($control);

// name
$item->data['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$item->data['type'] = $control['flags']['type'];
else
$item->data['type'] = 'text';

// help
if (isset ($control['flags']['help']) and !isset ($control['flags']['help_msg']))
$item->data['help_msg'] = 'system_filterTextHelp';

// target
if (isset ($control['flags']['target']) and $text = $formulary->getField ($control['flags']['target']))
{ // get field
$text = $formulary->getField ($control['flags']['target']);
$lang = $document->lang;
$defaultLang = $document->defaultLang;
$charset = $document->charset;

if (isset ($text[$lang]))
$found = $text[$lang];
elseif (isset ($text[$defaultLang]))
$found = $text[$defaultLang];
else
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
$flags = $formulary->flags;

if (isset ($control['flags']['target']))
{ // set field
$current = $formulary->getField ($control['flags']['target']);
$current === false ? $current = [] : null;
if (isset ($control['flags']['monolang']))
$current = [];

$lang = $formulary->document->lang;
if (isset ($current[$lang]))
unset ($current[$lang]);

if (!isset ($formulary->received[$fieldName]) or $formulary->received[$fieldName] == '')
{ // empty field
if (isset ($control['flags']['required']) and !$current)
return $formulary->setRequiredMsg ($control, $fieldName);
$formulary->received[$fieldName] = false;
} // empty field

if ($formulary->received[$fieldName] !== false)
{ // received data
$current[$lang][TEXT_CONTENT] = $formulary->received[$fieldName];

if ($formulary->document->charset == 'ISO-8859-1')
$current[$lang][TEXT_CHARSET] = 1;

if (isset ($control['flags']['format_enable']))
{ // format enable
if (isset ($flags['text_format_choose']) and isset ($formulary->received['text_format']))
$current[$lang][TEXT_FORMAT] = 2;
elseif (isset ($flags['text_format_force']))
{ // format force
if ($flags['text_format_force'])
$current[$lang][TEXT_FORMAT] = 1;
} // format force
elseif (!isset ($flags['text_format_choose']))
$current[$lang][TEXT_FORMAT] = 1;
} // format enable
elseif (isset ($control['flags']['format_force']))
$current[$lang][TEXT_FORMAT] = $control['flags']['format_force'];

if (isset ($control['flags']['html_enable']))
{ // html enable
if (isset ($flags['text_html_choose']) and isset ($formulary->received['text_html']))
$current[$lang][TEXT_HTML] = 2;
elseif (isset ($flags['text_html_force']))
{ // html force
if ($flags['text_html_force'])
$current[$lang][TEXT_HTML] = $flags['text_html_force'];
} // html force
elseif (!isset ($flags['text_html_choose']))
$current[$lang][TEXT_HTML] = 1;
} // html enable
elseif (isset ($control['flags']['html_force']))
$current[$lang][TEXT_HTML] = $control['flags']['html_force'];

if (isset ($control['flags']['ecolabore_enable']))
{ // ecolabore enable
if (isset ($flags['text_ecolabore_choose']) and isset ($formulary->received['text_ecolabore']))
$current[$lang][TEXT_ECOLABORE] = 1;
elseif (isset ($flags['text_ecolabore_force']))
{ // force ecolabore
if ($flags['text_ecolabore_force'])
$current[$lang][TEXT_ECOLABORE] = 1;
} // force ecolabore
elseif (!isset ($flags['text_ecolabore_choose']) and !isset ($flags['text_ecolabore_disabled']))
$current[$lang][TEXT_ECOLABORE] = 1;
} // ecolabore enable
elseif (isset ($control['flags']['ecolabore_force']))
$current[$lang][TEXT_ECOLABORE] = $control['flags']['ecolabore_force'];
} // received data
if (!$current)$current = false;
$formulary->setField ($control['flags']['target'], $current);
} // set field
} // function save

} // class eclFilter_text

?>