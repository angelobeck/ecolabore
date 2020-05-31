<?php

class eclEngine_render
{ // class eclEngine_render

public $document;
public $buffer = '';

public $data = false;
public $me = false;
public $mod = [];
public $children = [];
public $index = 0;
public $blocks = [];

public $cuts = [];
public $cutOnce = [];
public $scissorsIndex = 0;
public $scissors = [];
public $pasteIndex = 0;
public $pastePointer = [];
public $pasteNames = [];
public $tagsStack = [];

private $levelIndex = 0;
private $levelArray = [];
private $markdownBuffer = '';
private $markdownReferences = [];
private $markdownHtmlEnable = 0;

public function __construct ($document=false)
{ // function __construct
$this->document = $document;
} // function __construct

public function levelUp ($data)
{ // function levelUp
$this->levelIndex++;
$this->levelArray[$this->levelIndex] = [];
$a = &$this->levelArray[$this->levelIndex];

if (isset ($data['me']))
{ //  me
$a['me'] = $this->me;
$this->me = $data['me'];
} // me

if (isset ($data['data']) or isset ($data['local']) or isset ($data['text']))
{ // data
$a['data'] = $this->data;

$this->data = [];
if (isset ($data['data']))
$this->data = $data['data'];
if (isset ($data['local']))
$this->data = array_replace ($this->data, $data['local']);
if (isset ($data['text']))
$this->data = array_replace ($this->data, $data['text']);
} // data

if (isset ($data['mod']))
{ // mod
$a['mod'] = $this->mod;
$this->mod = $data['mod'];
} // mod

if (isset ($data['blocks']))
{ // blocks
$a['blocks'] = $this->blocks;
$this->blocks = array_replace ($this->blocks, $data['blocks']);
} // blocks

if (isset ($data['children']))
{ // children
$a['children'] = $this->children;
$a['index'] = $this->index;
$this->children = $data['children'];
if (isset ($data['index']))
$this->index = $data['index'];
else
$this->index = 0;
} // children
} // function levelUp

public function levelDown ()
{ // function levelDown
foreach ($this->levelArray[$this->levelIndex] as $property => $value)
{ // each item
$this->$property = $value;
} // each item

unset ($this->levelArray[$this->levelIndex]);
$this->levelIndex--;
} // function levelDown

public function render ($content)
{ // function render
if (is_string ($content))
$content = [
TEXT_CONTENT => $content, 
TEXT_HTML => 2, 
TEXT_ECOLABORE => 1
];

if (!isset ($content[TEXT_CONTENT][0]) and !isset ($content[TEXT_EDITABLE]))
return;

if (isset ($content[TEXT_FORMAT]) and $content[TEXT_FORMAT])
{ // text format
if ($content[TEXT_FORMAT] == 2)
{ // pre formated text
$this->buffer .= $this->text2pre ($content[TEXT_CONTENT]);
return;
} // pre formated text

$this->markdown2html ($content);
} // text format

if (isset ($content[TEXT_ECOLABORE]) and $content[TEXT_ECOLABORE])
{ // render tags
$tagsLevel = count ($this->tagsStack);
$this->render_tags ($content);
for ($i = count ($this->tagsStack) - $tagsLevel; $i > 0; $i--)
{ // close pending tags
$item = end ($this->tagsStack);
$class = 'eclTag_' . $item[0];
$class::close ($this);
} // close pending tags
} // render tags
elseif ( (!isset ($content[TEXT_FORMAT]) or !$content[TEXT_FORMAT]) and (!isset ($content[TEXT_HTML]) or !$content[TEXT_HTML]))
$this->buffer .= str_replace (['&', '<', QUOT], ['&amp;', '&lt;', '&quot;'], $content[TEXT_CONTENT]);
else
$this->buffer .= $content[TEXT_CONTENT];
} // function render

private function text2pre ($string)
{ // function text2pre
$string = trim (str_replace (['&', '<', QUOT], ['&amp;', '&lt;', '&quot;'], $string));
// Converte quebras de linhas Windows para Linux
$string = str_replace (CRLF, LF, $string);
$string = str_replace (LF, CRLF, $string);
return '<pre>' . $string . '</pre>' . CRLF;
} // function text2pre

/*
* 0 type
* 1 value
* 2 arguments
* 3 sublevel
* 4 silent
* 5 parse error
* 
* Type values
* 0 string buffer
* 1 function
* 2 variable
* 3 number
* 4 operator
* 5 precedent operator
* 6 ending tag
*/

public function render_tags ($content, $parseOnly=false)
{ // function render_tags
if (is_string ($content))
$_string = $content;
elseif (is_array ($content) and isset ($content[1]))
$_string = $content[TEXT_CONTENT];
else
return;

$_length = strlen ($_string);
$_pointer = 0;

$_array = [];
$_level = 1;
$_index = [1 => 0];

PARSE_TAG_SEEK:
$_pos = strpos ($_string, '[', $_pointer);
if ($_pos === false)
{ // open tag not found
$_array[$_level][$_index[$_level]] = [0, substr ($_string, $_pointer)];
goto PARSE_END_OF_STRING;
} // open tag not found
if ($_pos > $_pointer)
{ // buffer
$_array[$_level][$_index[$_level]] = [0, substr ($_string, $_pointer, $_pos - $_pointer)];
$_index[$_level]++;
} // buffer
$_pointer = $_pos + 1;
if ($_pointer == $_length)
goto PARSE_END_OF_STRING;

$_char = ord ($_string[$_pointer]);

if ($_char == 93)
{ // []
$_array[$_level][$_index[$_level]] = [0, '['];
$_index[$_level]++;
$_pointer++;
if ($_pointer == $_length)
goto PARSE_END_OF_STRING;

goto PARSE_TAG_SEEK;
} // []

PARSE_NEXT_INSTRUCTION:

while ($_char == 9 or $_char == 10 or $_char == 13 or $_char == 32) // tab 9 lf 10 cr 13 espace 32
{ // empty space
$_pointer++;
if ($_pointer == $_length)
goto PARSE_END_OF_STRING;
$_char = ord ($_string[$_pointer]);
} // empty space

if ($_char == 47 and $_pointer + 1 < $_length and ($_string[$_pointer + 1] == '/' or $_string[$_pointer + 1] == '*'))
{ // comment
$_pointer++;
$_char = ord ($_string[$_pointer]);
if ($_char == 47)
{ // inline comment
$_offset = strcspn ($_string, CRLF . ";]", $_pointer);
$_pointer += $_offset;
if ($_pointer == $_length)
goto PARSE_END_OF_STRING;
$_char = ord ($_string[$_pointer]);
} // inline comment
elseif ($_char == 42)
{ // block comment
$_pos = strpos ($_string, '*/', $_pointer);
if ($_pos === false)
goto PARSE_END_OF_STRING;
$_pointer = $_pos + 2;
if ($_pointer == $_length)
goto PARSE_END_OF_STRING;
$_char = ord ($_string[$_pointer]);
} // block comment
goto PARSE_NEXT_INSTRUCTION;
} // comment

if ($_char == 125)
{ // } level down
$_level--;
if (!$_level)
goto PARSE_END_OF_STRING;
$_array[$_level][$_index[$_level]][3] = $_array[$_level + 1];
$_index[$_level]++;
unset ($_array[$_level + 1]);
$_pointer++;
if ($_pointer == $_length)
goto PARSE_END_OF_STRING;

$_char = ord ($_string[$_pointer]);
goto PARSE_NEXT_INSTRUCTION;
} // } level down

if ($_char == 96)
{ // ` string
$_pointer++;
if ($_pointer == $_length)
goto PARSE_END_OF_STRING;

$pos = strpos ($_string, '`', $_pointer);
if ($pos === false)
goto PARSE_END_OF_STRING;

$_array[$_level][$_index[$_level]] = [0 => 0, 1 => substr ($_string, $_pointer, $pos - $_pointer)];
$_index[$_level]++;
$_pointer = $pos + 1;
if ($_pointer == $_length)
goto PARSE_END_OF_STRING;
$_char = ord ($_string[$_pointer]);
goto PARSE_NEXT_INSTRUCTION;
} // ` string

$_offset = strcspn ($_string, '{};]', $_pointer);
if (!$_offset)
goto PARSE_END_OF_INSTRUCTION;

$length = $_pointer + $_offset;
$pointer = $_pointer;
$char = ord ($_string[$pointer]);
$silent = false;
$type = 1;
$name = '';

$level = 1;
$index[1] = 0;
$array = [1 => []];

if ($char == 64)
{ // @ silent
$silent = true;
while ($char == 9 or $char == 10 or $char == 13 or $char == 32 or $char == 64) // tab 9 lf 10 cr 13 espace 32 @ 64
{ // empty space
$pointer++;
if ($pointer == $length)
goto PARSE_END_OF_ARGUMENTS;
$char = ord ($_string[$pointer]);
} // empty space
} // @ silent

if ($char == 47)
{ // / end function
$type = 6;
$pointer++;
if ($pointer == $length)
goto PARSE_END_OF_ARGUMENTS;
$char = ord ($_string[$pointer]);
} // / end function

if ( ($char >= 65 and $char <= 90) or $char == 95 or ($char >= 97 and $char <= 122))
{ // a-z _ A-Z
do
{ //  . a-z _ A-Z 0-9
$name .= $_string[$pointer];
$pointer++;
if ($pointer == $length)
goto PARSE_END_OF_ARGUMENTS;
$char = ord ($_string[$pointer]);
} // . a-z _ A-Z 0-9
while ($char == 46 or ($char >= 65 and $char <= 90) or $char == 95 or ($char >= 97 and $char <= 122) or ($char >= 48 and $char <= 57));
} // a-z _ A-Z

// Elimina parênteses e espaços desnecessários
while ($char == 9 or $char == 10 or $char == 13 or $char == 32) // tab 9 lf 10 cr 13 espace 32
{ // empty space
$pointer++;
if ($pointer == $length)
goto PARSE_END_OF_ARGUMENTS;
$char = ord ($_string[$pointer]);
} // empty space

if ($char == 40)
{ // ()
$char = ord ($_string[$length - 1]);
while ($char == 9 or $char == 10 or $char == 13 or $char == 32 or $char == 64) // tab 9 lf 10 cr 13 espace 32 @ 64
{ // rtrim
$length--;
$char = ord ($_string[$length - 1]);
} // rtrim

if ($char == 41)
$length--;

$pointer++;
if ($pointer == $length)
goto PARSE_END_OF_ARGUMENTS;
$char = ord ($_string[$pointer]);
} // ()

if ($char == 58 or $char == 94 or $char == 42)
{ // : ^ * string
$pointer++;
if ($pointer == $length)
goto PARSE_END_OF_ARGUMENTS;

if ($char == 94)
$name = 'up';
if ($char == 42)
$name = 'footnote';

$offset = strcspn ($_string, '{]', $pointer);
$array[1][0] = [0 => 0, 1 => substr ($_string, $pointer, $offset)];
$_offset = ($pointer + $offset) - $_pointer;
goto PARSE_END_OF_ARGUMENTS;
} // : ^ * string

goto PARSE_NEXT_ARGUMENT_NO_DOWN;

PARSE_NEXT_ARGUMENT:
while (isset ($down[$level]))
{ // operator level down
unset ($down[$level]);
$level--;
if (!$level)
goto PARSE_END_OF_ARGUMENTS;
$array[$level][$index[$level]][2] = $array[$level + 1];
unset ($array[$level + 1]);
$index[$level]++;
} // operator level down

PARSE_NEXT_ARGUMENT_NO_DOWN:

while ($char == 9 or $char == 10 or $char == 13 or $char == 32) // tab 9 lf 10 cr 13 espace 32
{ // empty space
$pointer++;
if ($pointer == $length)
goto PARSE_END_OF_ARGUMENTS;
$char = ord ($_string[$pointer]);
} // empty space

if ($char == 45 or $char == 46 or ($char >= 48 and $char <= 57))
{ // number
$buffer = '';
do
{ // loop numbers
$buffer .= $_string[$pointer];
$pointer++;
if ($pointer == $length)
break;
$char = ord ($_string[$pointer]);
} // loop numbers
while ($char == 46 or ($char >= 48 and $char <= 57));

$array[$level][$index[$level]] = [0 => 3, 1 => $buffer];
$index[$level]++;
if ($pointer == $length)
goto PARSE_END_OF_ARGUMENTS;
$char = ord ($_string[$pointer]);
goto PARSE_NEXT_ARGUMENT;
} // number

if ($char == 36)
{ // $ variable
$pointer++;
if ($pointer == $length)
goto PARSE_END_OF_ARGUMENTS;
$buffer = '';

do
{ //  . a-z _ A-Z 0-9
$buffer .= $_string[$pointer];
$pointer++;
if ($pointer == $length)
break;
$char = ord ($_string[$pointer]);
} // . a-z _ A-Z 0-9
while ($char == 45 or $char == 46 or ($char >= 65 and $char <= 90) or $char == 95 or ($char >= 97 and $char <= 122) or ($char >= 48 and $char <= 57));
$array[$level][$index[$level]] = [0 => 2, 1 => $buffer];
$index[$level]++;
if ($pointer == $length)
goto PARSE_END_OF_ARGUMENTS;
$char = ord ($_string[$pointer]);
goto PARSE_NEXT_ARGUMENT;
} // $ variable

if ( ($char >= 65 and $char <= 90) or $char == 95 or ($char >= 97 and $char <= 122))
{ // function
$buffer = '';
do
{ //  . a-z _ A-Z 0-9
$buffer .= $_string[$pointer];
$pointer++;
if ($pointer == $length)
break;
$char = ord ($_string[$pointer]);
} // . a-z _ A-Z 0-9
while ($char == 46 or ($char >= 65 and $char <= 90) or $char == 95 or ($char >= 97 and $char <= 122) or ($char >= 48 and $char <= 57));

if ($buffer == 'and' or $buffer == 'or')
{ // operator
if (!$index[$level])
goto PARSE_END_OF_ARGUMENTS;
$index[$level]--;
$operand = $array[$level][$index[$level]];
$array[$level][$index[$level]] = [0 => 4, 1 => $buffer];
// puts the operand into argument list
$level++;
$array[$level] = [0 => $operand];
$index[$level] = 1;
// Alert to down a level after next operand
$down[$level] = true;
goto PARSE_NEXT_ARGUMENT_NO_DOWN;
} // operator
else
$array[$level][$index[$level]] = [0 => 1, 1 => $buffer];
if ($pointer == $length)
goto PARSE_END_OF_ARGUMENTS;

while ($char == 9 or $char == 10 or $char == 13 or $char == 32 or $char == 44) // tab 9 lf 10 cr 13 espace 32 , 44
{ // empty space
$pointer++;
if ($pointer == $length)
goto PARSE_END_OF_ARGUMENTS;
$char = ord ($_string[$pointer]);
} // empty space

if ($char == 40)
{ // ( level up
$pointer++;
if ($pointer == $length)
goto PARSE_END_OF_ARGUMENTS;
$level++;
$index[$level] = 0;
$array[$level] = [];

$char = ord ($_string[$pointer]);
goto PARSE_NEXT_ARGUMENT;
} // ( level up

$index[$level]++;
$char = ord ($_string[$pointer]);
goto PARSE_NEXT_ARGUMENT;
} // function

if ($char == 40)
{ // ( level up
$array[$level][$index[$level]] = [0 => 1, 1 => ''];
$level++;
$index[$level] = 0;
$array[$level] = [];
$pointer++;
if ($pointer == $length)
goto PARSE_END_OF_ARGUMENTS;

$char = ord ($_string[$pointer]);
goto PARSE_NEXT_ARGUMENT;
} // ( level up

if ($char == 41)
{ // ) level down
$level--;
if (!$level)
goto PARSE_END_OF_ARGUMENTS;
$array[$level][$index[$level]][2] = $array[$level + 1];
$index[$level]++;
while (isset ($down[$level]))
{ // operator level down
unset ($down[$level]);
$level--;
if (!$level)
goto PARSE_END_OF_ARGUMENTS;
$array[$level][$index[$level]][2] = $array[$level + 1];
$index[$level]++;
} // operator level down
$pointer++;
if ($pointer == $length)
goto PARSE_END_OF_ARGUMENTS;
$char = ord ($_string[$pointer]);
goto PARSE_NEXT_ARGUMENT;
} // ) level down

if ($char == 96)
{ // ` string
$pointer++;
if ($pointer == $_length)
goto PARSE_END_OF_STRING;

$pos = strpos ($_string, '`', $pointer);
if ($pos === false)
goto PARSE_END_OF_STRING;

$array[$level][$index[$level]] = [0 => 0, 1 => substr ($_string, $pointer, $pos - $pointer)];
$index[$level]++;
if ($pos > $length)
{ // new ending
$pointer = $pos + 1;
$_pointer = $pointer;
$_offset = strcspn ($_string, ';{]', $pointer);
if (!$_offset)
goto PARSE_END_OF_ARGUMENTS;
$length = $pointer + $_offset;
} // new ending
else
$pointer = $pos + 1;

if ($pointer == $length)
goto PARSE_END_OF_ARGUMENTS;
$char = ord ($_string[$pointer]);
goto PARSE_NEXT_ARGUMENT;
} // ` string

if ($char == 33)
{ // ! operator
$pointer++;
if ($pointer == $length)
goto PARSE_END_OF_ARGUMENTS;
$char = ord ($_string[$pointer]);
$array[$level][$index[$level]] = [0 => 5, 1 => '!'];
$level++;
$array[$level] = [];
$index[$level] = 0;
// Alert to down a level after next operand
$down[$level] = true;
goto PARSE_NEXT_ARGUMENT_NO_DOWN;
} // ! operator

if ($char == 38 or $char == 42 or $char == 43 or $char == 45 or $char == 47 or $char == 60 or $char == 61 or $char == 62)
{ // = + - / * += -= /= *= == & | operadores
$buffer = $_string[$pointer];
$pointer++;
if ($pointer == $length)
goto PARSE_END_OF_ARGUMENTS;
$char = ord ($_string[$pointer]);
if ($char == 38 or $char == 42 or $char == 43 or $char == 45 or $char == 47 or $char == 60 or $char == 61 or $char == 62)
{ // second operator
$buffer .= $_string[$pointer];
$pointer++;
if ($pointer == $length)
goto PARSE_END_OF_ARGUMENTS;
$char = ord ($_string[$pointer]);
} // second operator
if (!$index[$level])
goto PARSE_END_OF_ARGUMENTS;
$index[$level]--;
$operand = $array[$level][$index[$level]];
$array[$level][$index[$level]] = [0 => 4, 1 => $buffer];
// puts the operand into argument list
$level++;
$array[$level] = [0 => $operand];
$index[$level] = 1;
// Alert to down a level after next operand
$down[$level] = true;
goto PARSE_NEXT_ARGUMENT_NO_DOWN;
} // = + - / * += -= /= *= == & | operadores

// salta caractéres não reconhecidos até aqui
$pointer++;
if ($pointer < $length)
{ // unrecognized char
$char = ord ($_string[$pointer]);
goto PARSE_NEXT_ARGUMENT;
} // unrecognized char

PARSE_END_OF_ARGUMENTS:

while (isset ($down[$level]))
{ // operator level down
unset ($down[$level]);
$level--;
if (!$level)
break;
$array[$level][$index[$level]][2] = $array[$level + 1];
unset ($array[$level + 1]);
} // operator level down

if (strlen ($name) or $array[1])
{ // creates a new function

if ($name == 'noparse')
{ // noparse
$offset = strcspn ($_string, ']', $_pointer);
$_pointer += $offset + 1;
$end = strpos ($_string, '[/noparse', $_pointer);
if ($end === false)
$end = $_length;

$_array[$_level][$_index[$_level]] = [
0 => 0, 
1 => substr ($_string, $_pointer, $end), 
2 => [], 
4 => false
];

$_pointer = $end;
if ($_pointer == $_length)
goto PARSE_END_OF_STRING;
$_pointer += 9;
$_char = ord ($_string[$_pointer]);
if ($_pointer == $_length)
goto PARSE_END_OF_STRING;

goto PARSE_NEXT_INSTRUCTION;
} // noparse

$_array[$_level][$_index[$_level]] = [
0 => $type, 
1 => $name, 
2 => $array[1], 
4 => $silent
];

$_pointer += $_offset;
if ($_pointer == $_length)
goto PARSE_END_OF_STRING;
$_char = ord ($_string[$_pointer]);

if ($_char == 123)
{ // { level up
$_level++;
$_array[$_level] = [];
$_index[$_level] = 0;
$_pointer++;
if ($_pointer == $_length)
goto PARSE_END_OF_STRING;
$_char = ord ($_string[$_pointer]);
goto PARSE_NEXT_INSTRUCTION;
} // { level up
else
$_index[$_level]++;

goto PARSE_END_OF_INSTRUCTION;
} // creates a new function

$_pointer += $_offset;
if ($_pointer == $_length)
goto PARSE_END_OF_STRING;
$_char = ord ($_string[$_pointer]);

PARSE_END_OF_INSTRUCTION:

if ($_char == 59)
{ // ; end of instruction
$_pointer++;
if ($_pointer == $_length)
goto PARSE_END_OF_STRING;

$_char = ord ($_string[$_pointer]);
goto PARSE_NEXT_INSTRUCTION;
} // ; end of instruction

if ($_char == 93)
{ // ]
$_pointer++;
if ($_pointer == $_length)
goto PARSE_END_OF_STRING;

if (ord ($_string[$_pointer]) == 13)
{ // cr
$_pointer++;
if ($_pointer == $_length)
goto PARSE_END_OF_STRING;
} // cr

if (ord ($_string[$_pointer]) == 10)
{ // lf
$_pointer++;
if ($_pointer == $_length)
goto PARSE_END_OF_STRING;
} // lf

goto PARSE_TAG_SEEK;
} // ]

// unrecognized char
$_pointer++;
if ($_pointer == $_length)
goto PARSE_END_OF_STRING;

$_char = ord ($_string[$_pointer]);
goto PARSE_NEXT_INSTRUCTION;

PARSE_END_OF_STRING:

if ($parseOnly)
return $_array[1];

$this->render_tags_level ($_array[1]);
} // function render_tags

public function render_tags_level ($array)
{ // function render_tags_level
$length = count ($array);
if (!$length)
return;
$index = 0;
goto LEVEL_NEXT_INSTRUCTION_1;

LEVEL_NEXT_INSTRUCTION:
$index++;
if ($index == $length)
return;

LEVEL_NEXT_INSTRUCTION_1:
$current = $array[$index];

if (!isset ($current[1]))
goto LEVEL_NEXT_INSTRUCTION;

if ($current[0] == 0)
{ // buffer
$this->buffer .= $current[1];
goto LEVEL_NEXT_INSTRUCTION;
} // buffer

if (isset ($current[2]) and $current[2])
$arguments = $this->render_tags_arguments ($current[2]);
else
$arguments = [];

switch ($current[1])
{ // switch function name

case 'if':
if ($arguments and ( (is_string ($arguments[0]) and strlen ($arguments[0]) and $arguments[0] != '0') or (!is_string ($arguments[0]) and $arguments[0])
))
{ // if condition evaluates true
if (isset ($current[3]) and $current[3])
$this->render_tags_level ($current[3]);
goto LEVEL_NEXT_INSTRUCTION;
} // if condition evaluates true

while ($index + 1 != $length and isset ($array[$index + 1][1]) and $array[$index + 1][1] == 'elseif')
{ // loop elseif
$current = $array[$index + 1];
if (isset ($current[2]))
{ // conditions exists
$arguments = $this->render_tags_arguments ($current[2]);
if ($arguments and $arguments[0])
{ // elseif condition evaluates true
if (isset ($current[3]) and $current[3])
$this->render_tags_level ($current[3]);
$index++;
goto LEVEL_NEXT_INSTRUCTION;
} // elseif condition evaluates true
} // conditions exists
$index++;
} // loop elseif

if ($index + 1 != $length and isset ($array[$index + 1][1]) and $array[$index + 1][1] == 'else')
{ // else
$current = $array[$index + 1];
if (isset ($current[3]) and $current[3])
$this->render_tags_level ($current[3]);
$index++;
} // else

goto LEVEL_NEXT_INSTRUCTION;

case 'elseif':
case 'else':
goto LEVEL_NEXT_INSTRUCTION;

case 'loop':
do
{ // loop
if (isset ($current[3]) and $current[3])
$this->render_tags_level ($current[3]);
} // loop
while (eclTag_loop::render ($this));
goto LEVEL_NEXT_INSTRUCTION;

case '':
if (isset ($arguments[0]) and (!isset ($current[4]) or !$current[4]))
$this->buffer .= strval ($arguments[0]);
goto LEVEL_NEXT_INSTRUCTION;

default:
if (!preg_match ('/^[a-z][a-z0-9_]+$/', $current[1]))
goto LEVEL_NEXT_INSTRUCTION;

$class = 'eclTag_' . $current[1];
if ($current[0] == 1)
$local = $class::render ($this, $arguments);
else
$local = $class::close ($this);

if (!is_array ($local) or !$local)
{ // empty
if ($index + 1 != $length and isset ($array[$index + 1][1]) and $array[$index + 1][1] == 'else')
{ // else
$current = $array[$index + 1];
if (isset ($current[3]) and $current[3])
$this->render_tags_level ($current[3]);
$index++;
} // else

goto LEVEL_NEXT_INSTRUCTION;
} // empty

if (isset ($current[4]) and $current[4])
goto LEVEL_NEXT_INSTRUCTION;

if (isset ($current[3]) and $current[3])
{ // not empty
$this->levelUp ($local);
$this->render_tags_level ($current[3]);
$this->levelDown ();
goto LEVEL_NEXT_INSTRUCTION;
} // not empty

if (isset ($local['parsed']))
{ // parsed content
$this->levelUp ($local);
$this->render_tags_level ($local['parsed']);
$this->levelDown ();
goto LEVEL_NEXT_INSTRUCTION;
} // parsed content

if (isset ($local['html'][0]))
{ // render html
$this->levelUp ($local);
$this->render_tags ($local['html']);
$this->levelDown ();
goto LEVEL_NEXT_INSTRUCTION;
} // render html

switch ($current[1])
{ // switch tag

case 'audio':
case 'box':
case 'dinamic':
case 'file':
case 'html':
case 'img':
case 'mod':
case 'table':
case 'video':
$this->levelUp ($local);
// eclRender_renderBorders_none::render ($this);
$border = $this->block ('borders/default');
$this->render ($border['html']);
$this->levelDown ();
goto LEVEL_NEXT_INSTRUCTION;
} // switch tag
} // switch function name
goto LEVEL_NEXT_INSTRUCTION;
} // function render_tags_level

public function getVar ($name)
{ // function getVar
if ($name[0] == '$')
$name = substr ($name, 1);

for ($watchDog = 10; $watchDog; $watchDog--)
{ // loop search value
$value = $this->getVarSearch ($name);
if (!is_string ($value))
return $value;
if (!isset ($value[0]))
return $value;
if ($value[0] != '$')
return $value;
$name = substr ($value, 1);
} // loop search value
} // function getVar

private function getVarSearch ($name)
{ // function getVarSearch
global $system;

$parts = explode ('.', $name);

if (count ($parts) == 1)
{ // local scope
if (isset ($this->data[$name]))
return $this->data[$name];

if (isset ($this->data['text'][$name]))
return $this->data['text'][$name];

if (isset ($this->data['local'][$name]))
return $this->data['local'][$name];

if (is_array ($this->data))
{ // control mode
if (isset ($this->data[$name]))
return $this->data[$name];

if (isset ($this->data['text'][$name]))
return $this->data['text'][$name];

if (isset ($this->data['local'][$name]))
return $this->data['local'][$name];
} // control mode

if (is_object ($this->data))
{ // application mode
} // application mode

return '';
} // local scope

list ($prefix, $name) = $parts;

switch ($prefix)
{ // switch scope name
case 'mod':
if (isset ($this->mod[$name]))
return $this->mod[$name];

if (isset ($this->mod['text'][$name]))
return $this->mod['text'][$name];

if (isset ($this->mod['local'][$name]))
return $this->mod['local'][$name];

return '';

case 'system':
$me = $system;
break;

case 'user':
case 'subscription':
if ($this->document->pathway[0] != SYSTEM_PROFILES_URI and !isset ($document->data['user_data_access_enabled']))
{ // prevent access to user data
switch ($name)
{ // switch name
case 'caption':
case 'title':
break;

default:
return 'Access denied for user data';
} // switch name
} // prevent access to user data

case 'domain':
case 'home':
$me = $this->document->$prefix;
break;

case 'document':
$me = $this->document;
break;

case 'session':
if (isset ($this->document->session[$name]))
return $this->document->session[$name];

if (isset ($this->document->session['text'][$name]))
return $this->document->session['text'][$name];

if (isset ($this->document->session['local'][$name]))
return $this->document->session['local'][$name];

return '';

default:
return '';
} // switch scope name

if (isset ($me->data[$name]))
return $me->data[$name];

if (isset ($me->data['text'][$name]))
return $me->data['text'][$name];

if (isset ($me->data['local'][$name]))
return $me->data['local'][$name];

return '';
} // function getVarSearch

private function render_tags_arguments ($array)
{ // function render_tags_arguments
$arguments = [];
foreach ($array as $current)
{ // each argument
switch ($current[0])
{ // switch argument type
case 0:
case 3:
$arguments[] = $current[1];
break;

case 1:
if (isset ($current[2]) and $current[2])
$sub = $this->render_tags_arguments ($current[2]);
else
$sub = [];

if ($current[1] == '')
{ // anonimous function
if (isset ($sub[0]))
$arguments[] = $sub[0];
} // anonimous function
else
{ // sub function
if (preg_match ('/^[a-z][a-z0-9_]*$/', $current[1]))
{ // valid function
$func = 'eclTag_' . $current[1];
$arguments[] = $func::render ($this, $sub);
} // valid function
} // sub function
break;

case 2:
$arguments[] = $this->getVar ($current[1]);
break;

case 4:
if (!isset ($current[2]) or !$current[2])
break;
$sub = $this->render_tags_arguments ($current[2]);
if (count ($sub) < 2)
break;

switch ($current[1])
{ // switch operator
case 'and':
if ($sub[0] and $sub[1])
$arguments[] = 1;
else
$arguments[] = 0;
break;

case 'or':
if ($sub[0])
$arguments[] = $sub[0];
elseif ($sub[1])
$arguments[] = $sub[1];
else
$arguments[] = '';
break;
} // switch operator
break;

case 5:
if (!isset ($current[2]) or !$current[2])
break;
$sub = $this->render_tags_arguments ($current[2]);
if (!count ($sub))
break;
switch ($current[1])
{ // switch operator
case '!':
if ($sub[0])
$arguments[] = 0;
else
$arguments[] = 1;
} // switch operator
break;
} // switch argument type
} // each argument
return $arguments;
} // function render_tags_arguments

public function toKeyword ($key)
{ // function toKeyword
static $convert = ['a' => 'a', 'b' => 'b', 'c' => 'c', 'd' => 'd', 'e' => 'e', 'f' => 'f', 'g' => 'g', 'h' => 'h', 'i' => 'i', 'j' => 'j', 'k' => 'k', 'l' => 'l', 'm' => 'm', 'n' => 'n', 'o' => 'o', 'p' => 'p', 'q' => 'q', 'r' => 'r', 's' => 's', 't' => 't', 'u' => 'u', 'v' => 'v', 'w' => 'w', 'x' => 'x', 'y' => 'y', 'z' => 'z', 'A' => 'a', 'B' => 'b', 'C' => 'c', 'D' => 'd', 'E' => 'e', 'F' => 'f', 'G' => 'g', 'H' => 'h', 'I' => 'i', 'J' => 'j', 'K' => 'k', 'L' => 'l', 'M' => 'm', 'N' => 'n', 'O' => 'o', 'P' => 'p', 'Q' => 'q', 'R' => 'r', 'S' => 's', 'T' => 't', 'U' => 'u', 'V' => 'v', 'W' => 'w', 'X' => 'x', 'Y' => 'y', 'Z' => 'z', 'â' => 'a', 'Â' => 'a', 'ã' => 'a', 'Ã' => 'a', 'á' => 'a', 'Á' => 'a', 'à' => 'a', 'À' => 'a', 'ä' => 'a', 'Ä' => 'a', 'Ç' => 'c', 'ç' => 'c', 'ê' => 'e', 'Ê' => 'e', 'é' => 'e', 'É' => 'e', 'ë' => 'e', 'Ë' => 'e', 'î' => 'i', 'Î' => 'i', 'í' => 'i', 'Í' => 'i', 'ï' => 'i', 'Ï' => 'i', 'ô' => 'o', 'Ô' => 'o', 'õ' => 'o', 'Õ' => 'o', 'ó' => 'o', 'Ó' => 'o', 'ö' => 'o', 'Ö' => 'o', 'û' => 'u', 'Û' => 'u', 'ú' => 'u', 'Ú' => 'u', 'ü' => 'u', 'Ü' => 'u', 'Ý' => 'y', 'ý' => 'y', 'ÿ' => 'y', 'Ÿ' => 'y', '-' => '-', '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '0' => '0', '_' => '_', ' ' => '_'];

// The $last will remember the last character of the convertion
// to prevent duplicated spacing "__" or "--" in the name

$result = '';
$last = ' ';
foreach (str_split ($key) as $char)
{ // each char
if (!isset ($convert[$char]))
continue;
$valid = $convert[$char];
if ($valid != $last)
{ // not repeated separator
if ($valid == '-' or $valid == ' ' or $valid == '_')
$last = $valid;
else
$last = '';
$result .= $valid;
} // not repeated separator
} // each char

return trim ($result, '-_');
} // function toKeyword

public function block ($name)
{ // function block
global $store;
static $opened = false;

if (!strpos ($name, '/'))
return [];

if (!$opened and !$this->blocks)
{ // open
$opened = true;
if ($this->document->domain->domainId)
{ // open template

$children = $store->domainExtras->children ($this->document->domain->domainId, MODE_TEMPLATE, 0);
foreach ($children as $data)
{ // each block
$this->blocks[$data['name']] = $data;
} // each block
} // open template
} // open

if (isset ($this->blocks[$name]))
return $this->blocks[$name];

return $store->control->read ($name);
} // function block

public function stackPop ($type)
{ // function stackPop
$exists = false;
foreach ($this->tagsStack as $item)
{ // each stack item
if ($item[0] == $type)
{ // exists
$exists = true;
break;
} // exists
} // each item
if (!$exists)
return false;

do
{ // loop each level
if (!$this->tagsStack)
return false;

$item = end ($this->tagsStack);
if ($item[0] == $type)
{ // found
$item = array_pop ($this->tagsStack);
return $item[1];
} // found
$class = 'eclTag_' . $item[0];
$class::close ($render, []);
} // each stack item
while (true);
} // function stackPop

public function markdown2html (&$content)
{ // function markdown2html
$string = str_replace (CRLF, LF, $content[TEXT_CONTENT]);
$string = str_replace (CR, LF, $string);
$string = str_replace (CHR_TAB, "    ", $string);

if (!isset ($content[TEXT_HTML]))
$this->markdownHtmlEnable = 0;
else
$this->markdownHtmlEnable = $content[TEXT_HTML];

$this->markdownBuffer = '';
$tree = [];
$pointer = 0;
$length = strlen ($string);
while ($tree[] = $this->parseSublevel ($string, $pointer, $length));

$this->renderMarkdownElements ($tree);
$content[TEXT_CONTENT] = $this->markdownBuffer;
$this->markdownBuffer = '';
} // function markdown2html

private function renderMarkdownElements ($tree)
{ // function renderMarkdownElements
$length = count ($tree);
for ($index = 0; $index < $length; $index++)
{ // each element
$element = $tree[$index];
if (!$element)
continue;

switch ($element[0])
{ // switch tag
case 'h1':
case 'h2':
case 'h3':
case 'h4':
case 'h5':
case 'h6':
$buffer = $element[1];

/*
while ($index + 1 < $length and $tree[$index + 1][0] == 'p')
{ // merge lasy paragraphs
$index ++;
$buffer .= ' ' . $tree[$index][1];
} // merge lasy paragraphs
*/

$trimLength = strspn (strrev ($buffer), ' ' . $element[2]);
if ($trimLength and $buffer[strlen($buffer) - $trimLength] == ' ')
$buffer = substr ($buffer, 0, -$trimLength);

$buffer = $this->parseInline ($buffer);

if ($element[2] == '#')
{ // append to summary
$id = $this->document->mod->summary->appendTitle ($this->stripTags ($buffer), $element[0]);
$this->markdownBuffer .= '<a name=' . QUOT . $id . QUOT . '></a>';
} // append to summary

$this->markdownBuffer .= '<' . $element[0] . '>' . $buffer . '</' . $element[0] . '>' . CRLF;
break;

case 'p':
$buffer = $element[1];
while ($index + 1 < $length and $tree[$index + 1][0] == 'p')
{ // merge lasy paragraphs
$index ++;
$buffer .= LF . $tree[$index][1];
} // merge lasy paragraphs
$buffer = $this->parseInline ($buffer);

if ($index + 1 < $length and $tree[$index + 1][0] == '===')
{ // level 1 heading
$index ++;
$id = $this->document->mod->summary->appendTitle ($this->stripTags ($buffer), 'h1');
$this->markdownBuffer .= '<a name=' . QUOT . $id . QUOT . '></a>';
$this->markdownBuffer .= '<h1>' . $buffer . "</h1>" . CRLF;
} // level 1 heading
elseif ($index + 1 < $length and $tree[$index + 1][0] == '---')
{ // level 2 heading
$index ++;
$id = $this->document->mod->summary->appendTitle ($this->stripTags ($buffer), 'h2');
$this->markdownBuffer .= '<a name=' . QUOT . $id . QUOT . '></a>';
$this->markdownBuffer .= '<h2>' . $buffer . "</h2>" . CRLF;
} // level 2 heading
else
$this->markdownBuffer .= '<p>' . $buffer . "</p>" . CRLF;
break;

case 'pre':
$this->markdownBuffer .= '<pre>' . str_replace (['&', '<', QUOT, '['], ['&amp;', '&lt;', '&quot;', '[]'], $element[1]) . '</pre>' . CRLF;
break;

case 'ecl':
$this->markdownBuffer .= $element[1];
break;

case '___':
case '***':
case '---':
case '===':
$this->markdownBuffer .= "<hr>" . CRLF;
break;

case 'blockquote':
while ($index + 1 < $length and ($tree[$index + 1][0] == 'p' or $tree[$index + 1][0] == 'blockquote'))
{ // merge lasy paragraphs and blocquotes
$index ++;
if ($tree[$index][0] == 'p')
$element[1][] = $tree[$index];
else
{ // merge next blockquote
foreach ($tree[$index][1] as $nextElement)
{ // merge
$element[1][] = $nextElement;
} // merge
} // merge next blockquote
} // merge lasy paragraphs and blockquotes

$this->markdownBuffer .= "<blockquote>" . CRLF;
$this->renderMarkdownElements ($element[1]);
$this->markdownBuffer .= "</blockquote>" . CRLF;
break;

case 'table':
list ($prefix, $rows, $maxCols, $header, $hifensMax) = $element;
if (!$rows or !$maxCols)
break;
$buffer = "<table>" . CRLF;

$buffer .= '<colgroup>' . CRLF;
for ($c = 0; $c < $maxCols; $c ++)
{ // each col
$buffer .= '<col';
if (isset ($header[$c]))
$buffer .= ' style="text-align:' . $header[$c][0] . '; width:' . round (100 * ($header[$c][1] / $hifensMax)) . '%' . QUOT;

$buffer .= '>' . CRLF;
} // each col
$buffer .= '</colgroup>' . CRLF;

$row = array_shift ($rows);
if ($row)
{ // header exists
$buffer .= '<thead>' . CRLF;
$buffer .= '<tr>' . CRLF;
for ($c = 0; $c < $maxCols; $c ++)
{ // each cell
$buffer .= "<th>";
if (isset ($row[$c]))
$buffer .= $this->parseInline ($row[$c]);
else
$buffer .= ' &nbsp; ';
$buffer .= "</th>";
} // each cell
$buffer .= '</tr>' . CRLF;
$buffer .= "</thead>" . CRLF;
} // valid header

$buffer .= "<tbody>" . CRLF;
foreach ($rows as $row)
{ // each row
$buffer .= "<tr>";
for ($c = 0; $c < $maxCols; $c ++)
{ // each cell
$buffer .= "<td>";
if (isset ($row[$c]))
$buffer .= $this->parseInline ($row[$c]);
else
$buffer .= ' &nbsp; ';
$buffer .= "</td>";
} // each cell
$buffer .= "</tr>" . CRLF;
} // each row
$buffer .= '</tbody>' . CRLF;
$buffer .= "</table>" . CRLF;

$this->markdownBuffer .= $buffer;
break;

case '-':
case '*':
case '+':
$counter = 0;
$list = [$element];
while ($index + 1 < $length and ($tree[$index + 1][0] == 'p' or $tree[$index + 1][0] == $element[0] or $tree[$index + 1][0] == 'nl'))
{ // merge lasy paragraphs and nexted listItems
if ($tree[$index + 1][0] == 'p' and $tree[$index][0] == 'nl')
break;

$index ++;
if ($tree[$index][0] == 'p' or $tree[$index][0] == 'nl')
$list[$counter][1][] = $tree[$index];
else
{ // merge next list item
$counter ++;
$list[$counter] = $tree[$index];
} // merge next list item
} // merge lasy paragraphs and nexted list items

$this->markdownBuffer .= "<ul>" . CRLF;
foreach ($list as $element)
{ // each list item
$this->markdownBuffer .= "<li>" . CRLF;
$this->renderMarkdownElements ($element[1]);
$this->markdownBuffer .= "</li>" . CRLF;
} // each list item
$this->markdownBuffer .= "</ul>" . CRLF;
break;

case 'a':
case 'I':
case '1':
$counter = 0;
$list = [$element];
while ($index + 1 < $length and ($tree[$index + 1][0] == 'p' or $tree[$index + 1][0] == $element[0] or $tree[$index + 1][0] == 'nl'))
{ // merge lasy paragraphs and nexted listItems
if ($tree[$index + 1][0] == 'p' and $tree[$index][0] == 'nl')
break;

$index ++;
if ($tree[$index][0] == 'p' or $tree[$index][0] == 'nl')
$list[$counter][1][] = $tree[$index];
else
{ // merge next list item
$counter ++;
$list[$counter] = $tree[$index];
} // merge next list item
} // merge lasy paragraphs and nexted list items

$this->markdownBuffer .= '<ol style="list-style-type:';
if ($element[0] == 'a')
$this->markdownBuffer .= 'lower-alpha';
elseif ($element[0] == 'I')
$this->markdownBuffer .= 'upper-roman';
else
$this->markdownBuffer .= 'decimal';
$this->markdownBuffer .= QUOT . '>' . CRLF;
foreach ($list as $element)
{ // each list item
$this->markdownBuffer .= "<li>" . CRLF;
$this->renderMarkdownElements ($element[1]);
$this->markdownBuffer .= "</li>" . CRLF;
} // each list item
$this->markdownBuffer .= "</ol>" . CRLF;
break;

case '|':
$buffer = '';
foreach ($element[1] as $line)
{ // each line
if ($buffer == '')
$buffer .= $this->parseInline ($line);
else
$buffer .= "<br>" . CRLF . $this->parseInline ($line);
} // each line

$this->markdownBuffer .= '<p>' . $buffer . "</p>" . CRLF;
break;

case 'script':
case 'style':
$this->markdownBuffer .= '[' . $element[0] . ']' . $element[1] . '[/' . $element[0] . ']';
break;

case 'raw':
$this->markdownBuffer .= $element[1];
break;

case 'jump':
$this->markdownBuffer .= str_replace ('[', '[]', $element[1]);
break;
} // switch tag
} // each element
} // function renderMarkdownElements

private function parseSublevel ($string, &$pointer, $length)
{ // function parseSublevel
$previous = 1;
$char = '';
while (true)
{ // loop empty lines
if ($pointer >= $length)
return false;

$previous ++;
$leadingSpaces = strspn ($string, ' ', $pointer);
$lineLength = strcspn ($string, LF, $pointer);
if ($lineLength - $leadingSpaces == 0)
{ // 0 chars line
$pointer += $lineLength + 1;
return ['nl'];
} // 0 chars line

if ($string[$pointer + $leadingSpaces] == '[' and $item = $this->parseDefinition ($string, $pointer, $leadingSpaces, $length))
return $item;
if ($string[$pointer + $leadingSpaces] == '>')
return $this->parseBlockquote ($string, $pointer, $leadingSpaces, $length);

if ($lineLength - $leadingSpaces < 3)
return $this->parseParagraph($string, $pointer, $leadingSpaces, $previous);

$prefix = substr ($string, $pointer + $leadingSpaces, 3);
if (($prefix == '```' or $prefix == '~~~') and $item = $this->parseCodeFence ($string, $pointer, $leadingSpaces, $length))
return $item;
if ($leadingSpaces > 3 and $item = $this->parsePre ($string, $pointer, $length))
return $item;
if (($prefix == '---' or $prefix == '___' or $prefix == '***') and $item = $this->parseTematicBreak ($string, $pointer, $leadingSpaces, $prefix, $length))
return $item;
if ($prefix == '#. ' and $item = $this->parseOrderedList ($string, $pointer, $leadingSpaces, $char, $length))
return $item;

$char = $prefix[0];
switch ($char)
{ // switch char
case '=':
case '#':
if ($item = $this->parseHeader ($string, $pointer, $leadingSpaces, $char, $previous))
return $item;
break;

case '<':
if ($item = $this->parseHtml ($string, $pointer, $leadingSpaces, $length))
return $item;
break;

case '|':
if ($item = $this->parseTable ($string, $pointer, $leadingSpaces, $length))
return $item;
if ($item = $this->parseLineBlock ($string, $pointer, $leadingSpaces, $length))
return $item;
break;

case '-':
case '*':
case '+':
case '@':
if ($item = $this->parseList ($string, $pointer, $leadingSpaces, $char, $length))
return $item;
break;

default:
if ($item = $this->parseOrderedList ($string, $pointer, $leadingSpaces, $length))
return $item;
} // switch char

return $this->parseParagraph  ($string, $pointer, $leadingSpaces, $previous);
} // loop empty lines
} // function parseSublevel

private function parseCodeFence ($string, &$pointer, $leadingSpaces, $length)
{ // function parseCodeFence
$fenceChar = $string[$pointer + $leadingSpaces];
$fenceLength = strspn ($string, $fenceChar, $pointer + $leadingSpaces);
$boundaryLength = $leadingSpaces + $fenceLength;
$boundary = substr ($string, $pointer, $boundaryLength);
$endPointer = strpos ($string, LF . $boundary, $pointer + $boundaryLength);
if ($endPointer === false)
return false;

$startPointer = strpos ($string, LF, $pointer + $boundaryLength) + 1;

$pointer = $endPointer + 1 + strcspn ($string, LF, $endPointer + 1);

$lines = [];
$code = substr ($string, $startPointer, $endPointer - $startPointer);
foreach (explode (LF, $code) as $line)
{ // trim each line
$indentLength = strspn ($line, ' ');
if ($indentLength == 0)
$lines[] = $line;
elseif ($indentLength < $leadingSpaces)
$lines[] = substr ($line, $indentLength);
else
$lines[] = substr ($line, $leadingSpaces);
} // trim each line

return ['pre', implode (LF, $lines)];
} // function parseCodeFence

private function parsePre ($string, &$pointer, $length)
{ // function parsePre 
$buffer = '';
while (true)
{ // loop lines
$lineLength = strcspn ($string, LF, $pointer);
$leadingSpaces = strspn ($string, " ", $pointer);
if ($lineLength - $leadingSpaces == 0)
$buffer .= LF;
elseif ($leadingSpaces >= 4)
$buffer .= substr ($string, $pointer + 4, $lineLength - 4) . LF;
else
break;

$pointer += $lineLength + 1;
if ($pointer >= $length)
break;
} // loop lines

$buffer = rtrim ($buffer);
return ['pre', $buffer];
} // function parsePre 

private function parseTematicBreak ($string, &$pointer, $leadingSpaces, $prefix, $length)
{ // function parseTematicBreak 
$eol = strcspn ($string, LF, $pointer);
$lineLength = strspn ($string, ' ' . $prefix[0], $pointer);
$pointer += $eol + 1;
return [$prefix];
} // function parseTematicBreak 

private function parseHeader ($string, &$pointer, $leadingSpaces, $char, &$previous)
{ // function parseHeader 
$level = strspn ($string, $char, $pointer + $leadingSpaces);
if ($level > 6)
return false;
if ($string[$pointer + $leadingSpaces + $level] != ' ')
return false;
$start = $pointer + $leadingSpaces + $level + 1;
$offset = strcspn ($string, LF, $start);
$line = substr ($string, $start, $offset);
$pointer = $start + $offset + 1;

return ['h' . $level, $line, $char];
} // function parseHeader 

private function parseHtml ($string, &$pointer, $leadingSpaces, $length)
{ // function parseHtml 
return false;
} // function parseHtml 

private function parseBlockquote ($string, &$pointer, $leadingSpaces, $length)
{ // function parseBlockquote 
if ($pointer + $leadingSpaces + 2 <= $length and $string[$pointer + $leadingSpaces + 1] == ' ')
$leadingSpaces ++;
$boundary = substr ($string, $pointer, $leadingSpaces + 1);
$offset = $leadingSpaces + 1;
$pointer += $offset;
$buffer = '';
while (true)
{ // find lines
$lineLength = strcspn ($string, LF, $pointer);
$buffer .= substr ($string, $pointer, $lineLength) . LF;
$pointer += $lineLength + 1;
if (substr ($string, $pointer, $offset) != $boundary)
break;
$pointer += $offset;
} // find lines
$buffer = rtrim ($buffer);
$nextPointer = 0;
$nextLength = strlen ($buffer);
$nextItems = [];
while ($item = $this->parseSublevel ($buffer, $nextPointer, $nextLength))
{ // parse
$nextItems[] = $item;
} // parse
return ['blockquote', $nextItems];
} // function parseBlockquote 

private function parseTable ($string, &$pointer, $leadingSpaces, $length)
{ // function parseTable 
$initialPointer = $pointer;
$boundary = substr ($string, $pointer, $leadingSpaces + 1);
$boundaryLength = $leadingSpaces + 1;
$pointer += $boundaryLength;
$lines = [];
while (true)
{ // find lines
$lineLength = strcspn ($string, LF, $pointer);
$lines[] = substr ($string, $pointer, $lineLength);
$pointer += $lineLength + 1;
if ($pointer >= $length)
break;
if (substr ($string, $pointer, $boundaryLength) != $boundary)
break;
$pointer += $boundaryLength;
} // find lines

if (count ($lines) < 2 or count (explode ('|', trim ($lines[0], LF . " |"))) == 1)
{ // error
$pointer = $initialPointer;
return false;
} // error

$header = [];
$hifensTotal = 1;

if (isset ($lines[1]) and !strlen (trim ($lines[1], ' |+:-')))
{ // header
$line = $lines[1];
unset ($lines[1]);
$line = str_replace ('+', '|', $line);
$line = trim ($line, " |");
$cols = explode ("|", $line);
foreach ($cols as $th)
{ // each table header
$hifensLength = 1;
$align = 'auto';
$th = trim ($th);
if (isset ($th[0]))
{ // valid cell
if ($th[0] == ':' and substr ($th, -1) == ':')
{ // centered
$align = 'center';
$hifensLength = strlen ($th) - 2;
} // centered
elseif ($th[0] == ':')
{ // left
$align = 'left';
$hifensLength = strlen ($th) - 1;
} // left
elseif (substr ($th, -1) == ':')
{ // right
$align = 'right';
$hifensLength = strlen ($th) - 1;
} // right
else
$hifensLength = strlen ($th);

if ($hifensLength == 0)
$hifensLength = 1;
} // valid cell
$hifensTotal += $hifensLength;
$header[] = [$align, $hifensLength];
} // each table header
} // header

$table = [];
$maxCells = 0;
foreach ($lines as $line)
{ // each line
$line = trim ($line, ' |');
$cells = explode ('|', $line);
if (count ($cells) > $maxCells)
$maxCells = count ($cells);
$table[] = $cells;
} // each line

return ['table', $table, $maxCells, $header, $hifensTotal];
} // function parseTable 

private function parseLineBlock ($string, &$pointer, $leadingSpaces, $length)
{ // function parseLineBlock
if ($pointer + $leadingSpaces + 2 <= $length and $string[$pointer + $leadingSpaces + 1] == ' ')
$leadingSpaces ++;
$boundary = substr ($string, $pointer, $leadingSpaces + 1);
$offset = $leadingSpaces + 1;
$pointer += $offset;
$lines = [];
while (true)
{ // find lines
$lineLength = strcspn ($string, LF, $pointer);
$line = substr ($string, $pointer, $lineLength);
$nbspLength = strspn ($line, ' ');
$line = str_repeat ('&nbsp;', $nbspLength) . substr ($line, $nbspLength);
$lines[] = $line;
$pointer += $lineLength + 1;
if (substr ($string, $pointer, $offset) != $boundary)
break;
$pointer += $offset;
} // find lines

return ['|', $lines];
} // function parseLineBlock

private function parseDefinition ($string, &$pointer, $leadingSpaces, $length)
{ // function parseDefinition
if ($pointer + $leadingSpaces + 1 == $length)
return false;

switch ($string[$pointer + $leadingSpaces + 1])
{ // switch first character
case '[': // [[key]]
case ']': // []
case '^': // [^reference]
case '*': // [*footnote]
case '_': // [_cifer]
return false;
} // switch first character

$offset = strcspn ($string, ']', $pointer + $leadingSpaces + 1);
if ($pointer + $leadingSpaces + 1 + $offset + 1 == $length or $string[$pointer + $leadingSpaces + 1 + $offset + 1] == LF)
{ // ecl tag
$startPointer = $pointer + $leadingSpaces + 1;
$pointer += $leadingSpaces + 1 + $offset;
$encapsuled = substr ($string, $startPointer, $pointer - $startPointer);
switch ($encapsuled)
{ // switch encapsuled
case 'script':
case 'style':
case 'raw':
case 'jump':
$closeTag = '[/' . $encapsuled . ']';
$endPointer = strpos ($string, $closeTag, $pointer);
if ($endPointer === false)
return false;

$tag = $encapsuled;
$encapsuled = substr ($string, $pointer, $endPointer - $pointer);
$pointer = $endPointer + strlen ($closeTag);
return [$tag, $encapsuled];
} // switch encapsuled
$pointer ++;
return ['ecl', '[' . $encapsuled . ']'];
} // ecl tag

$char = $string[$pointer + $leadingSpaces + $offset + 1];

if ($char == '[' or $char == '(')
return false;

if ($char == ':')
{ // definition
$start = $pointer + $leadingSpaces + 1;
$end = $pointer + $leadingSpaces + $offset;
$identifier = substr ($string, $start, $end - $start);
$pointer = $end + 2;
$offset = strcspn ($string, LF, $pointer);
$line = trim (substr ($string, $pointer, $offset));
if (!strlen ($line))
{ // next line
$pointer += $offset + 1;
$offset = strcspn ($string, LF, $pointer);
$line = trim (substr ($string, $pointer, $offset));
} // next line
if (!isset ($this->markdownReferences[$identifier]))
$this->markdownReferences[$identifier] = $line;
return ['nl'];
} // definition

return false;
} // function parseReference 

private function parseList ($string, &$pointer, $leadingSpaces, $char, $length)
{ // function parseList 
if ($pointer + $leadingSpaces + 2 >= $length  or $string[$pointer + $leadingSpaces + 1] != ' ')
return false;

$indentLength = strspn ($string, " " . $char, $pointer);
$lineLength = strcspn ($string, LF, $pointer);
if ($indentLength == $lineLength)
return false;

$buffer = substr ($string, $pointer + $indentLength, $lineLength - $indentLength) . LF;
while (true)
{ // find lines
$pointer += $lineLength + 1;
if ($pointer + 1 >= $length)
break;
$nextIndentLength = strspn ($string, " ", $pointer);
$lineLength = strcspn ($string, LF, $pointer);

if ($lineLength - $nextIndentLength == 0)
$buffer .= LF;
elseif ($nextIndentLength >= $indentLength)
$buffer .= substr ($string, $pointer + $indentLength, $lineLength - $indentLength) . LF;
else
break;
} // find lines

if (substr ($buffer, -2) == LF . LF)
$pointer -= 2;
$buffer = rtrim ($buffer);


$nextPointer = 0;
$nextLength = strlen ($buffer);
$nextItems = [];
while ($item = $this->parseSublevel ($buffer, $nextPointer, $nextLength))
{ // parse
$nextItems[] = $item;
} // parse
return [$char, $nextItems];
} // function parseList 

private function parseOrderedList ($string, &$pointer, $leadingSpaces, $length)
{ // function parseOrderedList
$lineLength = strcspn ($string, LF, $pointer);
if ($lineLength - $leadingSpaces == 0)
return false;

$line = substr ($string, $pointer, $lineLength);
if (!preg_match ('/^(' . CHR_BSLASH . 'W*([0-9]{1,3}|[#]|[a-z]|[IVXLCM]{1,8})([.]|[)])' . CHR_BSLASH . 'W+)/', $line, $match))
return false;

$indentLength = strlen ($match[1]);
if ($indentLength == $lineLength)
return false;

$buffer = substr ($line, $indentLength) . LF;
while (true)
{ // find lines
$pointer += $lineLength + 1;
if ($pointer + 1 >= $length)
break;
$nextIndentLength = strspn ($string, " ", $pointer);
$lineLength = strcspn ($string, LF, $pointer);

if ($lineLength - $nextIndentLength == 0)
$buffer .= LF;
elseif ($nextIndentLength >= $indentLength)
$buffer .= substr ($string, $pointer + $indentLength, $lineLength - $indentLength) . LF;
else
break;
} // find lines

if (substr ($buffer, -2) == LF . LF)
$pointer -= 2;
$buffer = rtrim ($buffer);
$nextPointer = 0;
$nextLength = strlen ($buffer);
$nextItems = [];
while ($item = $this->parseSublevel ($buffer, $nextPointer, $nextLength))
{ // parse
$nextItems[] = $item;
} // parse

if (preg_match ('/^[a-z]+$/', $match[2]))
return ['a', $nextItems];
if (preg_match ('/^[IVXLCM]+$/', $match[2]))
return ['I', $nextItems];
else
return ['1', $nextItems];
} // function parseOrderedList 

private function parseParagraph ($string, &$pointer, $leadingSpaces, &$previous)
{ // function parseParagraph 
$end = strcspn ($string, LF, $pointer + $leadingSpaces);
$line = substr ($string, $pointer + $leadingSpaces, $end);

$pointer += $leadingSpaces + $end + 1;
if ($previous == 1)
$last = true;
else
$last = false;
$previous = 0;
return ['p', $line, $last];
} // function parseParagraph 

private function parseInline ($string)
{ // parseInline
$length = strlen ($string);
$pointer = 0;
$buffer = '';

while (true)
{ // find special chars

$offset = strcspn ($string, CHR_BSLASH . '*_~^`[<', $pointer);
if ($offset)
$buffer .= substr ($string, $pointer, $offset);

$pointer += $offset;
if ($pointer == $length)
break;

$char = $string[$pointer];
$pointer ++;
if ($pointer < $length and $string[$pointer] == $char)
{ // double marker
$char .= $char;
$pointer ++;
if (($char == '**' or $char == '__' or $char == '``') and $pointer < $length and $string[$pointer] == $char[0])
{ // tripple marker
$char .= $char[0];
$pointer ++;
} // tripple marker
} // double marker
elseif ($pointer < $length and $char == '^' and $string[$pointer] == '[')
{ // inline comment
$char .= '[';
$pointer ++;
} // inline comment

if ($char[0] == CHR_BSLASH)
{ // escape string
if (strlen ($char) == 2)
{ // escape backslash
$buffer .= CHR_BSLASH;
continue;
} // escape backslash

if ($pointer == $length)
{ // hard line break
$buffer .= '<br>';
break;
} // hard line break

switch ($string[$pointer])
{ // switch escaped character
case LF:
$buffer .= '<br>';
break;

case ' ':
$buffer .= '&nbsp;';
break;

case '&':
$buffer .= '&amp;';
break;

case '<':
$buffer .= '&lt;';
break;

case '>':
$buffer .= '&gt;';
break;

case '[':
$buffer .= '[]';
break;

case 'n':
$buffer .= '<br>';

default:
$buffer .= $string[$pointer];
} // switch escaped character
$pointer ++;
continue;
} // escape string

if ($char == '[')
$closeChar = ']';
elseif ($char == '[[')
$closeChar = ']]';
elseif ($char == '<')
$closeChar = '>';
elseif ($char == '^[')
$closeChar = ']';
else
$closeChar = $char;

$endPointer = $pointer;
do
{ // jump escaped chars
$endPointer = strpos ($string, $closeChar, $endPointer);
if ($endPointer === false or $char == '_')
{ // not found
if ($char == '<')
$buffer .= '&lt;';
elseif ($char == '[')
$buffer .= '[]';
else
$buffer .= $char;
continue 2;
} // not found
if ($string[$endPointer - 1] != CHR_BSLASH)
break;
$endPointer ++;
} // jump escaped chars
while (true);

$encapsuled = substr ($string, $pointer, $endPointer - $pointer);
$pointer = $endPointer + strlen ($closeChar);

switch ($char)
{ // switch char
case '***':
case '___':
$buffer .= '<strong><em>' . $this->parseInline ($encapsuled) . '</em></strong>';
break;

case '**':
case '__':
$buffer .= '<strong>' . $this->parseInline ($encapsuled) . '</strong>';
break;

case '*':
$buffer .= '<em>' . $this->parseInline ($encapsuled) . '</em>';
break;

case '~~':
$buffer .= '<del>' . $this->parseInline ($encapsuled) . '</del>';
break;

case '```':
case '``':
case '`':
$buffer .= '<samp>' . trim(str_replace (['&', '<', QUOT, '['], ['&amp;', '&lt;', '&quot;', '[]'], $encapsuled)) . '</samp>';
break;

case '^^':
case '^':
$buffer .= '<sup>' . $this->parseInline ($encapsuled) . '</sup>';
break;

case '~':
$buffer .= '<sub>' . $this->parseInline ($encapsuled) . '</sub>';
break;

case '[[':
$buffer .= '[key:' . $encapsuled . ']';
break;

case '^[':
$id = $this->document->mod->footnotes->appendNote ($encapsuled);
$buffer .= '<sup><a name="footnote_return_' . $id . QUOT . '></a><a href="#footnote_' . $id . QUOT . '>' . $id . '</a></sup>';
break;

case '[':
if ($pointer < $length and $string[$pointer] == '(')
{ // find link
$endPointer = strpos ($string, ')', $pointer);
if ($endPointer !== false)
{ // found
$pointer ++;
$url = substr ($string, $pointer, $endPointer - $pointer);
$buffer .= '<a href=' . QUOT . $url . QUOT . '>' . $this->parseInline ($encapsuled) . '</a>';
$pointer = $endPointer + 1;
break;
} // found
} // find link
$buffer .= '[' . $encapsuled . ']';
break;

case '<':
if (preg_match ('/^[a-zA-Z0-9._-]+[@][a-zA-Z0-9._-]+$/', $encapsuled))
{ // mail
$buffer .= '[mailto:' . $encapsuled . ']';
break;
} // mail

list ($prefix) = explode (':', $encapsuled);

switch ($prefix)
{ // switch prefix
case 'http':
case 'https':
case 'mailto':
case 'ftp':
case 'tel':
$buffer .= '[' . $encapsuled . ']';
break 2;
} // switch prefix

if ($this->markdownHtmlEnable )
$buffer .= '<' . $encapsuled . '>';
else
$buffer .= '&lt;' . $encapsuled . '&gt;';
break;

} // switch char
} // find special chars

if ($pointer < $length)
$buffer .= substr ($string, $pointer);
return $buffer;
} // parseInline

private function stripTags ($string)
{ // function stripTags
$length = strlen ($string);
$pointer = 0;
$buffer = '';

while (true)
{ // find special chars

$offset = strcspn ($string, '<', $pointer);
if ($offset)
$buffer .= substr ($string, $pointer, $offset);

$pointer += $offset;
if ($pointer == $length)
break;

$endPointer = strpos ($string, '>', $pointer);
if ($endPointer === false)
break;

$pointer = $endPointer + 1;
} // find special chars
return $buffer;
} // function stripTags

} // class eclEngine_render

?>