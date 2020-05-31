<?php

class eclTag_details
{ // class eclTag_details

static $type = 'scope';

static function render ($render, $arguments)
{ // function render
global $store;

$details = $render->getVar ('mod.details');

if (!strlen ($details))
return;

if (is_string ($details))
$details = explode (' ', str_replace (CRLF, ' ', $details));

$buffer['parsed'] = [];
foreach ($details as $name)
{ // each detail
if (!strlen ($name = trim ($name)))
continue;

$data = $render->block ('details/' . $name);
if (isset ($data['parsed']))
{ // ready
$buffer['parsed'] = array_merge ($buffer['parsed'], $data['parsed']);
continue;
} // ready

if (!isset ($data['html']))
continue;

$buffer['parsed'] = array_merge ($buffer['parsed'], $render->render_tags ($data['html'], true));
} // each detail

return $buffer;
} // function render

static function close ($render)
{ // function close

} // function close

} // class eclTag_details

?>