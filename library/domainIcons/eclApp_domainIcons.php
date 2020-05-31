<?php

class eclApp_domainIcons
{ // class eclApp_domainIcons

static function is_child ($me, $name)
{ // function is_child
if ($name == '-icons')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return array ('-icons');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
$me->ignoreSubfolders = true;
$me->ignoreSession (true);
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $io, $store;
$me = $document->application;
$name = array_pop ($document->pathway);
$data = $store->domainContent->open ($document->domain->domainId, '-styles-vars');

if (isset ($data['updated']))
$updated = date ('r', $data['updated']);
elseif (defined ('SYSTEM_PACKED_DATE'))
$updated = SYSTEM_PACKED_DATE;
else
$updated = date ('r', TIME);

$document->data['flags']['modLayout_base'] = 'empty';
$document->mod->layout = new eclMod_domainIcons_layout ($document);
$document->render ();

$size = strval (strlen ($document->buffer));

// error_reporting (0);
while (@ob_end_clean ());
$headers = is_callable ('apache_request_headers') ? apache_request_headers () : [];

header_remove ('Pragma');
header_remove ('Expires');
header_remove ('X-Powered-By');

$eTag = ($name . $updated);

if (isset ($headers['ETag']) and $headers['ETag'] == $eTag)
{ // not modified
header ('HTTP/1.1 304 Not Modified');
exit;
} // not modified

if (isset ($headers['If-Modified-Since']) and $headers['If-Modified-Since'] == $updated)
{ // not modified
header ('HTTP/1.1 304 Not Modified');
exit;
} // not modified

header ('HTTP/1.1 200 OK');
header ('Cache-Control: public, only-if-cached, max-age=172800');
header ('Last-Modified: ' . $updated);
header ('ETag: ' . QUOT . $eTag . QUOT);
header ('Content-type: image/svg+xml');
header ('Content-Length: ' . $size);
header ('Connection: close');

print $document->buffer;
exit;
} // function dispatch

} // class eclApp_domainIcons

?>