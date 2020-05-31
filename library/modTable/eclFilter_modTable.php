<?php

class eclFilter_modTable
{ // class eclFilter_modTable

static function create ($fieldName, $control, $formulary)
{ // function create
$document = $formulary->document;

if (!isset ($control['flags']['target']))
return;

$table = $formulary->appendChild ($control);
$table->data['name'] = $fieldName;

$array = $formulary->getField ($control['flags']['target']);
if (!is_array ($array) or !$array)
$array = array (array ('', ''), array ('', ''));

// Descobre o número máximo de células por linha
$maxCols = 0;
$maxRows = 0;
foreach ($array as $row)
{ // each row
if (!is_array ($row))
continue;

$maxRows++;
if (count ($row) > $maxCols)
$maxCols = count ($row);
} // each row

$formulary->hidden[$fieldName . '_maxRows'] = $maxRows;
$formulary->hidden[$fieldName . '_maxCols'] = $maxCols;

$numRow = - 1;
foreach ($array as $line)
{ // each line
if (!is_array ($line))
continue;

$numRow++;
$row = $table->appendChild (false, array ('row' => $numRow));
for ($i = 0; $i < $maxCols; $i++)
{ // each cell
if (isset ($line[$i]))
{ // value exists
$local['value'] = $formulary->htmlSpecialChars ($line[$i]);
if ($document->charset != 'UTF-8')
$local['value'] = mb_convert_encoding ($local['value'], $document->charset, 'UTF-8');
} // value exists
else
$local['value'] = '';

$local['name'] = $fieldName . '_' . $numRow . '_' . $i;
$row->appendChild (false, $local);
} // each cell
} // each line
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
$received = $formulary->received;
$document = $formulary->document;

if (!isset ($control['flags']['target']))
return;
if (!isset ($received[$fieldName . '_maxRows']))
return;
if (!isset ($received[$fieldName . '_maxCols']))
return;

$numCols = $received[$fieldName . '_maxCols'];
$numRows = $received[$fieldName . '_maxRows'];
$table = [];

for ($r = 0; $r < $numRows; $r++)
{ // each row
for ($c = 0; $c < $numCols; $c++)
{ // each col
if (isset ($received[$fieldName . '_' . $r . '_' . $c]))
$value = trim ($received[$fieldName . '_' . $r . '_' . $c]);
if (!strlen ($value))
continue;

if ($document->charset != 'UTF-8')
$value = mb_convert_encoding ($value, 'UTF-8', $document->charset);

if (!isset ($table[$r]))
$table[$r] = array ($c => $value);
else
$table[$r][$c] = $value;
} // each col
} // each row

$formulary->setField ($control['flags']['target'], $table);
} // function save

} // class eclFilter_modTable

?>