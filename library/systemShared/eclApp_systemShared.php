<?php

class eclApp_systemShared
{ // class eclApp_systemShared

static function is_child ($me, $name)
{ // function is_child
global $io;
if (in_array ('-shared', $io->request->pathway))
return true;

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
$me->name = '-shared';
$me->ignoreSubfolders = true;
$me->ignoreSession (true);
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $components;
$me = $document->application;
$pathway = $document->pathway;
while (array_shift ($pathway) != $me->name);

if (count ($pathway) < 2)
exit;

if ($pathway[0] == 'libraries')
{ // libraries
array_shift ($pathway);

if (isset ($components[$pathway[0]]))
return self::join_component_library ($pathway);
if (SYSTEM_IS_PACKED)
return self::send_packed_file (array ('scripts', end($pathway)));

return self::join_shared_library ($pathway);
} // libraries

if (isset ($components[$pathway[0]]))
return self::send_component_file ($pathway);
if (SYSTEM_IS_PACKED)
return self::send_packed_file ($pathway);

return self::send_shared_file ($pathway);
} // function dispatch

static function send_packed_file ($pathway)
{ // function send_packed_file
global $dataMap, $io;

static $mime = array (
'css' => 'text/css', 
'gif' => 'image/gif', 
'html' => 'text/html', 
'htm' => 'text/html', 
'jpg' => 'image/jpeg', 
'js' => 'text/javascript', 
'mp3' => 'audio/mpeg', 
'png' => 'image/png', 
'svg' => 'image/svg+xml', 
'txt' => 'text/plain', 
'ttf' => 'font/ttf'
);

$folders = $pathway;
$name = array_pop ($folders);
$path = implode ('/', $folders);

if (!isset ($dataMap['f'][$path][$name]))
exit;

@list (, $extension) = explode ('.', $name, 2);
if (!isset ($mime[$extension]))
{ // bad request
header ('HTTP/1.0 400 Bad Request');
exit;
} // bad request
if (SYSTEM_TIME_LIMIT)
set_time_limit (0);
error_reporting (0);

while (@ob_end_clean ());
$headers = is_callable ('apache_request_headers') ? apache_request_headers () : [];

header_remove ('Pragma');
header_remove ('Expires');
header_remove ('X-Powered-By');

$buffer = $io->cachedControls->file ($path, $name);
$size = strval (strlen ($buffer));
$eTag = md5 ($name . SYSTEM_PACKED_DATE);

if (isset ($headers['ETag']) and $headers['ETag'] == $eTag)
{ // not modified
header ('HTTP/1.1 304 Not Modified');
exit;
} // not modified

if (isset ($headers['If-Modified-Since']) and $headers['If-Modified-Since'] == SYSTEM_PACKED_DATE)
{ // not modified
header ('HTTP/1.1 304 Not Modified');
exit;
} // not modified

header ('HTTP/1.1 200 OK');
header ('Access-Control-Allow-Origin: *');
header ('Cache-Control: public, only-if-cached, max-age=172800');
header ('Last-Modified: ' . SYSTEM_PACKED_DATE);
header ('ETag: ' . QUOT . $eTag . QUOT);
header ('Content-type: ' . $mime[$extension]);
header ('Content-Length: ' . $size);
header ('Connection: close');

print $buffer;
exit;
} // function send_packed_file

static function send_shared_file ($pathway)
{ // function send_shared_file
global $io;
$file = PATH_SHARED . implode ('/', $pathway);
if (!is_file ($file))
exit;

$headers = array (
'Content-Disposition' => 'inline', 
'Cache-Control' => 'public, only-if-cached, max-age=2592000'
);
$io->sendFile->send ($file, $headers);
exit;
} // function send_shared_file

static function send_component_file ($pathway)
{ // function send_component_file
global $components, $io;
$component = array_shift ($pathway);
$file = PATH_COMPONENTS . $component . '/' . $components[$component] . '/shared/' . implode ('/', $pathway);
if (!is_file ($file))
exit;

$headers = array (
'Content-Disposition' => 'inline', 
'Cache-Control' => 'public, only-if-cached, max-age=2592000'
);
$io->sendFile->send ($file, $headers);
exit;
} // function send_component_file

static function join_shared_library ($pathway)
{ // function join_shared_library
$name = end($pathway);
list($name) = explode ('.', $name);
$dir = PATH_SHARED . 'libraries/' . $name;
if (!is_dir ($dir))
exit;

print self::join_library ($dir);
exit;
} // function join_shared_library

static function join_component_library ($pathway)
{ // function join_component_library
global $components;
if (count ($pathway) < 2)
exit;

$component = array_shift ($pathway);
$fileName = end ($pathway);
list ($library) = explode ('.', $fileName);

$dir = PATH_COMPONENTS . $component . '/' . $components[$component] . '/shared/' . $library . '/';
if (!is_dir ($dir))
exit;

print self::join_library ($dir);
exit;
} // function join_component_library

static function join_library ($dir)
{ // join_library
$buffer = '';

foreach (scandir ($dir) as $group)
{ // index each group
if ($group[0] == '.' or !is_dir ($dir . '/' . $group))
continue;

$buffer .= 'var ' . $group . ' = ' . $group . ' || {};' . CRLF;
} // index each group

foreach (scandir ($dir) as $group)
{ // each group
if ($group[0] == '.' or !is_dir ($dir . '/' . $group))
continue;

foreach (scandir ($dir . '/' . $group) as $component)
{ // each component
if ($component[0] == '.' or !is_dir ($dir . '/' . $group . '/' . $component))
continue;
$buffer .= $group . '.' . $component . ' = ' . $group . '.' . $component . ' || {};' . CRLF
. '(function (me){' . CRLF;

foreach (scandir ($dir . '/' . $group . '/' . $component) as $file)
{ // each folder
if ($file[0] == '.')
continue;
if (is_dir ($dir . '/' . $group . '/' . $component . '/' . $file))
{ // create class
$buffer .= 'class ' . $file . ' {' . CRLF;
foreach (scandir ($dir . '/' . $group . '/' . $component . '/' . $file) as $method)
{ // each method
if ($method[0] == '.')
continue;

if (is_file ($dir . '/' . $group . '/' . $component . '/' . $file . '/' . $method))
$buffer .= file_get_contents ($dir . '/' . $group . '/' . $component . '/' . $file . '/' . $method);
} // each method
$buffer .= '};' . CRLF;
} // create class
} // each folder

foreach (scandir ($dir . '/' . $group . '/' . $component) as $file)
{ // each file
if ($file[0] == '.')
continue;

if (!is_file ($dir . '/' . $group . '/' . $component . '/' . $file))
continue;
$buffer .= file_get_contents ($dir . '/' . $group . '/' . $component . '/' . $file);
} // each file
$buffer .= '})(' . $group . '.' . $component . ');' . CRLF;
} // each component
} // each group
return $buffer;
} // function join_library

} // class eclApp_systemShared

?>