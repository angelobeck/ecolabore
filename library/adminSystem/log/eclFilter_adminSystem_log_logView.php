<?php

class eclFilter_adminSystem_log_logView
{ // class eclFilter_adminSystem_log_logView

static function create ($fieldName, $control, $formulary)
{ // function create
global $io;

$item = $formulary->document->createListItem ($control);

// name
$item->data['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$item->data['type'] = $control['flags']['type'];
else
$item->data['type'] = 'textarea';

$string = &$io->fileBinary->open (SYSTEM_LOG_FILE);
if ($formulary->command ('clear_log'))
$string = '';

$log = $io->fileBinary->open (SYSTEM_LOG_FILE);
$lines = [];
foreach (explode (LF, $log) as $line)
{ // each log line
if (!trim ($line))
continue;

list ($date, $info) = explode ("]", trim ($line));
if (isset ($lines[$info]))
$lines[$info]++;
else
$lines[$info] = 1;
} // each log line

$buffer = '';
foreach ($lines as $info => $times)
{ // each line
$buffer .= $info . ' (' . $times . ")" . CRLF;
} // each line
$item->data['value'] = $buffer;

return $item;
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
} // function save

} // class eclFilter_adminSystem_log_logView

?>