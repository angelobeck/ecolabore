<?php

class eclTag_or
{ // class eclTag_or

static function render ($render, $arguments)
{ // function render
if (!isset ($arguments[0]))
return '';

if ($arguments[0])
return $arguments[0];

if (isset ($arguments[1]))
return $arguments[1];

return '';
} // function render

static function close ($render)
{ // function close

} // function close

} // class eclTag_or

?>