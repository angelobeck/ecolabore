<?php

class eclScope_user
{ // class eclScope_user

static function get ($render, $arguments)
{ // function get
global $store;
$document = $render->document;

if (!isset ($arguments[0]) or !$arguments[0])
return false;

if ($document->user->userId == $arguments[0])
{ // you
return $store->control->read ('user_you');
} // you

$userName = $store->user->getName ($arguments[0]);
$user = $store->userContent->open ($arguments[0], '-register');
if (!$user)
return;

$data['local'] = $user;
$data['local']['url'] = $render->document->url (array (SYSTEM_PROFILES_URI, $userName));
return $data;
} // function get

} // class eclScope_user

?>