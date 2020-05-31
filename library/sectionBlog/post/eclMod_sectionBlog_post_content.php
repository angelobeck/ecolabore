<?php

class eclMod_sectionBlog_post_content
{ // class eclMod_sectionBlog_post_content

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;
$render = $document->render;
$me = $document->application->parent;

// Procure o módulo dentro da aplicação
if (isset ($me->data['extras']['post']))
$data = $me->data['extras']['post'];
else
{ // from preset
if (isset ($me->data['flags']['modList_preset']))
$preset = $me->data['flags']['modList_preset'];
else
$preset = 'blog';

$data = $render->block ('modules/post_' . $preset);
if (!$data)
$data = $render->block ('modules/post_blog');
} // from preset

$mod->data = $render->block ('modules/content');
$mod->data = array_replace_recursive ($mod->data, $data);

// Itens da lista
$mod->appendChild ($document->application)
->set ('editable', $document->contentEditable);

// Se o módulo pode ser editado
if ($document->templateEditable and $document->access (4, $me->groups))
{ // reference
$pathway = array_slice ($me->pathway, 1);
array_unshift ($pathway, $document->domain->name, '-personalite', 'extras', 'post');
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modContent_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // reference

$mod->enabled = true;
} // function setModule

} // class eclMod_sectionBlog_post_content

?>