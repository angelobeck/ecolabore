<?php

class eclScope_audima
{ // class eclScope_audima

static function get ($render, $arguments)
{ // function get
global $store, $system;
if ($render->me === false)
return false;
$document = $render->document;
$me = $render->me;
if ($document->data['id'] != $me->data['id'])
return [];

if (isset ($me->data['extras']['audio_0']))
return [];

$data = array ('local' => array ('audima' => 1));
return $data;
} // function get

} // class eclScope_audima

?>