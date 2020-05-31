<?php

class eclTag_inline_style
{ // class eclTag_inline_style

static function render ($render, $arguments)
{ // function render
if (!isset ($arguments[0][0]))
return false;

$result = [];
foreach (explode (';', $arguments[0]) as $field)
{ // each field
if (!isset ($field[0]))
continue;

if (!strpos ($field, ':'))
continue;

list ($property, $value) = explode (':', $field);
$property = trim ($property);
$value = trim ($value);
if ($value[0] != '$')
{ // static value
$result[] = $property . ':' . $value;
continue;
} // static value

$value = $render->getVar (substr ($value, 1));
if ($value === '')
continue;

$result[] = $property . ':' . strval ($value);
} // each field

if (!$result)
return false;

$render->buffer .= ' style=' . QUOT . implode ('; ', $result) . QUOT;

return false;
} // function render

static function close ($render)
{ // function close

} // function close

} // class eclTag_inline_style

?>