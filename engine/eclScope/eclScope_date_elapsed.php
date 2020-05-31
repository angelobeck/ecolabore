<?php

class eclScope_date_elapsed
{ // class eclScope_date_elapsed

static function get ($render, $arguments)
{ // function get
global $store;

if (!isset ($arguments[0]))
return false;

$time = intval ($arguments[0]);
if (!$time)
return false;

$dif = TIME - $time;

if ($dif <= 60)
$message = 'now';
elseif ($dif <= 120)
$message = '1minute';
elseif ($dif <= 3600)
{
$message = 'minutes';
$local['i'] = intval ($dif / 60);
}
elseif ($dif <= 7200)
$message = '1hour';
elseif ($dif <= 86400)
{
$message = 'hours';
$local['h'] = intval ($dif / 3600);
}
elseif ($dif <= 172800)
$message = '1day';
elseif ($dif <= 2592000)
{
$message = 'days';
$local['d'] = intval ($dif / 86400);
}
elseif ($dif <= 5184000)
$message = '1month';
elseif ($dif <= 31104000)
{
$message = 'months';
$local['m'] = intval ($dif / 2592000);
}
elseif ($dif <= 62208000)
$message = '1year';
else
{
$message = 'years';
$local['y'] = intval ($dif / 31104000);
}
$description = $render->block ('labels/date/elapsed_' . $message);
$local['elapsed'] = $description['text']['caption'];
$data['local'] = $local;
return $data;
} // function get

} // class eclScope_date_elapsed

?>