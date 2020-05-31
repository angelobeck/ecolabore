<?php

class eclFilter_personaliteFields_start
{ // class eclFilter_personaliteFields_start

static function create ($fieldName, $control, $formulary)
{ // function create
$local['type'] = 'start';
$local['url'] = $formulary->document->url (true, true, $formulary->action);
$local['prefix'] = $formulary->prefix;
$local['time'] = TIME;
$local['password'] = md5 (TIME . $formulary->prefix . ADMIN_PASSWORD . session_id ());
$formulary->appendChild ($local);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
if ($formulary->document->user->userId)
return;

// Hanney pot
if (isset ($formulary->received[$formulary->prefix . '_command_mail']) and strlen ($formulary->received[$formulary->prefix . '_command_mail']))
return $formulary->setErrorMsg (false, [], 'systemFormulary_alertRobotsPrevent');

if (!isset ($formulary->received[$formulary->prefix . '_command_time']))
return $formulary->setErrorMsg (false, [], 'systemFormulary_alertRobotsPrevent');

$time = intval ($formulary->received[$formulary->prefix . '_command_time']);

// Time left up to 60 minutes
if ($time + SYSTEM_SESSION_TTL + 60 < TIME)
return $formulary->setErrorMsg (false, [], 'systemFormulary_alertRobotsPrevent');

// time left less than 5 seconds
if ($time + 5 > TIME)
return $formulary->setErrorMsg (false, [], 'systemFormulary_alertRobotsPrevent');

// Probably javascript disabled
if (!isset ($formulary->received[$formulary->prefix . '_command_password']))
return $formulary->setErrorMsg (false, [], 'systemFormulary_alertRobotsPrevent');

$password = $formulary->received[$formulary->prefix . '_command_password'];
if ($password != md5 ($time . $formulary->prefix . ADMIN_PASSWORD . session_id ()))
return $formulary->setErrorMsg (false, [], 'systemFormulary_alertRobotsPrevent');

// check IP tracking
} // function save

} // class eclFilter_personaliteFields_start

?>