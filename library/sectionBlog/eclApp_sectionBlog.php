<?php

class eclApp_sectionBlog
{ // class eclApp_sectionBlog

static function constructor_helper ($me)
{ // function constructor_helper
$me->map = array ('sectionBlog_post', 'sectionBlog_post_new');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$me = $document->application;

if ($document->access (4))
{ // admin

// Context new post
$pathway = $me->pathway;
$pathway[] = '-new-post';
$document->mod->context->appendChild ('sectionBlog_post_contextNew')
->url ($pathway);

$document->mod->editor->enable ();
} // admin

$document->mod->sort = new eclMod_sectionBlog_sort ($document);
$document->mod->list = new eclMod_sectionBlog_list ($document);
$document->mod->pages = new eclMod_sectionBlog_pages ($document);
} // function dispatch

static function remove ($me)
{ // function remove
} // function remove

} // class eclApp_sectionBlog

?>