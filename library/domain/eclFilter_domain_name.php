<?php

class eclFilter_domain_name
{ // class eclFilter_domain_name

static function create ($fieldName, $control, $formulary)
{ // function create
$local['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$local['type'] = $control['flags']['type'];
else
$local['type'] = 'text_small';

// target
if (isset ($formulary->data['name']))
$local['value'] = $formulary->data['name'];

$formulary->appendChild ($control, $local);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $store;
$document = $formulary->document;
$name = '';
// If a previous problem was ocurred saving the formulary, we need to abort
if ($formulary->errorMsg !== false)
return;

$received = $formulary->received;

// Get name from formulary
if (isset ($received[$fieldName]) and strlen ($received[$fieldName]))
$name = $received[$fieldName];

// If has no name to receive, but the $data['name'] is set, silently abort
elseif (isset ($formulary->data['name']) and strlen ($formulary->data['name']))
return;

// But if $data['name'] is not set, we will try to create one based on
// caption or title from formulary, because a new section or post needs a name
else
{ // look for alternative name
if (isset ($formulary->data['text']['caption'][$document->lang][TEXT_CONTENT]))
$name = $formulary->data['text']['caption'][$document->lang][TEXT_CONTENT];
elseif (isset ($formulary->data['text']['title'][$document->lang][TEXT_CONTENT]))
$name = $formulary->data['text']['title'][$document->lang][TEXT_CONTENT];
} // look for alternative name

// We need to convert special characters. But the convertion only will works
// currectly if the encoding are ASCII or ISO-8859-1
$charset = $formulary->document->charset;
if ($charset != 'ISO-8859-1')
$name = mb_convert_encoding ($name, 'ISO-8859-1', $charset);

// Here is a table for character convertion:
// the key represents the input character,
// and the value is the valid character to use instead
// Other characters will be ignored
static $convert = array ('a' => 'a', 'b' => 'b', 'c' => 'c', 'd' => 'd', 'e' => 'e', 'f' => 'f', 'g' => 'g', 'h' => 'h', 'i' => 'i', 'j' => 'j', 'k' => 'k', 'l' => 'l', 'm' => 'm', 'n' => 'n', 'o' => 'o', 'p' => 'p', 'q' => 'q', 'r' => 'r', 's' => 's', 't' => 't', 'u' => 'u', 'v' => 'v', 'w' => 'w', 'x' => 'x', 'y' => 'y', 'z' => 'z', 'A' => 'a', 'B' => 'b', 'C' => 'c', 'D' => 'd', 'E' => 'e', 'F' => 'f', 'G' => 'g', 'H' => 'h', 'I' => 'i', 'J' => 'j', 'K' => 'k', 'L' => 'l', 'M' => 'm', 'N' => 'n', 'O' => 'o', 'P' => 'p', 'Q' => 'q', 'R' => 'r', 'S' => 's', 'T' => 't', 'U' => 'u', 'V' => 'v', 'W' => 'w', 'X' => 'x', 'Y' => 'y', 'Z' => 'z', 'â' => 'a', 'Â' => 'a', 'ã' => 'a', 'Ã' => 'a', 'á' => 'a', 'Á' => 'a', 'à' => 'a', 'À' => 'a', 'ä' => 'a', 'Ä' => 'a', 'Ç' => 'c', 'ç' => 'c', 'ê' => 'e', 'Ê' => 'e', 'é' => 'e', 'É' => 'e', 'ë' => 'e', 'Ë' => 'e', 'î' => 'i', 'Î' => 'i', 'í' => 'i', 'Í' => 'i', 'ï' => 'i', 'Ï' => 'i', 'ô' => 'o', 'Ô' => 'o', 'õ' => 'o', 'Õ' => 'o', 'ó' => 'o', 'Ó' => 'o', 'ö' => 'o', 'Ö' => 'o', 'û' => 'u', 'Û' => 'u', 'ú' => 'u', 'Ú' => 'u', 'ü' => 'u', 'Ü' => 'u', 'Ý' => 'y', 'ý' => 'y', 'ÿ' => 'y', 'Ÿ' => 'y', ' ' => '-', '_' => '-', '-' => '-', '/' => '-', '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '0' => '0');

// The $last will remember the last character of the convertion
// to prevent duplicated spacing "__" or "--" in the name

$result = '';
$last = '-';
foreach (str_split ($name) as $char)
{ // each char
if (!isset ($convert[$char]))
continue;
$valid = $convert[$char];
if ($valid != $last)
{ // not repeated separator
if ($valid == '-' or $valid == '_')
$last = $valid;
else
$last = '';
$result .= $valid;
} // not repeated separator
} // each char
if (strlen ($result) > 34)
$result = substr ($result, 0, 34);
$name = trim ($result, '_-');

// Can happens that the convertion eats all characters!
// But if $data['name'] is set, we can silently abort.
if (!strlen ($name) and isset ($formulary->data['name']))
return false;

// If the received name is equal the $data['name'], nothing has to be done
if (isset ($formulary->data['name']) and $name == $formulary->data['name'])
return false;

// In cases that the name was not set, and we not found a valid one,
// we will provide a default one
if (!strlen ($name) or preg_match ('/^[0-9]+$/', $name))
{ // provides a default value
if (isset ($control['flags']['default_value']))
$name = $control['flags']['default_value'];
else
$name = 'page';
} // provides a default value

// Name changed

// Prevent duplicated names
$domainId = $formulary->document->application->domainId;
$testing = $name;
while ($store->domainContent->open ($domainId, $testing))
{ // unduplicate name
isset ($index) ? $index++ : $index = 1;
$testing = $name . str_pad (strval ($index), 3, '0', STR_PAD_LEFT);
} // unduplicate name
$name = $testing;

// If the name was changed, we need to rename all files of this section or post
if (isset ($formulary->data['name']))
{ // rename files
$store->domainFile->renamePrefixedFiles ($formulary->document->application->domainId, $formulary->data['name'], $name);

$prefix = $formulary->data['name'] . CHR_FNS;
$prefixLength = strlen ($prefix);
$newPrefix = $name . CHR_FNS;
foreach ($formulary->data['extras'] as &$module)
{ // each module
foreach ($module as &$property)
{ // each property
if (!is_string ($property) or strlen ($property) < $prefixLength)
continue;

if (substr ($property, 0, $prefixLength) == $prefix)
$property = $newPrefix . substr ($property, $prefixLength);
} // each property
} // each module
} // rename files

// If you are not so tired, please set $data['name']!
$formulary->data['name'] = $name;
return false;
} // function save

} // class eclFilter_domain_name

?>