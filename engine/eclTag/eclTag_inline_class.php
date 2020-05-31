<?php

class eclTag_inline_class
{ // class eclTag_inline_class

static function render ($render, $arguments)
{ // function render
if (!isset ($arguments[0][0]))
return false;

$result = [];
foreach (explode (' ', $arguments[0]) as $field)
{ // each field
$parts = explode ('$', $field);

if (!isset ($parts[1]))
{ // discrete class
$result[] = $parts[0];
continue;
} // discrete class

$options = explode ('??', $parts[1]);

$value = $render->getVar ($options[0]);
if ($value !== '')
{ // exists
$result[] = $parts[0] . $value;
continue;
} // exists

if (!isset ($options[1]))
continue;

$result[] = $parts[0] . $options[1];
} // each field

if (!$result)
return false;

$render->buffer .= ' class=' . QUOT . implode (' ', $result) . QUOT;

return false;
} // function render

static function close ($render)
{ // function close

} // function close

} // class eclTag_inline_class

?>