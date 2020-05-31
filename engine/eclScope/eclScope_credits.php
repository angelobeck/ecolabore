<?php

class eclScope_credits
{ // class eclScope_credits

static function get ($render, $arguments)
{ // function get
if (!isset ($render->data['text']['author']))
return false;

$data = $render->data;
if (isset ($render->data['extras']['credits']))
$data['local'] = $render->data['extras']['credits'];
return $data;
} // function get

} // class eclScope_credits

?>