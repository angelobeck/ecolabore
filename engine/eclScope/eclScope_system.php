<?php

class eclScope_system
{ // class eclScope_system

static function get ($render, $arguments)
{ // function get
global $system;
$data['data'] = $system->data;
return $data;
} // function get

} // class eclScope_system

?>