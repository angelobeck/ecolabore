<?php

class eclFilter_adminDatabase_query_command
{ // class eclFilter_adminDatabase_query_command

static function create ($fieldName, $control, $formulary)
{ // function create
global $io;
$item = $formulary->document->createListItem ($control);

// name
$item->data['name'] = $fieldName;

// type
if (!isset ($item->data['type']))
$item->data['type'] = 'textarea';

if (isset ($formulary->received[$fieldName . '_input']) and $io->database->status)
{ // perform a query

$buffer = '';
$io->database->verbose = false;
$result = $io->database->query ($formulary->received[$fieldName . '_input']);
$header = true;
$headers = [];
foreach ($result as $line)
{ // each line
$values = [];
if ($header)
{ // creates a header
$header = false;
foreach ($line as $fieldName => $value)
{ // each field
if (is_int ($fieldName))
continue;
$headers[] = $fieldName;
$values[] = $value;
} // each field
$buffer = implode ("\t", $headers) . CRLF;
$buffer .= implode ("\t", $values) . CRLF;
} // creates a header
else
{ // header ready
foreach ($line as $fieldName => $value)
{ // each field
if (is_int ($fieldName))
continue;
$values[] = $value;
} // each field
$buffer .= implode ("\t", $values) . CRLF;
} // header ready
} // each line
if ($io->database->error ())
$buffer .= $formulary->received[$fieldName . '_input'] . CRLF . $io->database->error () . CRLF;
if ($io->database->insertId ())
$buffer .= 'Insert id = ' . $io->database->insertId () . CRLF;
if ($io->database->affectedRows ())
$buffer .= 'Rows = ' . $io->database->affectedRows () . CRLF;
if (count ($result))
$buffer .= count ($result) . " rows in set" . CRLF;
$item->data['value'] = $formulary->htmlSpecialChars ($buffer);
$io->database->verbose = true;
} // perform a query
return $item;
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
} // function save

} // class eclFilter_adminDatabase_query_command

?>