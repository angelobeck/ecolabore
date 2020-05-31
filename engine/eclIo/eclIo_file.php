<?php

class eclIo_file
{ // class eclIo_file

private $openedFiles = [];

public function &open ($name)
{ // function &
if (!isset ($this->openedFiles[$name]))
{ // open file
if (is_file ($name))
$this->openedFiles[$name][0] = $this->string2array (file_get_contents ($name, FILE_BINARY));
else
$this->openedFiles[$name][0] = [];
$this->openedFiles[$name][1] = $this->openedFiles[$name][0];
} // open file
$data = &$this->openedFiles[$name][0];
return $data;
} // function &

public function read ($name)
{ // function read
if (is_file ($name))
return $this->string2array (file_get_contents ($name, FILE_BINARY));
return [];
} // function read

public function close ()
{ // function close
foreach ($this->openedFiles as $name => $contents)
{ // each opened file
// protection mode
// '<' . '?' . 'php __halt_' . 'compiler();' . CRLF .

if ($contents[0] and $contents[0] != $contents[1])
file_put_contents ($name, $this->array2string ($contents[0]));
if (!$contents[0] and is_file ($name))
unlink ($name);
} // each opened files
} // function close

public static function array2string ($array, $level=0)
{ // function array2string
$string = '';
foreach ($array as $key => $value)
{ // each item
if (is_int ($key) and $level < 2)
$string .= '#=';
elseif (is_int ($key))
$string .= strval ($key) . '=';
elseif (is_string ($key))
$string .= "" . TIC . str_replace (TIC, "''", $key) . TIC . "=";
else
continue;

if (is_int ($value))
$string .= strval ($value) . CRLF;
elseif (is_string ($value))
$string .= "" . TIC . str_replace (TIC, "''", $value) . "" . TIC . CRLF;
elseif (is_array ($value))
$string .= '{' . CRLF . self::array2string ($value, $level + 1) . '}' . CRLF;
else
$string .= '0' . CRLF;
} // each item
return $string;
} // function array2string

public static function string2array ($string, $counter1=0)
{ // function string2array
if (!is_string ($string))
return [];

// acrescentamos à string recebida uma quebra de linha
$string .= LF;
// o cumprimento total da string
$strlen = strlen ($string);
// o ponteiro que percorrerá a string
$pointer = 0;
// o edifício
$building = [];
// o andar em que nos encontramos
$floor = 1;
$building[$floor] = [];
// O contador que substituirá os coringas encontrados
$counter[$floor] = $counter1;

// Sinalizadores
$buffer = '';
$buffer_enabled = false;
$buffer_string = false;
$key_enabled = false;

// iremos varrer a string até que o ponteiro alcance seu cumprimento total
while ($pointer < $strlen)
{ // get char

// capturamos o caractére da string que se encontra sob o ponteiro
$char = $string[$pointer];

// Verificamos que caractére é este
switch ($char)
{ // char

// ao encontrar uma quebra de linha, criamos um novo item de array
case "\n":
if (!$buffer_string)settype ($buffer, 'int');
if ($key_enabled)
{ // creates a value
$building[$floor][$key] = $buffer;
} // creates a value
$buffer = '';
$key_enabled = false;
$buffer_enabled = false;
$buffer_string = false;
break;

// ao encontrar "=", o que estiver no $buffer representa a chave de um item
case '=':
if ($buffer_enabled)
{ // creates key
if ($buffer == '#')
{ // counter
$buffer = $counter[$floor];
$counter[$floor]++;
} // counter
elseif (!$buffer_string)
{ // numeric
settype ($buffer, 'int');
} // numeric
$key = $buffer;
$buffer = '';
$buffer_enabled = false;
$buffer_string = false;
$key_enabled = true;
} // creates key
break;

// ao encontrar "{", subiremos um andar
case '{':
if ($key_enabled)
{ // sublevel
$array_key[$floor] = $key;
$floor++;
$building[$floor] = [];
$counter[$floor] = 0;
} // sublevel
$key = '';
$key_enabled = false;
$buffer = '';
$buffer_enabled = false;
$buffer_string = false;
break;

// ao encontrar "}", copiamos o array do andar corrente para o item atual do andar inferior e descemos um andar
case '}':
if ($floor <= 1)
break 2;

$building[$floor - 1][$array_key[$floor - 1]] = $building[$floor];
$counter[$floor] = 0;
$floor--;
$buffer = '';
$buffer_enabled = false;
$buffer_string = false;
$key = '';
$key_enabled = false;
break;

// Ao encontrar TIC, procuramos uma string com apóstrofos duplicados ("''")
case TIC:
case QUOT:
loop_escape_char:
$n_close_string = strpos ($string, $char, $pointer + 1);
if ($n_close_string === false)
break;

$buffer .= substr ($string, $pointer + 1, $n_close_string - ($pointer + 1));
$buffer_enabled = true;
$buffer_string = true;
$pointer = $n_close_string;
if ($string[$pointer + 1] != $char)
break;
$buffer .= $char;
$pointer++;
goto loop_escape_char;

// Caractéres ignorados
case CR:
case "\t":
case ' ':
break;

// Ao encontrar "/" ou "<", seguiremos ignorando uma linha de comentário
case '<':
case '/':
$n_close_string = strpos ($string, LF, $pointer + 1);
if ($n_close_string === false)
break;
else
{ // coment
$pointer = $n_close_string - 1;
} // coment
break;

// outros caractéres são guardados em $buffer
default:
$buffer .= $char;
$buffer_enabled = true;
break;
} // char

// avança o ponteiro para o próximo caractére
$pointer++;
} // get char
return $building[1];
} // function string2array

} // class eclIo_file

?>