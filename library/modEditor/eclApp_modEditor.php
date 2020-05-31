<?php

class eclApp_modEditor
{ // class eclApp_modEditor

static function is_child ($me, $name)
{ // function is_child
if ($name == 'wysiwyg')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return array ('wysiwyg');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
if (!isset ($document->received['fields']))
return;

foreach (explode (' ', $document->received['fields']) as $from)
{ // each field
if (!isset ($from[0]))
continue;

$parts = explode ('_', $from);
$length = count ($parts);
if ($length == 1)
continue;

switch ($parts[1])
{ // switch target
case 'caption':
case 'title':
case 'description':
case 'content':
self::update_text ($document, $from);
break;

case 'box':
if ($length == 3)
self::update_box ($document, $from);
break;

case 'table':
if ($length == 5)
self::update_table ($document, $from);
break;
} // switch target
} // each field
} // function dispatch

static function &get_data ($document, $id)
{ // function &
global $store;
$empty = [];
$data = &$store->domainContent->openById ($document->domain->domainId, intval ($id));

if (!$data)
return $empty;

if ($data['mode'] == MODE_DOMAIN and $document->access (4, $document->domain->groups))
return $data;

$me = $document->domain->findChild ($data['name']);
if ($me == false)
return $empty;

if ($document->access (4, $me->groups))
return $data;

return $empty;
} // function &

static function update_text ($document, $from)
{ // function update_text
list ($id, $field) = explode ('_', $from);
$data = &self::get_data ($document, $id);
if (!$data)
return;

if (!isset ($data['text'][$field]))
$data['text'][$field] = [];

if (isset ($document->received[$from][0]))
{ // received
if ($field == 'content')
$data['text'][$field][$document->lang] = array (4 => 1, 5 => 2, 6 => 1);
else
$data['text'][$field][$document->lang] = [];

$data['text'][$field][$document->lang][1] = $document->received[$from];
if ($document->charset == 'ISO-8859-1')
$data['text'][$field][$document->lang][2] = 1;
} // received
else
unset ($data['text'][$field][$document->lang]);
} // function update_text

static function update_box ($document, $from)
{ // function update_box
list ($id, $target, $number) = explode ('_', $from);
$data = &self::get_data ($document, $id);
if (!$data)
return;

$target .= '_' . $number;

if (!isset ($data['extras'][$target]))
$data['extras'][$target] = [];

if (isset ($document->received[$from][0]))
{ // received
$data['extras'][$target]['content'][$document->lang] = array (
1 => $document->received[$from], 
4 => 1, 
5 => 2, 
6 => 1
);

if ($document->charset == 'ISO-8859-1')
$data['extras'][$target]['content'][$document->lang][2] = 1;
} // received
else
unset ($data['extras'][$target]['content'][$document->lang]);
} // function update_box

static function update_table ($document, $from)
{ // function update_table
static $saved = [];
list ($id, $target, $number) = explode ('_', $from);
if (isset ($saved[$id . '_table_' . $number]))
return;

$saved[$id . '_table_' . $number] = true;
$data = &self::get_data ($document, $id);
if (!$data)
return;

if (!isset ($data['extras']['table_' . $number]['table']))
return;

$received = $document->received;
$table = &$data['extras']['table_' . $number]['table'];
$numCols = 0;
foreach ($table as $row)
{ // each row
if (count ($row) > $numCols)
$numCols = count ($row);
} // each row

$numRows = count ($table);
$newTable = [];

for ($indexRow = 0; $indexRow < $numRows; $indexRow++)
{ // each row
for ($indexCol = 0; $indexCol < $numCols; $indexCol++)
{ // each column
if (isset ($received[$id . '_table_' . $number . '_' . $indexRow . '_' . $indexCol]))
$content = $received[$id . '_table_' . $number . '_' . $indexRow . '_' . $indexCol];
elseif (isset ($table[$indexRow][$indexCol]))
$content = $table[$indexRow][$indexCol];
else
$content = '';

$newTable[$indexRow][$indexCol] = $content;
} // each column
} // each row

$table = $newTable;
} // function update_table

} // class eclApp_modEditor

?>