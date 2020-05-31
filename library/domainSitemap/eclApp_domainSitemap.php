<?php

class eclApp_domainSitemap
{ // class eclApp_domainSitemap

static function is_child ($me, $name)
{ // function is_child
global $store;

if ($name == 'robots.txt')
return true;
if ($name == 'sitemap.xml')
return true;
if (!strpos ($name, '.'))
return false;

$data = $store->domainContent->open ($me->domainId, '-google-webmasters');
if ($data and $data['local']['file_name'] == $name)
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return array ('robots.txt', 'sitemap.xml');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
if ($me->name == '-google-verify')
$me->access = 4;

$me->ignoreSubfolders = true;
$me->ignoreSession (true);
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $io, $store;
$me = $document->application;

$data = $store->domainContent->open ($me->domainId, '-google-webmasters');
if ($data and $data['local']['file_name'] == $me->name)
return self::action_verify_page ($document);

if ($me->name == 'robots.txt')
{ // robots.txt

if (defined ('SYSTEM_PACKED_DATE'))
$updated = SYSTEM_PACKED_DATE;
else
$updated = date ('r', TIME);
$eTag = ('robots.txt' . $updated);
$buffer = 'sitemap: ' . $document->url (array ($document->domain->name, 'sitemap.xml'), false, false) . CRLF;
$size = strval (strlen ($buffer));

error_reporting (0);
while (@ob_end_clean ());
header_remove ('Pragma');
header_remove ('Expires');
header_remove ('X-Powered-By');

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
header ('Content-type:text/plain');
header ('Content-Length: ' . $size);
header ('Connection: close');

print $buffer;
exit;
} // robots.txt

$document->data['flags']['modLayout_base'] = 'empty';
$document->mod->layout = new eclMod_domainSitemap_layout ($document);
$document->render ();

$updated = date ('r', TIME);
$size = strval (strlen ($document->buffer));

// error_reporting (0);
while (@ob_end_clean ());

header_remove ('Pragma');
header_remove ('Expires');
header_remove ('X-Powered-By');

header ('HTTP/1.1 200 OK');
header ('Cache-Control: public, only-if-cached, max-age=172800');
header ('Last-Modified: ' . $updated);
header ('Content-type:text/xml');
header ('Content-Length: ' . $size);
header ('Connection: close');

print $document->buffer;
exit;
} // function dispatch

static function action_verify_page ($document)
{ // function action_verify_page
global $store;

$me = $document->application;
$data = $store->domainContent->open ($me->domainId, '-google-webmasters');

header_remove ('Pragma');
header_remove ('Expires');
header_remove ('X-Powered-By');

header ('HTTP/1.1 200 OK');
header ('Last-Modified: ' . date ('r', $data['updated']));
header ('Content-type:text/html');
header ('Content-Length: ' . strlen ($data['local']['file_content']));
header ('Connection: close');

print $data['local']['file_content'];
exit;
} // function action_verify_page

} // class eclApp_domainSitemap

?>