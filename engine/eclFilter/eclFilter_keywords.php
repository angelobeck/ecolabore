<?php

/*
* Valid control flags
* control_filter = keywords
*/

class eclFilter_keywords
{ // class eclFilter_keywords

static function create ($fieldName, $control, $formulary)
{ // function create
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $io;

// If a previous problem was ocurred saving the formulary, we need to abort
if ($formulary->errorMsg !== false)
return false;
$data = &$formulary->data;

if (isset ($data['name']))
$keywords = $data['name'];
else
$keywords = '';

foreach (array ('caption', 'title', 'author', 'licence') as $field)
{ // each text field
if (isset ($data['text'][$field]))
{ // field exists
foreach ($data['text'][$field] as $language)
{ // each language
if (!isset ($language[TEXT_CHARSET]) or $language[TEXT_CHARSET] == 0)
$keywords .= ' ' . mb_convert_encoding ($language[TEXT_CONTENT], 'ISO-8859-1', 'UTF-8');
elseif ($language[TEXT_CHARSET] == 1)
$keywords .= ' ' . $language[TEXT_CONTENT];
} // each language
} // field exists
} // each text field

$keywords = str_replace (array ('#', ';'), '', $keywords);

if (isset ($data['text']['keywords']))
{ // set hash tags
$buffer = '';
foreach ($data['text']['keywords'] as $language)
{ // each language
if (!isset ($language[TEXT_CHARSET]) or $language[TEXT_CHARSET] == 0)
$buffer .= ' ' . mb_convert_encoding ($language[TEXT_CONTENT], 'ISO-8859-1', 'UTF-8');
elseif ($language[TEXT_CHARSET] == 1)
$buffer .= ' ' . $language[TEXT_CONTENT];
} // each language
foreach (explode (' ', $buffer) as $key)
{ // filter each keyword
$key = str_replace (array ('#', ';'), '', $key);
if (!strlen ($key))
continue;

$keywords .= ' :' . $key . ':';
} // filter each keyword
} // set hash tags

$formulary->data['keywords'] = implode (' ', eclIo_database::filterKeywords ($keywords));
} // function save

} // class eclFilter_keywords

?>