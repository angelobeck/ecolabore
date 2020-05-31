<?php

class eclApp_personaliteInstall
{ // class eclApp_personaliteInstall

static function is_child ($me, $name)
{ // function is_child
if ($name == 'install')
return true;

return false;
} // function is_child

static function get_menu_type ($me)
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('install');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('personaliteInstall_content');
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $io;

if ($io->request->uploaded)
self::action_install ($document);

if (is_file (PATH_DOMAINS . $document->domain->name . '/-install.zip'))
return self::action_select_components ($document);

$formulary = $document->createFormulary ('personaliteInstall_edit', [], 'install');

if ($formulary->command ('cancel'))
return $document->dataReplace ('layouts/dialog_cancel');

$document->mod->formulary = $formulary;
} // function dispatch

static function action_install ($document)
{ // function action_install
global $io, $store;
$me = $document->application;

foreach ($io->request->uploaded as $name => $files)
{ // each entry
foreach ($files as $file)
{ // each file
if ($file['size'] > 100000000 or $file['size'] < 3)
continue;
@list ($name, $ext) = explode ('.', $file['name']);
if ($ext == 'zip')
{ // move install file
$folder = PATH_DOMAINS . $document->domain->name . '/-install.zip';
move_uploaded_file ($file['tmp_name'], $folder);
return;
} // move install file
} // each file
} // each entry

$filename = PATH_DOMAINS . $document->domain->name . '/-install.zip';
$zip = new ZipArchive ();
if ($zip->open ($filename, ZIPARCHIVE::CREATE) !== TRUE)
return;

foreach ($io->request->uploaded as $name => $files)
{ // each entry
foreach ($files as $file)
{ // each file
if ($file['size'] > 100000000 or $file['size'] < 3)
continue;

$zip->addFile ($file['tmp_name'], '/' . $file['name']);
} // each file
} // each entry

$zip->close ();
} // function action_install

static function action_select_components ($document)
{ // function action_select_components
$formulary = $document->createFormulary ('personaliteInstall_select', [], 'select');

if ($formulary->command ('cancel'))
return $document->dataReplace ('layouts/dialog_cancel');

if ($formulary->command ('save') and $formulary->save ())
{ // save
if (isset ($formulary->data['saved']))
{ // saved
$fileName = PATH_DOMAINS . $document->domain->name . '/-install.zip';
if (is_file ($fileName))
unlink ($fileName);
$document->dataReplace ('layouts/dialog_close');
return;
} // saved
} // save

$document->mod->formulary = $formulary;
} // function action_select_components

} // class eclApp_personaliteInstall

?>