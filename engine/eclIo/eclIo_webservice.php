<?php

class eclIo_webservice
{ // class eclIo_webservice

public function request ($url, $data=[], $headers=[])
{ // function request
global $io;

if (!function_exists ('curl_init'))
{ // error
$io->log->addMessage ('curl library not found');
return [];
} // error

$ch = curl_init ($url);

if($data)
{ // post
$data = $this->array2json ($data);
$headers = array_replace ($headers, ['Content-Type: application/json', 'Content-Length: ' . strlen ($data)]);

curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);
} // post
else
{ // get
curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "GET");
} // get

if($headers)
curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);

curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec ($ch);
return $this->json2array ($result);
} // function request

public function read ()
{ // function read
$string = file_get_contents ("php://input");
return $this->json2array ($string);
} // function read

public function send ($data)
{ // function send
print $this->array2json ($data);

header_remove ();
header ('HTTP/1.1 200 OK');
header ('X-Powered-By: ECOLABORE/' . SYSTEM_VERSION);
header ("Cache-Control: no-cache, must-revalidate, max-age=0");
header ('Content-Type: text/json; charset=utf-8');
header ('Content-length: ' . strval (ob_get_length ()));
} // function send

static function array2json ($array)
{ // function array2json
static $replaces = array (
"\b" => '\\b', 
"\f" => '\\f',
"\n" => '\\n',
CR => '',
"\t" => '\\t',
QUOT => '\\"',
'\\' => '\\\\'
);

$buffer = '';

if (!is_array ($array) or !$array)
return '{}';

reset ($array);
$key = key ($array);
if ($key === 0)
{ // is indexed array
$buffer .= '[' . CRLF;
foreach ($array as $key => $value)
{ // each value
if ($key)
$buffer .= ',' . CRLF;

if ($value === true)
$buffer .= 'true';
elseif ($value === false)
$buffer .= 'false';
elseif (is_int ($value))
$buffer .= strval ($value);
elseif (is_float ($value))
$buffer .= strval ($value);
elseif (is_string ($value))
$buffer .= QUOT . str_replace (array_keys ($replaces), array_values ($replaces), $value) . QUOT;
elseif (is_array ($value))
$buffer .= self::array2json ($value);
} // each value
$buffer .= CRLF . ']';
return $buffer;
} // is indexed array

$buffer .= '{' . CRLF;
$index = 0;
foreach ($array as $key => $value)
{ // each array element
if ($index++)
$buffer .= ',' . CRLF;
$buffer .= QUOT . str_replace (QUOT, '\\"', $key) . QUOT . ':';

if ($value === true)
$buffer .= 'true';
elseif ($value === false)
$buffer .= 'false';
elseif (is_int ($value))
$buffer .= strval ($value);
elseif (is_float ($value))
$buffer .= strval ($value);
elseif (is_string ($value))
$buffer .= QUOT . str_replace (array_keys ($replaces), array_values ($replaces), $value) . QUOT;
elseif (is_array ($value))
$buffer .= self::array2json ($value);
} // each array element
$buffer .= CRLF . '}';
return $buffer;
} // function array2json

public static function json2array ($string)
{ // function json2array
static $replaces = array (
"\b" => '\\b', 
"\f" => '\\f',
"\r\n" => '\\n',
"" => '\\r',
"\t" => '\\t',
QUOT => '\\"',
'\\' => '\\\\'
);

$pointer = 0;
$floor = 0;
$array[0] = [];
$key[0] = false;
$isFloat = false;
$value = [];

while (isset ($string[$pointer]))
{ // each character

switch ($string[$pointer])
{ // switch char
case ' ':
case CR:
case "\n":
case "\t":
$pointer++;
break;

case '[':
case '{':
$pointer++;
$floor++;
$array[$floor] = [];
$key[$floor] = false;
$value = [];
break;

case ']':
case '}':
$pointer++;

if (!is_array ($value))
{ // append last item
if ($key[$floor] === false)
$array[$floor][] = $value;
else
$array[$floor][$key[$floor]] = $value;
$key[$floor] = false;
$value = [];
} // append last item

if ($floor < 0)
return $array[0];

$floor--;
if ($key[$floor] === false)
$array[$floor][] = $array[$floor + 1];
else
$array[$floor][$key[$floor]] = $array[$floor + 1];

$key[$floor] = false;
break;

case ':':
$pointer++;
if (!is_array ($value) and !is_bool ($value) and preg_match ('/^[$a-zA-Z][a-zA-Z0-9_-]*$/', $value))
$key[$floor] = $value;
elseif (!is_array ($value) and !is_bool ($value) and preg_match ('/^[0-9]+$/', $value))
$key[$floor] = intval ($value);

$value = [];
break;

case ',':
$pointer++;
if (is_array ($value))
break;

if ($key[$floor] === false)
$array[$floor][] = $value;
else
$array[$floor][$key[$floor]] = $value;
break;

case QUOT:
$pointer++;
$start = $pointer;
$pointer = strpos ($string, QUOT, $pointer);
if ($pointer === false)
return $array[0];

while ($string[$pointer - 1] == '\\')
{ // loop escaped quotes
$pointer = strpos ($string, QUOT, $pointer + 1);
if ($pointer === false)
return $array[0];
} // loop escaped quotes

$value = substr ($string, $start, $pointer - $start);
$value = str_replace (array_values ($replaces), array_keys ($replaces), $value);
$pointer++;
break;

case 't':
case 'T':
$pointer += 4;
$value = true;
break;

case 'f':
case 'F':
$pointer += 5;
$value = false;
break;

case '.':
case '0':
case '1':
case '2':
case '3':
case '4':
case '5':
case '6':
case '7':
case '8':
case '9':
$value = '';
while (isset ($string[$pointer]))
{ // search numbers
switch ($string[$pointer])
{ // switch chars
case '.':
$isFloat = true;
case '0':
case '1':
case '2':
case '3':
case '4':
case '5':
case '6':
case '7':
case '8':
case '9':
$value .= $string[$pointer];
$pointer++;
break;

default:
if ($isFloat)
$value = floatval ($value);
else
$value = intval ($value);

$isFloat = false;
break 2;
} // switch chars
} // search numbers
break;
} // switch char
} // each character
if (count ($array[0]) == 1 and isset ($array[0][0]))
return $array[0][0];

return $array[0];
} // function json2array

public function close ()
{ // function close
} // function close

} // class eclIo_webservice

?>