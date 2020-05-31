<?php

class eclScope_home
{ // class eclScope_home

static function get ($render, $arguments)
{ // function get
global $store;
$document = $render->document;

$home = $document->application;
while (!$home->isDomain)
{ // loop up
$home = $home->parent;
} // loop up

$data['data'] = $home->data;
if ($home !== $document->application and $document->application->name != '')
$data['data']['url'] = $document->url ($home->pathway);
if ($document->contentEditable and $document->application->name == '')
$data['data']['editable'] = 1;

return $data;
} // function get

} // class eclScope_home

?>