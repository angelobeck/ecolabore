<?php

class eclEngine_formulary extends eclEngine_listItem
{ // class eclEngine_formulary

public $document;
public $application;

public $protocol = true;
public $pathway = true;
public $lang = true;
public $action = true;

public $data;
public $flags = [];
public $received;
public $prefix;

public $errorMsg = false;
public $required = false;
public $hidden = [];
public $posteriori = 0; // 0=all 1=normal_only 2=posteriori_only
public $submited = false;

private $receivedCommands;
private $baseTarget = '';
private $baseName = '';
private $originalControl = [];

public $control = [];
public $children = [];

private $level = 0;
private $tower = [];
public $index = 0;

public function __construct ($document, $controlName=false, $data=[], $prefix='edit')
{ // function __construct
global $store;
$this->document = $document;
$this->application = $document->application;
$this->received = $document->received;
if (isset ($document->data['flags']))
$this->flags = $document->data['flags'];

$this->data = $data;
$this->prefix = $prefix;
if ($controlName !== false)
{ // open control
if (is_array ($controlName))
$this->control = $controlName;
else
$this->control = $store->control->read ($controlName);
if (isset ($this->control['flags']))
$this->flags = array_replace ($this->flags, $this->control['flags']);
if (isset ($this->flags['prefix']))
$this->prefix = $this->flags['prefix'];
if (isset ($this->flags['action']))
$this->action = $this->flags['action'];
} // open control

// check formulary id
if (!$this->prefix or isset ($document->ids[$this->prefix]))
$this->control['children'] = array ('_start', '_formularyIdCrash', '_end');
else
{ // correct insertion
$document->ids[$this->prefix] = 1;
$this->hidden[$this->prefix] = 'yes';
if (isset ($this->received[$this->prefix]))
$this->submited = true;
$this->prefix .= '_';
} // correct insertion
if (isset ($this->control['children']))
$this->control['children'] = array_values ($this->control['children']);
$this->originalControl = $this->control;
} // function __construct

public function command ($command)
{ // function command
if (!isset ($this->receivedCommands))
{ // load commands
if (!$this->submited)
$this->receivedCommands = [];
else
{ // received

$start = strlen ($this->prefix);

foreach (array_keys ($this->received) as $key)
{ // each key
if (substr ($key, 0, $start) != $this->prefix)
continue;

if (substr ($key, $start, 8) == 'command_')
$this->receivedCommands[substr ($key, $start + 8)] = true;
} // each key
} // received
} // load commands
if (isset ($this->receivedCommands[$command]))
return true;

return false;
} // function command

public function levelUp ($control)
{ // function levelUp
if (!isset ($control['children']))
return;

$control['children'] = array_values ($control['children']);

$this->level++;
$this->tower[$this->level] = [
'children' => $this->children, 
'control' => $this->control, 
'index' => $this->index
];
$this->children = [];
$this->control = $control;
$this->index = 0;
} // function levelUp

public function insertControlChildren ($control)
{ // function insertControlChildren
if (!isset ($control['children']))
return;

$control['children'] = array_values ($control['children']);

$this->level++;
$this->tower[$this->level] = [
'control' => $this->control, 
'index' => $this->index
];
$this->control = $control;
$this->index = 0;
} // function insertControlChildren

private function nextControl ()
{ // function nextControl
do
{ // loop levels
if (isset ($this->control['flags']['base_target']))
$this->baseTarget = $this->control['flags']['base_target'];
else
$this->baseTarget = '';

if (isset ($this->control['flags']['base_name']))
$this->baseName = $this->control['flags']['base_name'] . '_';
else
$this->baseName = '';

if (isset ($this->control['children'][$this->index]))
{ // child found
$this->index++;
return $this->control['children'][$this->index - 1];
} // found

if (!$this->level)
return false;

$floor = $this->tower[$this->level];
if (isset ($floor['children']))
{ // children level down
end ($floor['children'])->children = $this->children;
$this->children = $floor['children'];
} // children level down
$this->control = $floor['control'];
$this->index = $floor['index'];
unset ($this->tower[$this->level]);
$this->level--;
} // loop levels
while (true);
} // function nextControl

public function create ()
{ // function create
global $store;
$originalControl = $this->control;
$this->children = [];
$this->index = 0;

if (!isset ($this->control['children']))
return $this;

while ($name = $this->nextControl ())
{ // each child

if (is_string ($name))
{ // open control
if ($name[0] == '_')
$name = 'system_form' . $name;

$control = $store->control->read ($name);
} // open control
elseif (is_array ($name) and isset ($name['flags']['field_name']))
{ // embed control
$control = $name;
$name = $control['flags']['field_name'];
} // embed control
else
continue;

// condition
if (isset ($control['flags']['condition']) and !$this->condition ($control['flags']['condition']))
continue;

// display field
if (isset ($control['flags']['field_display']) and !$this->condition ($control['flags']['field_display']))
continue;

// field name
if (isset ($control['flags']['field_name']))
$fieldName = $this->prefix . $this->baseName . $control['flags']['field_name'];
else
$fieldName = $this->prefix . $this->baseName . $name;

// required
if (isset ($control['flags']['required']))
{ // required
$this->required = true;
$control['required'] = 1;
} // required

// help
if (isset ($control['flags']['help']))
$control['help'] = 1;

// filter
if (isset ($control['flags']['filter']))
{ // use filter
if (preg_match ('%^[a-z][a-zA-Z0-9_]*$%', $control['flags']['filter']))
{ // valid filter name
$filter = 'eclFilter_' . $control['flags']['filter'];
if (isset ($control['flags']['view']))
$item = $filter::view ($fieldName, $control, $this);
else
$item = $filter::create ($fieldName, $control, $this);
if (is_object ($item))
$this->appendChild ($item);
} // valid filter name
} // use filter
else
{ // no filter
if (isset ($control['flags']['type']))
$type = $control['flags']['type'];
else
$type = 'unknown';
$this->appendChild ($name, [
'name' => $fieldName, 
'type' => $type
]);
} // no filter
} // each child

$this->control = $originalControl;
return $this;
} // function create

public function save ($posteriori=0)
{ // function save
global $store;
$this->index = 0;
$originalControl = $this->control;

if (!$this->submited)
return false;

if (!isset ($this->control['children']))
return false;

while ($name = $this->nextControl ())
{ // each child
if (is_string ($name))
{ // open control
if ($name[0] == '_')
continue;

$control = $store->control->read ($name);
} // open control
elseif (is_array ($name) and isset ($name['flags']['field_name']))
{ // embed control
$control = $name;
$name = $control['flags']['field_name'];
} // embed control
else
continue;

if (!isset ($control['flags']['filter']))
continue;
if (!preg_match ('%^[a-z][a-zA-Z0-9_]*$%', $control['flags']['filter']))
continue;
if (isset ($control['flags']['condition']) and !$this->condition ($control['flags']['condition']))
continue;
if (isset ($control['flags']['posteriori']) and $posteriori == 1)
continue;
if (!isset ($control['flags']['posteriori']) and $posteriori == 2)
continue;
if (isset ($control['flags']['view']))
continue;

if (isset ($control['flags']['field_name']))
$fieldName = $this->prefix . $this->baseName . $control['flags']['field_name'];
else
$fieldName = $this->prefix . $this->baseName . $name;
$filter = 'eclFilter_' . $control['flags']['filter'];
$filter::save ($fieldName, $control, $this);
} // each child

$this->control = $originalControl;

if ($this->errorMsg)
return false;

return true;
} // function save

public function restore ($data=[])
{ // function restore
global $store;
if ($this->controlName === false)
return false;
$control = $store->control->read ($this->controlName);
if (isset ($control['flags']))
$this->flags = array_replace ($this->flags, $control['flags']);
if (isset ($control['children']))
{ // children exists
foreach ($control['children'] as $name)
{ // each child
if ($name[0] == '_')
$name = 'formulary' . $name;

$control = $store->control->read ($name);
if ( (!isset ($control['flags']['condition']) or $this->condition ($control['flags']['condition'])) and isset ($control['flags']['target']))
{ // valid condition
$target = $control['flags']['target'];
$field = $this->getField ($target, $data);
$this->setField ($target, $field);
} // valid condition
} // each child
} // children exists
} // function restore

public function removeLanguage ($lang)
{ // function removeLanguage
if (!isset ($this->data['text']))
return;
foreach (array_keys ($this->data['text']) as $key)
{ // each field
if (isset ($this->data['text'][$key][$lang]))
unset ($this->data['text'][$key][$lang]);
} // each field
} // function removeLanguage

public function getField ($path, $data=false)
{ // function getField
$path = $this->baseTarget . $path;

if (!strlen ($path))
return false;

if ($data === false)
$data = $this->data;

$pathway = explode ('/', $path);
$level = count ($pathway);
$found[0] = $data;
for ($i = 0; $i < $level; )
{ // loop levels
if (!isset ($found[$i][$pathway[$i]]))
return false;
$return = $found[$i][$pathway[$i]];
$i++;
if ($level == $i)
return $return;
$found[$i] = $return;
} // loop levels
} // function getField

public function setField ($path, $value=false)
{ // function setField
$path = $this->baseTarget . $path;

if (!strlen ($path))
return;

$pathway = explode ('/', $path);
$level = count ($pathway);
$data = $this->data;
do
{ // its not a loop
if ($level == 1)
{ // level 1
$data[$pathway[0]] = $value;
break;
} // level 1
if (!isset ($data[$pathway[0]]))
$data[$pathway[0]] = [];

if ($level == 2)
{ // level 2
$data[$pathway[0]][$pathway[1]] = $value;
break;
} // level 2
if (!isset ($data[$pathway[0]][$pathway[1]]))
$data[$pathway[0]][$pathway[1]] = [];

if ($level == 3)
{ // level 3
$data[$pathway[0]][$pathway[1]][$pathway[2]] = $value;
break;
} // level 3

if (!isset ($data[$pathway[0]][$pathway[1]][$pathway[2]]))
$data[$pathway[0]][$pathway[1]][$pathway[2]] = [];

if ($level == 4)
{ // level 4
$data[$pathway[0]][$pathway[1]][$pathway[2]][$pathway[3]] = $value;
break;
} // level 4
if (!isset ($data[$pathway[0]][$pathway[1]][$pathway[2]][$pathway[3]]))
$data[$pathway[0]][$pathway[1]][$pathway[2]][$pathway[3]] = [];

if ($level == 5)
{ // level 5
$data[$pathway[0]][$pathway[1]][$pathway[2]][$pathway[3]][$pathway[4]] = $value;
break;
} // level 5
if (!isset ($data[$pathway[0]][$pathway[1]][$pathway[2]][$pathway[3]][$pathway[4]]))
$data[$pathway[0]][$pathway[1]][$pathway[2]][$pathway[3]][$pathway[4]] = [];

if ($level == 6)
{ // level 6
$data[$pathway[0]][$pathway[1]][$pathway[2]][$pathway[3]][$pathway[4]][$pathway[5]] = $value;
break;
} // level 6

break;
} // its not a loop
while ('Ill never be evaluated');

if ($value === false)
{ // empty value
switch ($level)
{ // clear fields
case 6:
if (!$data[$pathway[0]][$pathway[1]][$pathway[2]][$pathway[3]][$pathway[4]][$pathway[5]])
unset ($data[$pathway[0]][$pathway[1]][$pathway[2]][$pathway[3]][$pathway[4]][$pathway[5]]);

case 5:
if (!$data[$pathway[0]][$pathway[1]][$pathway[2]][$pathway[3]][$pathway[4]])
unset ($data[$pathway[0]][$pathway[1]][$pathway[2]][$pathway[3]][$pathway[4]]);

case 4:
if (!$data[$pathway[0]][$pathway[1]][$pathway[2]][$pathway[3]])
unset ($data[$pathway[0]][$pathway[1]][$pathway[2]][$pathway[3]]);

case 3:
if (!$data[$pathway[0]][$pathway[1]][$pathway[2]])
unset ($data[$pathway[0]][$pathway[1]][$pathway[2]]);

case 2:
if (!$data[$pathway[0]][$pathway[1]])
unset ($data[$pathway[0]][$pathway[1]]);

case 1:
if (!$data[$pathway[0]])
unset ($data[$pathway[0]]);
} // clear fields
} // empty value
$this->data = $data;
} // function setField

public function setErrorMsg ($control, $fieldName, $msg=false, $value=false)
{ // function setErrorMsg
if ($this->errorMsg)
return;

$local = $control['flags'];
unset ($local['icon']);

if (isset ($local['error_msg']))
$local['msg'] = $local['error_msg'];
elseif ($msg !== false)
$local['msg'] = $msg;
else
$local['msg'] = 'system_msg_alertErrorField';

$local['name'] = $fieldName;
$local['return_id'] = $fieldName;
if (isset ($control['text']['caption']))
$local['field_caption'] = $control['text']['caption'];
if ($value !== false)
$local['value'] = $this->htmlSpecialChars ($value);

$this->errorMsg = $local;
} // function setErrorMsg

public function setRequiredMsg ($control, $fieldName, $msg=false)
{ // function setRequiredMsg
if ($this->errorMsg)
return;

$local = $control['flags'];
unset ($local['icon']);

if (isset ($local['required_msg']))
$local['msg'] = $local['required_msg'];
elseif ($msg !== false)
$local['msg'] = $msg;
else
$local['msg'] = 'system_msg_alertRequiredField';

$local['name'] = $fieldName;
$local['return_id'] = $fieldName;
if (isset ($control['text']['caption']))
$local['field_caption'] = $control['text']['caption'];

$this->errorMsg = $local;
} // function setRequiredMsg

public function condition ($condition)
{ // function condition
if (!$condition)
return true;

$not = false;

// Vamos separar os membros OR
$or_list = explode ('||', $condition);
foreach ($or_list as $or_condition)
{ // each or condition
$result = true;
$or_condition = trim ($or_condition);

// Para cada membro OR, vamos criar uma lista AND
$and_list = explode ('&&', $or_condition);
foreach ($and_list as $and_condition)
{ // each and condition
$c = trim ($and_condition);
if ($c[0] == '!')
{ //  negative operator on
$not = true;
$c = trim (substr ($c, 1));
} // negative operator on

if (!$not and (!isset ($this->flags[$c]) or !$this->flags[$c]))$result = false;
elseif ($not and isset ($this->flags[$c]) and $this->flags[$c])$result = false;
$not = false;
} // each and condition
if ($result)
return true;
} // each or condition
return false;
} // function condition

public function htmlSpecialChars ($string)
{ // function htmlSpecialChars
return str_replace (['&', '<', QUOT], ['&amp;', '&lt;', '&quot;'], $string);
} // function htmlSpecialChars

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;

if (isset ($this->flags['modFormulary_preset']))
$template = 'formulary_' . $this->flags['modFormulary_preset'];
elseif ($document->application->isDomain)
$template = 'formulary_system';
else
$template = 'formulary';

$mod->data = $document->render->block ('modules/' . $template);

$mod->childrenMerge ($this->create ());

if (!$mod->children)
return;

if (isset ($this->control['local']))
{ // set local scope
if (isset ($mod->data['local']))
$mod->data['local'] = array_replace ($mod->data['local'], $this->control['local']);
else
$mod->data['local'] = $this->control['local'];
} // set local scope

if (isset ($this->control['text']['caption']))
$mod->data['local']['caption'] = $this->control['text']['caption'];

$mod->data['local']['list'] = 'formulary';

// Se o módulo pode ser editado
if (!$document->application->isDomain and $document->templateEditable and $document->access (4, $document->domain->groups))
{ // reference
$pathway = [$document->domain->name, '-personalite', 'modules', $template];
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modFormulary_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // reference

$mod->enabled = true;
} // function setModule

} // class eclEngine_formulary

?>