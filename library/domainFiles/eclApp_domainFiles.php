<?php

class eclApp_domainFiles
{ // class eclApp_domainFiles

static function is_child ($me, $name)
{ // function is_child
switch ($name)
{ // switch name
case '-files':
case '-downloads':
case '-play':
return true;
} // switch name

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
$me->ignoreSubfolders = true;
$me->ignoreSession (true);
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $io, $store;
$pathway = $document->pathway;

$mode = substr ($document->application->name, 1);
$fileName = array_pop ($pathway);

if ($mode == 'downloads')
$disposition = 'attachment';
else
$disposition = 'inline';

$path = FOLDER_DOMAINS . $document->domain->name . '/' . $fileName;

$headers = array (
'Content-Disposition' => $disposition, 
'Cache-Control' => 'public, only-if-cached, max-age=172800'
);

if ($mode == 'downloads' or $mode == 'play')
{ // download
@list ($filePrefix, $extension) = explode ('.', $fileName);
@list ($name, $target) = explode (CHR_FNS, $filePrefix);

$data = &$store->domainContent->open ($document->domain->domainId, $name);
if (!$data)
exit;

if ($mode == 'downloads' and isset ($data['extras'][$target]['filename']))
$headers['Filename'] = $data['extras'][$target]['filename'];

if (!isset ($data['extras'][$target][$mode]))
$data['extras'][$target][$mode] = 1;
else
$data['extras'][$target][$mode]++;
} // download

$io->sendFile->send ($path, $headers);
} // function dispatch

} // class eclApp_domainFiles

?>