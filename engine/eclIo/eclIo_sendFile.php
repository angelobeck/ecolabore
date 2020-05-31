<?php

class eclIo_sendFile
{ // class eclIo_sendFile

static public function send ($path, $params)
{ // function send
error_reporting (0);
if (SYSTEM_TIME_LIMIT)
set_time_limit (0);

while (@ob_end_clean ());

// turn off compression on the server
// if (is_callable ('apache_setenv'))
// apache_setenv ('no-gzip', 1);
$headers = is_callable ('apache_request_headers') ? apache_request_headers () : [];
// ini_set ('zlib.output_compression', 'Off');

$parts = self::get_file_parts ($path);

header_remove ('Pragma');
header_remove ('Expires');
header_remove ('X-Powered-By');

if (!$parts)
{ // bad request
header ('HTTP/1.0 400 Bad Request');
return 400;
} // bad request

if (!is_file ($path))
{ // file not found
header ('HTTP/1.0 404 Not Found');
return 404;
} // file not found

$name = $parts[0];
$mime = $parts[1];
$size = filesize ($path);
$start = 0;
$end = $size - 1;
$updated = filemtime ($path);
$eTag = md5 ($name . $updated);

// check if http_range is sent by browser (or download manager)
if (isset ($_SERVER['HTTP_RANGE']))
{ // requested range
list ($requested_size_unit, $requested_range_orig) = explode ('=', $_SERVER['HTTP_RANGE'], 2);
// multiple ranges could be specified at the same time, but for simplicity only serve the first range
// http://tools.ietf.org/id/draft-ietf-http-range-retrieval-00.txt
list ($range) = explode (',', $requested_range_orig, 2);
list ($start) = explode ('-', $range);
$start = intval (trim ($start));

if ($requested_size_unit != 'bytes' or $start >= $end)
{ // out of range
header ('HTTP/1.1 416 Requested Range Not Satisfiable');
return 416;
} // out of range
} // requested range

if (!$start and isset ($headers['ETag']) and $headers['ETag'] == $eTag)
{ // not modified
header ('HTTP/1.1 304 Not Modified');
return 304;
} // not modified

if (!$start and isset ($headers['If-Modified-Since']) and $headers['If-Modified-Since'] == $updated)
{ // not modified
header ('HTTP/1.1 304 Not Modified');
return 304;
} // not modified

$stream = fopen ($path, 'rb');

if (!$stream and $size > 2000000)
{ // internal error
header ('HTTP/1.0 500 Internal Server Error');
return 500;
} // internal error

header ('HTTP/1.1 200 OK');
header ('Access-Control-Allow-Origin: *');

// allow a file to be streamed instead of sent as an attachment
if (isset ($_REQUEST['stream']))
header ('Content-Disposition: inline;');
elseif (isset ($params['Content-Disposition']) and $params['Content-Disposition'] == 'inline')
header ('Content-Disposition: inline;');
elseif (isset ($params['Filename']))
header ('Content-Disposition: attachment; filename=' . QUOT . $params['Filename'] . QUOT);
else
header ('Content-Disposition: attachment; filename=' . QUOT . $name . QUOT);

if (isset ($params['Cache-Control']))
header ('Cache-Control: ' . $params['Cache-Control']);

header ('ETag: ' . QUOT . $eTag . QUOT);
header ('Last-Modified: ' . $updated);
header ('Content-Type: ' . $mime);

if ($stream)
header ('Accept-Ranges: bytes');

//Only send partial content header if downloading a piece of the file (IE workaround)
if ($stream and $start > 0)
{ // partial content
header ('HTTP/1.1 206 Partial Content');
header ('Content-Range: bytes ' . strval ($start) . '-' . $end . '/' . $size);
header ('Content-Length: ' .  ( ($end - $start) + 1));
} // partial content
else
header ('Content-Length: ' . $size);

if (!$stream and !$start)
{ // fopen failed
print file_get_contents ($path);
return 0;
} // fopen failed

fseek ($stream, $start);
fpassthru ($stream);
fclose ($stream);
return 0;
} // function send

static public function get_file_parts ($file)
{ // function get_file_parts
$parts = explode ('/', $file);

// cant read from root directory (protection)
if (count ($parts) < 2)
return false;

$name = array_pop ($parts);
$n = strrpos ($name, '.');
if (!$n)
return array ($name, "application/octet-stream");

$extension = strtolower (substr ($name, $n + 1));
switch ($extension)
{ // switch extension
case 'js':
$mime = 'application/javascript';
break;
case 'json':
$mime = 'application/json';
break;
case 'jpg':
case 'jpeg':
case 'jpe':
$mime = 'image/jpg';
break;
case 'png':
case 'gif':
case 'bmp':
case 'tiff':
$mime = 'image/' . $extension;
break;
case 'css':
$mime = 'text/css';
break;
case 'xml':
$mime = 'application/xml';
break;
case 'doc':
case 'docx':
$mime = 'application/msword';
break;
case 'xls':
case 'xlt':
case 'xlm':
case 'xld':
case 'xla':
case 'xlc':
case 'xlw':
case 'xll':
$mime = 'application/vnd.ms-excel';
break;
case 'ppt':
case 'pps':
$mime = 'application/vnd.ms-powerpoint';
break;
case 'rtf':
$mime = 'application/rtf';
break;
case 'pdf':
$mime = 'application/pdf';
break;
case 'html':
case 'htm':
case 'php':
$mime = 'text/html';
break;
case 'txt':
$mime = 'text/plain';
break;
case 'mpeg':
case 'mpg':
case 'mpe':
$mime = 'video/mpeg';
break;
case 'mp3':
$mime = 'audio/mpeg';
break;
case 'wav':
$mime = 'audio/wav';
break;
case 'aiff':
case 'aif':
$mime = 'audio/aiff';
break;
case 'avi':
$mime = 'video/msvideo';
break;
case 'wmv':
$mime = 'video/x-ms-wmv';
break;
case 'mov':
$mime = 'video/quicktime';
break;
case 'zip':
$mime = 'application/zip';
break;
case 'tar':
$mime = 'application/x-tar';
break;
case 'swf':
$mime = 'application/x-shockwave-flash';
break;
default:
if (function_exists ('mime_content_type'))
{ // discover file type
$mime = mime_content_type ($path);
} // discover file type
else
$mime = 'application/octet-stream';
} // switch extension

return array ($name, $mime);
} // function get_file_parts

public function close ()
{ // function close
} // function close

} // class eclIo_sendFile

?>