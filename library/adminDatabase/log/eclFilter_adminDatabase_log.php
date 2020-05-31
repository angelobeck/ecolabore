<?php

class eclFilter_adminDatabase_log
{ // class eclFilter_adminDatabase_log

static function create ($fieldName, $control, $formulary)
{ // function create
global $io;

$item = $formulary->appendChild ($control);

// name
$item->data['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$item->data['type'] = $control['flags']['type'];
else
$item->data['type'] = 'textarea';

$string = &$io->fileBinary->open (DATABASE_LOG_FILE);
if ($formulary->command ('clear_log'))
$string = '';

$log = $io->fileBinary->open (DATABASE_LOG_FILE);
$item->data['value'] = $formulary->htmlSpecialChars ($log);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
} // function save

} // class eclFilter_adminDatabase_log

?>