<?php

class eclIo_smtp
{ // class eclIo_smtp

private $enabled = false;

private $host;
private $port;
private $user;
private $password;
private $from;

private $to = [];
private $cc = [];
private $bcc = [];

private $subject = '';
private $destinations = '';
private $alternativeContents = [];
private $relatedContents = [];
private $attachmentContents = [];

public $rejected = [];
public $log = '';

public function __construct ($host=false, $port=false, $user=false, $password=false, $from=false)
{ // function __construct
if (!defined ('INTEGRATION_SMTP_ENABLE') or !INTEGRATION_SMTP_ENABLE)
return;

$this->host = $host ? $host : INTEGRATION_SMTP_HOST;
$this->port = $port ? $port : INTEGRATION_SMTP_PORT;
$this->user = $user ? $user : INTEGRATION_SMTP_USER;
$this->password = $password ? $password : INTEGRATION_SMTP_PASSWORD;
$this->from = $from ? $from : INTEGRATION_SMTP_FROM;
$this->enabled = true;
} // function __construct

public function from ($from)
{ // function from
$this->from = $from;
return $this;
} // function from

public function to ($to)
{ // function to
foreach (explode (';', $to) as $copy)
{ // each copy
$this->to[] = trim ($copy);
} // each copy
return $this;
} // function to

public function cc ($cc)
{ // function cc
foreach (explode (';', $cc) as $copy)
{ // each copy
$this->cc[] = trim ($copy);
} // each copy
return $this;
} // function cc

public function bcc ($bcc)
{ // function bcc
foreach (explode (';', $bcc) as $copy)
{ // each copy
$this->bcc[] = trim ($copy);
} // each copy
return $this;
} // function bcc

public function subject ($subject, $charset='UTF-8')
{ // function subject
$this->subject = 'Subject: =?' . $charset . '?Q?' . quoted_printable_encode ($subject) . '?=' . CRLF;
return $this;
} // function subject

public function contentHTML ($html, $charset='UTF-8', $description="Message in HTML format")
{ // function contentHTML
$this->alternativeContents[] = 'Content-type: text/html;charset=' . $charset . CRLF
 . 'Content-Description: =?' . $charset . '?Q?' . quoted_printable_encode ($description) . '?=' . CRLF
 . 'Content-Disposition: inline' . CRLF
 . 'Content-Transfer-Encoding: base64' . CRLF
 . CRLF
 . chunk_split (base64_encode ($html)) . CRLF;

return $this;
} // function contentHTML

public function contentTXT ($text, $charset='UTF-8', $description="Message in plain text format")
{ // function contentTXT
$this->alternativeContents[] = 'Content-type: text/plain;charset=' . $charset . CRLF
 . 'Content-Description: =?' . $charset . '?Q?' . quoted_printable_encode ($description) . '?=' . CRLF
 . 'Content-Disposition: inline' . CRLF
 . 'Content-Transfer-Encoding: base64' . CRLF
 . CRLF
 . chunk_split (base64_encode ($text)) . CRLF;

return $this;
} // function contentTXT

public function relatedImg ($path, $cid)
{ // function relatedImg
$fileName = array_pop (explode ('/', $path));
$fileExtension = strtolower (array_pop (explode ('.', $fileName)));
$mime = array (
'jpg' => 'image/jpeg', 
'jpeg' => 'image/jpeg', 
'gif' => 'image/gif', 
'png' => 'image/png'
);
$contents = file_get_contents ($path);

$this->relatedContents[] = 'Content-Type: ' . $mime[$fileExtension] . '; name=' . QUOT . $fileName . QUOT . CRLF
 . 'Content-Disposition: inline; filename=' . QUOT . $fileName . QUOT . CRLF
 . 'Content-Transfer-Encoding: base64' . CRLF
 . 'Content-ID: <' . $cid . '>' . CRLF
 . CRLF
 . chunk_split (base64_encode ($contents)) . CRLF
 . CRLF;

return $this;
} // function relatedImg

public function attachment ($path)
{ // function attachment
$fileName = array_pop (explode ('/', $path));

if (function_exists ('mime_content_type'))
$mime = mime_content_type ($path);
else
$mime = 'application/octet-stream';

$contents = file_get_contents ($path);

$this->attachmentContents[] = 'Content-Type: ' . $mime . '; name=' . QUOT . $fileName . QUOT . CRLF
 . 'Content-Disposition: attachment; filename=' . QUOT . $fileName . QUOT . CRLF
 . 'Content-Transfer-Encoding: base64' . CRLF
 . CRLF
 . chunk_split (base64_encode ($contents)) . CRLF
 . CRLF;

return $this;
} // function attachment

public function send ()
{ // function send
if (!$this->enabled)
return array ('msg' => 'adminMail_smtp_alertDisabled');

if (SYSTEM_TIME_LIMIT)
set_time_limit (0);

// Opens a socket
$this->socket = @fsockopen ($this->host, $this->port, $errNo, $errMsg, INTEGRATION_SMTP_TTL);
if (!$this->socket)
return array ('msg' => 'adminMail_smtp_alertSocketError', 'server' => $this->host);

// Ehlo
$this->putContents ("EHLO " . $this->host . CRLF);
if ($this->discoverError ())
return array ('msg' => 'adminMail_smtp_alertSocketError', 'server' => $this->host);

// Auth login
$this->putContents ("AUTH LOGIN" . CRLF);
if ($this->discoverError ())
return array ('msg' => 'adminMail_smtp_alertSocketError', 'server' => $this->host);

// Username
$this->putContents (base64_encode ($this->user) . CRLF);
if ($this->discoverError ())
return array ('msg' => 'adminMail_smtp_alertLoginError', 'server' => $this->host, 'user' => $this->user);

// Password
$this->putContents (base64_encode ($this->password) . CRLF);
if ($this->discoverError ())
return array ('msg' => 'adminMail_smtp_alertLoginError', 'server' => $this->host, 'user' => $this->user);

// mail from
$this->putContents ("MAIL FROM: <" . $this->from . ">" . CRLF);
if ($this->discoverError ())
return array ('msg' => 'adminMail_smtp_alertLoginError', 'server' => $this->host, 'user' => $this->user);

// Test the destinations
if ($error = $this->rcpt ())
return $error;

// data command
$this->putContents ("DATA" . CRLF);
if ($this->discoverError ())
return array ('msg' => 'adminMail_smtp_alertSendingError', 'to' => $this->to);

list ($headers, $content) = explode (CRLF . CRLF, $this->getMessageBody (), 2);

// The message
$out = 'MIME-Version: 1.0' . CRLF
 . 'From: <' . $this->from . '>' . CRLF
 . $this->destinations
 . $this->subject
 . 'Date: ' . date ('D, d M Y H:i:s O') . CRLF
 . 'Message-ID: <' . date ('YmdHis') . "." . md5 (microtime ()) . "." . $this->from . '>' . CRLF
// . 'User-Agent: ' . SYSTEM_GENERATOR . ' ' . SYSTEM_VERSION . ' ' . SYSTEM_RELEASE . CRLF
 . 'Importance: normal' . CRLF
 . $headers . CRLF
 . CRLF;

$this->putContents ($out);
if ($this->discoverError ())
return array ('msg' => 'adminMail_smtp_alertSendingError', 'to' => implode ('; ', $this->to));

// Content
$this->putContents ($content . CRLF . '.' . CRLF);
if ($this->discoverError ())
return array ('msg' => 'adminMail_smtp_alertSendingError', 'to' => $this->to);

// Close the socket
fclose ($this->socket);
return false;
} // function send

private function getContents ()
{ // function getContents
$c = $this->socket;
$buffer = '';
while ($str = @fgets ($c, 515))
{ // loop reading
$buffer .= $str;
// if the 4th character is a space then we are done reading
// so just break the loop
if (substr ($str, 3, 1) == " ")
break;
} // loop reading
$this->log .= $buffer;
return $buffer;
} // function getContents

private function putContents ($out)
{ // function putContents
if (!@fwrite ($this->socket, $out))
exit ('connection was broken');
$this->log .= $out;
return true;
} // function putContents

private function discoverError ()
{ // function discoverError
$lines = $this->getContents ();
$lines = trim ($lines);
if (strlen ($lines) < 4)
return true;
if ($lines[0] == '5')
return true;

return false;
} // function discoverError

private function getMessageBody ()
{ // function getMessageBody
$alternativeBoundary = md5 (TIME . 'ALTERNATIVE');
$relatedBoundary = md5 (TIME . 'RELATIVE');
$attachmentBoundary = md5 (TIME . 'ATTACHMENT');

if (count ($this->alternativeContents) == 1)
$alternativeBody = $this->alternativeContents[0];

elseif (count ($this->alternativeContents) > 1)
$alternativeBody = 'Content-Type: multipart/alternative; boundary=' . QUOT . $alternativeBoundary . QUOT . CRLF
 . CRLF
 . '--' . $alternativeBoundary . CRLF
 . implode (CRLF . '--' . $alternativeBoundary . CRLF, $this->alternativeContents)
 . CRLF
 . '--' . $alternativeBoundary . '--' . CRLF;

array_unshift ($this->relatedContents, $alternativeBody);

if (count ($this->relatedContents) == 1)
$relatedBody = $this->relatedContents[0];

else
$relatedBody = 'Content-Type: multipart/related; boundary=' . QUOT . $relatedBoundary . QUOT . CRLF
 . CRLF
 . '--' . $relatedBoundary . CRLF
 . implode (CRLF . '--' . $relatedBoundary . CRLF, $this->relatedContents)
 . CRLF
 . '--' . $relatedBoundary . '--' . CRLF;

array_unshift ($this->attachmentContents, $relatedBody);

if (count ($this->attachmentContents) == 1)
return $this->attachmentContents[0];

return 'Content-Type: multipart/mixed; boundary=' . QUOT . $attachmentBoundary . QUOT . CRLF
 . CRLF
 . '--' . $attachmentBoundary . CRLF
 . implode (CRLF . '--' . $attachmentBoundary . CRLF, $this->attachmentContents)
 . CRLF
 . '--' . $attachmentBoundary . '--' . CRLF;
} // function getMessageBody

private function rcpt ()
{ // function rcpt
foreach ($this->to as $recipient)
{ // each recipient
$this->putContents ("RCPT TO: <" . $recipient . ">" . CRLF);
if ($this->discoverError ())
$this->rejected[] = $recipient;
else
$to[] = $recipient;
} // each recipient

foreach ($this->cc as $recipient)
{ // each recipient
$this->putContents ("RCPT TO: <" . $recipient . ">" . CRLF);
if ($this->discoverError ())
$this->rejected[] = $recipient;
else
$cc[] = $recipient;
} // each recipient

foreach ($this->bcc as $recipient)
{ // each recipient
$this->putContents ("RCPT TO: <" . $recipient . ">" . CRLF);
if ($this->discoverError ())
$this->rejected[] = $recipient;
else
$bcc[] = $recipient;
} // each recipient

if (isset ($to) and $to)
$this->destinations .= 'To: ' . implode (",\r\n\t", $to) . CRLF;

if (isset ($cc) and $cc)
$this->destinations .= 'Cc: ' . implode (",\r\n\t", $cc) . CRLF;

if (isset ($bcc) and $bcc)
$this->destinations .= 'Bcc: ' . implode (",\r\n\t", $bcc) . CRLF;

if (!strlen ($this->destinations))
return array ('msg' => 'adminMail_smtp_alertToError', 'to' => implode ('; ', $this->rejected));

return false;
} // function rcpt

public function close ()
{ // function close
} // function close

} // class eclIo_smtp

?>