<?php

class eclMod_personaliteTemplate_filters_list
{ // class eclMod_personaliteTemplate_filters_list

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;
$me = $document->application;
$render = $document->render;

foreach ($store->control->scandir ('c', 'sectionBlog/post') as $name)
{ // each detail
$data = $store->control->read ('sectionBlog_post_' . $name);

if (!isset ($data['flags']['filters_manager']))
continue;
if (!$data or !isset ($data['text']['caption']))
continue;

$data['name'] = $data['flags']['filters_manager'];
$mod->appendChild ($data);
} // each detail

$mod->enabled = true;
} // function setModule

} // class eclMod_personaliteTemplate_filters_list

?>