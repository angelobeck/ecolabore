<?php

class eclTag_post
{ // class eclTag_post

static $type = 'scope';

static function render ($render, $arguments)
{ // function render
global $store, $system;

if (!isset ($arguments[0]) or !is_string ($arguments[0]) or !strlen ($arguments[0]))
return;
$class = array_shift ($arguments);
if (!preg_match ('/^[a-z][a-z0-9_]*$/', $class))
return false;

$class = 'eclScope_post' . ucfirst ($class);
return $class::get ($render, $arguments);
} // function render

static function close ($render)
{ // function close

} // function close

} // class eclTag_post

?>