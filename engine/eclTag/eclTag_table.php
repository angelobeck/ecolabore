<?php

class eclTag_table
{ // class eclTag_table

static function render ($render, $arguments)
{ // function render
if (isset ($arguments[0]))
$name = 'table_' . $arguments[0];
else
$name = 'table';

$render->tagsStack[] = array ('table', $name);

$render->scissorsIndex++;
$render->scissors[$render->scissorsIndex] = array (
'position' => strlen ($render->buffer), 
'name' => $name, 
);
} // function render

static function close ($render)
{ // function close
$name = $render->stackPop ('table');
if ($name === false)
return;

if (!$render->scissorsIndex)
return;

$position = $render->scissors[$render->scissorsIndex]['position'];
$render->scissorsIndex--;

$border = $render->block ('borders/table');
if (!isset ($border['html']))
return;

$cut = substr ($render->buffer, $position);
$render->buffer = substr ($render->buffer, 0, $position);

$data = $render->block ('modules/' . $name);
if (!$data)
$data = $render->block ('modules/table');

$data['html'] = $border['html'];
$data['local']['value'] = $cut;
return $data;
} // function close

} // class eclTag_table

?>