<?php

class eclEngine_listItem
{ // class eclEngine_listItem

public $document;
public $me = false;
public $data;
public $children = [];

public function __construct ($document, $from=false, $local=[])
{ // function __construct
global $store;
$this->document = $document;

if (is_string ($from))
$this->data = $store->control->read ($from);
elseif (is_object ($from))
{ // from object
$this->data = $from->data;
if (get_class ($from) == 'eclEngine_application')
$this->me = $from;
} // from object
elseif (is_array ($from))
$this->data = $from;

if ($local)
{ // local
if (!$this->data)
$this->data = $local;
else
$this->data = array_replace_recursive ($this->data, $local);
} // local
} // function __construct

public function appendChild ($from=false, $local=[])
{ // function appendChild
if (is_object ($from) and isset ($from->document))
{ // from list item
if ($local)
$from->data = array_replace_recursive ($from->data, $local);
$this->children[] = $from;
return $from;
} // from list item
else
$this->children[] = new eclEngine_listItem ($this->document, $from, $local);
return end ($this->children);
} // function appendChild

public function childrenMerge ($from)
{ // function childrenMerge
if (is_object ($from))
{ // is object
foreach ($from->children as $child)
{ // each child
if (isset ($child->document))
$this->children[] = $child;
else
$this->children[] = new eclEngine_listItem ($this->document, $child);
} // each child
} // is object
} // function childrenMerge

public function url ($pathway=true, $lang=true, $action=true, $protocol=true)
{ // function url
if ($pathway === true)
{ // get pathway
if (isset ($this->me->pathway))
$pathway = $this->me->pathway;
else
$pathway = $this->document->application->pathway;
} // get pathway
$this->data['pathway'] = $pathway;
$this->data['url'] = $this->document->url ($pathway, $lang, $action, $protocol);
return $this;
} // function url

public function appendFolder ($folder)
{ // function appendFolder
if (!isset ($this->data['pathway']))
{ // set pathway
if (isset ($this->me->pathway))
$this->data['pathway'] = $this->me->pathway;
else
$this->data['pathway'] = $this->document->application->pathway;
} // set pathway
$this->data['pathway'][] = $folder;

$this->data['url'] = $this->document->url ($this->data['pathway']);
return $this;
} // function appendFolder

public function confirm ($name, $formulary=false)
{ // function confirm
if (!isset ($this->data['url']))
$this->data['url'] = $this->document->mod->humperstilshen->dialog ($name, ['url' => $this->document->url ()], $formulary);
else
$this->data['url'] = $this->document->mod->humperstilshen->dialog ($name, ['url' => $this->data['url']], $formulary);
return $this;
} // function confirm

public function popUpOpen ($width=410, $height=600)
{ // function popUpOpen
if (!isset ($this->data['url']))
$this->data['url'] = $this->document->url ();

$this->data['url'] = "javaScript:gadgets.humperstilshen.actionPopupOpen(" . TIC . $this->data['url'] . TIC . ", " . $width . ", " . $height . ")";
return $this;
} // function popUpOpen

public function dialog ($data)
{ // function dialog
$this->data['url'] = $this->document->mod->humperstilshen->dialog ($data);
return $this;
} // function dialog

public function submenu ($name)
{ // function submenu
$this->data['url'] = $this->document->mod->humperstilshen->submenu ($name);
return $this;
} // function submenu

public function active ($active=true)
{ // function active
if ($active)
$this->data['active'] = 1;
return $this;
} // function active

public function virtual ($virtual=true)
{ // function virtual
if ($virtual)
$this->data['virtual'] = 1;
return $this;
} // function virtual

public function set ($name, $value=1)
{ // function set
if (!$value)
unset ($this->data[$name]);
elseif ($value === true)
$this->data[$name] = 1;
else
$this->data[$name] = $value;
return $this;
} // function set

public function swapTitle ($title=[])
{ // function swapTitle
if (is_array ($this->data) and isset ($this->data['text']['title']))
$this->data['caption'] = $this->data['text']['title'];
return $this;
} // function swapTitle

public function getVar ($name)
{ // function getVar
if ($name[0] == '$')
$name = substr ($name, 1);

if (!is_array ($this->data))
return false;

if (isset ($this->data[$name]))
return $this->data[$name];

if (isset ($this->data['text'][$name]))
return $this->data['text'][$name];

if (isset ($this->data['local'][$name]))
return $this->data['local'][$name];

return false;
} // function getVar

public function editable ($access)
{ // function editable
if (!$this->document->contentEditable)
return $this;

if ($access)
$this->data['editable'] = 1;
return $this;
} // function editable

public function sort ($sortBy="created", $sortDirection="asc")
{ // function sort
if (!$this->children)
return;

$sorted = [];
$unsorted = [];
foreach ($this->children as $child)
{ // each child
if (isset ($child->data[$sortBy]))
$sorted[$child->data[$sortBy]][] = $child;
else
$unsorted[] = $child;
} // each child

if ($sortDirection == "asc")
ksort ($sorted);
else
krsort ($sorted);
$sorted[] = $unsorted;

$result = [];
foreach ($sorted as $group)
{ // each group
foreach ($group as $child)
{ // each child
$result[] = $child;
} // each child
} // each group

$this->children = $result;
return $this;
} // function sort

} // class eclEngine_listItem

?>