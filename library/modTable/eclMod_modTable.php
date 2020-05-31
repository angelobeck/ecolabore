<?php

class eclMod_modTable
{ // class eclMod_modTable

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store, $system;
$document = $this->document;

// Encontre a aplicação
$me = $document->application->findModuleTarget ($document, $arguments);
if (!$me)
return;

$number = $arguments[1];

// A tabela não pode ser editada e está vazia
if ($document->printableLayout or !$document->access (4, $me->groups))
{ // user cant edit
if (!isset ($me->data['extras']['table_' . $number]['table']))
return;
} // user cant edit
else
{ // user is admin
// O documento está em modo de edição
if ($document->contentEditable)
{ // content editable
$editable = true;
if (!isset ($me->data['extras']['table_' . $number]))
$me->data['extras']['table_' . $number] = $store->control->read ('modTable_module');
if (!isset ($me->data['extras']['table_' . $number]['local']['list']))
$me->data['extras']['table_' . $number]['local']['list'] = 'table_simple';
if (!isset ($me->data['extras']['table_' . $number]['table']))
$me->data['extras']['table_' . $number]['table'] = array (array ('', ''), array ('', ''));
} // content editable
else
{ // wysiwyg disabled
if (!isset ($me->data['extras']['table_' . $number]))
$mod->data = $store->control->read ('modTable_moduleCreate');
} // wysiwyg disabled

// anchor
$pathway = array_slice ($me->pathway, 1);
array_unshift ($pathway, $document->domain->name, '-personalite', 'tables', 'table_' . $number);
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modTable_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // user is admin

$mod->enabled = true;
if (!isset ($me->data['extras']['table_' . $number]['table']))
return;

$mod->data = array_replace_recursive ($mod->data, $me->data['extras']['table_' . $number]);

if (isset ($editable) and $editable and isset ($me->data['id']))
{ // editable
$mod->data['local']['editable'] = 1;
$mod->data['local']['id'] = $me->data['id'];
$mod->data['local']['number'] = $number;
} // editable

// count max cols
$table = &$me->data['extras']['table_' . $number]['table'];
$maxCols = 0;
foreach ($table as $row)
{ // count columns
if (count ($row) > $maxCols)
$maxCols = count ($row);
} // count columns

$lang = $document->lang;

foreach ($table as $rowData)
{ // each row
$row = $mod->appendChild ();

for ($index = 0; $index < $maxCols; $index++)
{ // each column
if (isset ($rowData[$index]))
$local['caption'][$lang] = array (1 => $rowData[$index], 4 => 1, 5 => 2, 6 => 1);
else
$local = [];
$row->appendChild ($local);
} // each column
} // each row
} // function setModule

} // class eclMod_modTable

?>