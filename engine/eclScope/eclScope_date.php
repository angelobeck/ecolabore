<?php

class eclScope_date
{ // class eclScope_date

static function get ($render, $arguments)
{ // function get
global $store;
$document = $render->document;
if (!isset ($arguments[0]))
return false;

$time = intval ($arguments[0]);
if (!$time)
return false;

if (isset ($arguments[1]) and $time == intval ($arguments[1]))
return false;

$parts = explode ('-', date ('Y-m-d-H-i-n', $time));
$local['y'] = $parts[0];
$local['m'] = $parts[1];
$local['d'] = $parts[2];
$local['h'] = $parts[3];
$local['i'] = $parts[4];
$local['n'] = $parts[5];
if ($parts[2] == 1)
$local['th'] = 'st';
elseif ($parts[2] == 2)
$local['th'] = 'nd';
elseif ($parts[2] == 3)
$local['th'] = 'rd';
else
$local['th'] = 'th';

$month = $render->block ('labels/date/month' . $parts[5]);
if (isset ($month['text']['caption']))
$local['month'] = $month['text']['caption'];
$data['local'] = $local;
return $data;
} // function get

} // class eclScope_date

?>