<?php

class eclTag_rendertable
{ // class eclTag_rendertable

static function render ($render, $arguments)
{ // function render
$buffer = &$render->buffer;
$heading = $render->getVar ('table_heading');
$editable = $render->getVar ('mod.editable');
$id = $render->getVar ('mod.id');
$number = $render->getVar ('mod.number');

$buffer .= '<table';

// Table class
if (isset ($arguments[0]) and is_string ($arguments[0]))
$buffer .= ' class=' . QUOT . $arguments[0] . QUOT;

$buffer .= '>' . CRLF;

$rowIndex = 0;

foreach ($render->children as $row)
{ // each row

// Row class
$buffer .= '<tr class="row-' . $rowIndex . QUOT . '>';
$cellIndex = 0;
foreach ($row->children as $cell)
{ // each cell

if ($rowIndex < $heading)
$buffer .= '<th';
else
$buffer .= '<td';

// Rowspan
if (isset ($cell->data['rowspan']))
$buffer .= ' rowspan=' . QUOT . $cell->data['rowspan'] . QUOT;

// Colspan
if (isset ($cell->data['colspan']))
$buffer .= ' colspan=' . QUOT . $cell->data['colspan'] . QUOT;

// editable
if ($editable)
$buffer .= ' data-name=' . QUOT . $id . '_table_' . $number . '_' . $rowIndex . '_' . $cellIndex . QUOT . ' data-mode="stack"';

// Cell class
$class = 'col-' . $cellIndex . ' cell-' . $rowIndex . '-' . $cellIndex;
if (!isset ($cell->data['text']['caption']) and !isset ($cell->data['caption']))
$class .= ' empty';
if (isset ($cell->data['class']))
$class .= ' ' . $cell->data['class'];

// Cell style
if (isset ($cell->data['style']))
{ // style
$buffer .= ' style="';
foreach ($cell->data['style'] as $selector => $value)
{ // each selector
if (strpos ($value, ':'))
$buffer .= $value;
else
$buffer .= $selector . ':' . $value . '; ';
} // each selector
$buffer .= QUOT;
} // style

$buffer .= '>';

// Cell link
if (isset ($cell->data['url']))
$buffer .= '<a href=' . QUOT . $cell->data['url'] . QUOT . '>';

// Cell content
if (isset ($cell->data['caption']))
eclTag_text::render ($render, array ($cell->data['caption'], $editable));
elseif (isset ($cell->data['text']['caption']))
eclTag_text::render ($render, array ($cell->data['text']['caption'], $editable));
elseif ($editable)
eclTag_text::render ($render, array ('', $editable));

// End Cell link
if (isset ($cell->data['url']))
$buffer .= '</a>';

if ($rowIndex < $heading)
$buffer .= '</th>' . CRLF;
else
$buffer .= '</td>' . CRLF;

$cellIndex++;
} // each cell

$buffer .= '</tr>' . CRLF;
$rowIndex++;
} // each row

$buffer .= '</table>' . CRLF;
} // function render

static function close ($render)
{ // function close

} // function close

} // class eclTag_rendertable

?>