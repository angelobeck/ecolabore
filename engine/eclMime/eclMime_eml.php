<?php

class eclMime_eml
{ // class eclMime_eml

static public function parse ($string)
{ // parse
$message = new eclMime_eml_message();
self::mailParse ($string, $message);
return $message;
} // parse

public static function mailParse ($string, $message, $mime='multipart/alternative')
{ // mailParse
if (!strpos ($string, CRLF . CRLF))
return;

list ($head, $content) = explode (CRLF . CRLF, $string);
$headers = self::parseHeaders ($head);
$contentType = self::getMimeType ($headers);
switch ($contentType['mime'])
{ // switch mime
case 'multipart/mixed':
case 'multipart/related':
case 'multipart/alternative':
$parts = explode ('--' . $contentType['boundary'], $content);
foreach ($parts as $part)
{ // each part
self::mailParse ($part, $message, $contentType['mime']);
} // each part
return;
} // switch mime

if (isset ($headers['content-transfer-encoding']))
{ // transfer encoding
switch (strtolower ($headers['content-transfer-encoding']))
{ // switch transfer encoding
case 'base64':
$content = base64_decode ($content);
break;

case 'quoted-printable':
$content = quoted_printable_decode ($content);
} // switch transfer encoding
} // transfer encoding

if (isset ($headers['content-disposition']) and strtolower ($headers['content-disposition']) == 'attachment')
{ // attachment
$message->attach ($content, $head);
return;
} // attachment

switch ($mime)
{ // switch mime
case 'multipart/mixed':
$message->addAttachment ($content, $head);
return;

case 'multipart/related':
$message->addRelatedContent ($content, $head);
return;

case 'multipart/alternative':
default:
$message->addAlternativeContent ($content, $head);
return;
} // switch mime
} // mailParse

public static function parseHeaders ($head)
{ // function parseHeaders
$lines = explode (LF, $head);
$headers = [];
$lastHeader = '';

foreach ($lines as $line)
{ // each line
if (preg_match ('/^([a-zA-Z-]+)\:(.+)/', $line, $match))
{ // new header
$lastHeader = strtolower ($match[1]);
$headers[$lastHeader][] = trim ($match[2]);
} // new header
else
$headers[$lastHeader][] = trim ($line);
} // each line

$valid = [];
$address = false;
foreach ($headers as $header => $lines)
{ // each header
if (substr ($header, 0, 2) == 'x-')
continue;

switch ($header)
{ // switch headers
case 'delivered-to':
case 'from':
case 'to':
case 'reply-to':
case 'sender':
case 'return-path':

foreach ($lines as $line)
{ // each line
$address = self::isValidAddress($line);
if ($address !== false)
$valid[$header] = $address;
} // each line
break;

default:
break;
} // switch headers
} // each header

return $valid;
} // function parseHeaders

public static function isValidAddress ($line)
{ // isValidAddress
if (preg_match ('/([a-zA-Z0-9._-]+[@][a-zA-Z0-9._-]+)/', $line, $match))
return $match[1];

return false;
} // isValidAddress

public function close(){}

} // class eclMime_eml

?>