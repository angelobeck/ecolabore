<?php

class eclApp_personaliteExtras
{ // class eclApp_personaliteExtras

static function is_child ($me, $name)
{ // function is_child
if ($name == 'extras')
return true;
if ($name == 'post')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return array ('extras');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('personaliteExtras_edit');
$me->access = 4;
$me->isDomain = true;
$me->ignoreSubfolders = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
if (!self::find_target ($document, $me, $target, $prefix, $isBigFile))
return self::not_configurable ($document);

// Save uploaded big files
if ($isBigFile and $document->actions ('upload', 'save'))
return self::save_uploaded_big_file ($document, $me, $target, $prefix);

// Upload big files formulary
if ($isBigFile and !isset ($me->data['extras'][$target]['files']))
return self::upload_big_file ($document, $me, $target, $prefix);

// Restore default configurations
if (isset ($document->received['save']) and $document->received['save'] == 'restore')
return self::restore_default_configurations ($document, $me, $target);

// get formulary data
$data = self::get_formulary_data ($document, $me, $target, $prefix);

// Create formulary
$formulary = $document->createFormulary ($document->application->data, $data);
$formulary->application = $me;
$formulary->flags['target'] = $target;

// Save formulary
if ($formulary->save ())
{ // save formulary
$me->data['extras'][$target] = $formulary->data;
$document->dataReplace ('layouts/dialog_close');
return;
} // save formulary

// Show formulary
$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);

$document->dataReplace ('layouts/dialog_tabs');
switch ($prefix)
{ // switch prefix
case 'audio':
case 'banner':
case 'box':
case 'file':
case 'html':
case 'img':
case 'video':
$document->data['local']['remove_object'] = 1;
} // switch prefix
} // function dispatch

static function find_target ($document, &$me, &$target, &$prefix, &$isBigFile)
{ // function find_target
global $store;
$document->application->pathway = $document->pathway;

$pathway = array_slice ($document->pathway, 3);
$target = array_shift ($pathway);

$me = $document->domain;
if (!$pathway)
$me = $me->child ('');
else
{ // find child
foreach ($pathway as $folder)
{ // each folder
$me = $me->child ($folder);
if ($me === false)
return false;
} // each folder
} // find child

if (!$document->access (4, $me->groups))
return false;

$parts = explode ('_', $target);
$prefix = $parts[0];
if ($document->application->name == 'post')
$target = 'post_' . $target;

$isBigFile = false;

switch ($prefix)
{ // switch prefix
case 'audio':
case 'file':
case 'video':
$isBigFile = true;

case 'banner':
case 'box':
case 'card':
case 'dinamic':
case 'formulary':
case 'html':
case 'img':
case 'list':
case 'pages':
case 'sort':
$document->application->data = $store->control->read ('mod' . ucfirst ($prefix) . '_edit');
return true;

case 'post':
$document->application->data = $store->control->read ('modContent_post');
return true;

default:
return false;
} // switch prefix
} // function find_target

static function not_configurable ($document)
{ // function not_configurable
$formulary = $document->createFormulary ('personaliteModules_contentNotConfigurable', [], 'not_configurable');

if ($formulary->command ('cancel'))
return $document->dataReplace ('layouts/dialog_cancel');

$document->mod->formulary = $formulary;
$document->dataReplace ('layouts/dialog');
} // function not_configurable

static function upload_big_file ($document, $me, $target, $prefix)
{ // function upload_big_file
global $store;
$formulary = $document->createFormulary ('personaliteExtras_upload_' . $prefix, [], 'upload');
$formulary->action = '_upload-save';
$document->mod->formulary = $formulary;
$document->application->data = $store->control->read ('personaliteExtras_upload_' . $prefix);
$document->dataReplace ('personaliteExtras_upload_' . $prefix);
} // function upload_big_file

static function save_uploaded_big_file ($document, $me, $target, $prefix)
{ // function save_uploaded_big_file
global $io;
ini_set ('upload_max_filesize', '1024M');
ini_set ('post_max_size', '1024M');

$document->buffer = 'ok';

$uploaded = reset ($io->request->uploaded);
if (!$uploaded)
{ // fail
$document->buffer = 'fail';
return;
} // fail

$uploaded = $uploaded[0];
if ($uploaded['error'])
return $document->buffer = 'fail';

$ext = strtolower (end (explode ('.', $uploaded['name'])));
$filename = $me->data['name'] . CHR_FNS . $target . '.' . $ext;
$path = PATH_DOMAINS . $document->domain->name . '/' . $filename;
move_uploaded_file ($uploaded['tmp_name'], $path);

$me->data['extras'][$target] = array (
'files' => array ($prefix => $filename), 
'size' => $uploaded['size'], 
'play' => 0, 
'downloads' => 0, 
'type' => $uploaded['type'], 
'filename' => $uploaded['name']
);
} // function save_uploaded_big_file

static function restore_default_configurations ($document, $me, $target)
{ // function restore_default_configurations
if (isset ($me->data['extras'][$target]['files']))
{ // remove files
foreach ($me->data['extras'][$target]['files'] as $name)
{ // each file
$file = FOLDER_DOMAINS . $document->domain->name . '/' . $name;
if (is_file ($file))
unlink ($file);
} // each file
} // remove files
unset ($me->data['extras'][$target]);

$document->dataReplace ('layouts/dialog_close');
} // function restore_default_configurations

static function get_formulary_data ($document, $me, $target, $prefix)
{ // function get_formulary_data
if (isset ($me->data['extras'][$target]))
return $me->data['extras'][$target];

if ($document->application->name == 'post')
{ // try post
if ($data = $document->render->block ('modules/' . $prefix . '_post'))
return $data;

return $document->render->block ('modules/' . $prefix);
} // try post

if (isset ($me->data['flags']['modList_preset']))
$preset = $me->data['flags']['modList_preset'];
else
$preset = 'blog';

if ($data = $document->render->block ('modules/' . $prefix . '_' . $preset))
return $data;

if ($data = $document->render->block ('modules/' . $prefix . '_blog'))
return $data;

return $document->render->block ('modules/' . $prefix);
} // function get_formulary_data

} // class eclApp_personaliteExtras

?>