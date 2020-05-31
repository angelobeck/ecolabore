<?php

class eclEngine_application
{ // class eclEngine_application

public $applicationName;
public $name;
public $parent;
public $pathway;
public $groups;
public $access;

public $map = [];
public $data = [];

public $id = 0;
public $domainId = 0;
public $userId = 0;
public $ignoreSubfolders = false;
public $isDomain = false;
public $menuType = 'hidden';

private $childrenByName = [];
private $children = false;

public function __construct ($applicationName='system', $name='system', $parent=false, $document=false)
{ // function __construct
global $store;

$this->applicationName = $applicationName;
$this->name = $name;
$this->parent = $parent;

if ($parent)
{ // inherit parent properties
$this->pathway = $this->parent->pathway;
$this->pathway[] = $this->name;

// Vamos propagar propriedades herdadas
$this->groups = $parent->groups;
$this->access = $parent->access;
$this->domainId = $parent->domainId;
$this->userId = $parent->userId;
} // inherit parent properties

$class = 'eclApp_' . $applicationName;
if (defined ($class . '::ignoreSubfolders'))
$this->ignoreSubfolders = $class::ignoreSubfolders;
if (defined ($class . '::dataFrom'))
$this->data = $store->control->read ($class::dataFrom);
if (defined ($class . '::map'))
$this->map = explode (' ', $class::map);
if (defined ($class . '::access') and $class::access > $this->access)
$this->access = $class::access;
if (defined ($class . '::isDomain'))
$this->isDomain = $class::isDomain;

// menu
if (defined ($class . '::menuType'))
$this->menuType = $class::menuType;
elseif (is_callable ([$class, 'get_menu_type']))
$this->menuType = $class::get_menu_type ($this, $document);

// Chama a classe de aplicação para auxiliar na construção
if (is_callable ([$class, 'constructor_helper']))
$class::constructor_helper ($this, $document);
} // function __construct

public function getMap ($applicationName=false)
{ // function getMap
global $mapPrepend, $map, $mapAppend;
static $openedMaps = [];

if ($applicationName == false)
$applicationName = $this->applicationName;

if (isset ($openedMaps[$applicationName]))
return $this->map = $openedMaps[$applicationName];

$openedMaps[$applicationName] = [];
if (SYSTEM_IS_PACKED)
{ // internal map
if (isset ($mapPrepend[$applicationName]))
$openedMaps[$applicationName] = $mapPrepend[$applicationName];
if (isset ($map[$applicationName]))
$openedMaps[$applicationName] = array_merge ($openedMaps[$applicationName], $map[$applicationName]);
if (isset ($mapAppend[$applicationName]))
$openedMaps[$applicationName] = array_merge ($openedMaps[$applicationName], $mapAppend[$applicationName]);

$this->map = $openedMaps[$applicationName];
return;
} // internal map

$result = [];
list ($prefix) = explode ('_', $applicationName, 2);

$file = PATH_LIBRARY . $prefix . '/app_' . $applicationName . '_prepend.txt';
if (is_file ($file))
{ // open file
foreach (explode ("\n", file_get_contents ($file)) as $line)
{ // each line
$line = trim ($line);
if (preg_match ('%^[a-zA-Z0-9_]+$%', $line))
$result[] = $line;
} // each line
} // open file

if (isset ($map[$applicationName]))
$result = array_merge ($result, $map[$applicationName]);

$file = PATH_LIBRARY . $prefix . '/app_' . $applicationName . '_append.txt';
if (is_file ($file))
{ // open file
foreach (explode ("\n", file_get_contents ($file)) as $line)
{ // each line
$line = trim ($line);
if (preg_match ('%^[a-zA-Z0-9_]+$%', $line))
$result[] = $line;
} // each line
} // open file

$openedMaps[$applicationName] = $result;
$this->map = $openedMaps[$applicationName];
} // function getMap

public function child ($name, $document=false)
{ // function child
if (isset ($this->childrenByName[$name]))
return $this->childrenByName[$name];

// Será que os filhos já foram todos abertos?
if ($this->children !== false)
return false;

// Vamos percorrer o mapa, testando cada aplicação
foreach ($this->map as $applicationName)
{ // each application
$class = 'eclApp_' . $applicationName;

if ( (defined ($class . '::name') and $name == $class::name) or (is_callable ([$class, 'is_child']) and $class::is_child ($this, $name, $document)))
{ // child found
$this->childrenByName[$name] = new eclEngine_application ($applicationName, $name, $this, $document);
return $this->childrenByName[$name];
} // child found
} // each application

// Não existe um filho com este nome
return false;
} // function child

public function children ($document=false)
{ // function children
if (!is_array ($this->children))
{ // open children
$this->children = [];
foreach ($this->map as $applicationName)
{ // each application
$class = 'eclApp_' . $applicationName;
$names = [];
if (defined ($class . '::name'))
$names[] = $class::name;
elseif (is_callable ([$class, 'get_children_names']))
$names = $class::get_children_names ($this, $document);
foreach ($names as $name)
{ // each name
// Se este filho não foi chamado pelo nome, crie-o
if (!isset ($this->childrenByName[$name]))
$this->childrenByName[$name] = new eclEngine_application ($applicationName, $name, $this, $document);

// Vamos copiá-lo para a lista em ordem de índice
$this->children[] = $this->childrenByName[$name];
} // each name
} // each application
} // open children
return $this->children;
} // function children

public function menuChildren ($document, $name=false)
{ // function menuChildren
$menu = [];

if (!is_array ($this->children))
{ // open children
foreach ($this->map as $applicationName)
{ // each application
$class = 'eclApp_' . $applicationName;

$menuType = 'hidden';
if (defined ($class . '::menuType'))
$menuType = $class::menuType;
elseif (is_callable ([$class, 'get_menu_type']))
$menuType = $class::get_menu_type ($this);

switch ($menuType)
{ // switch menu type
case 'post':
if (!$name or !$class::is_child ($this, $name))
break;

if (!isset ($this->childrenByName[$name]))
$this->childrenByName[$name] = new eclEngine_application ($applicationName, $name, $this);

// Vamos copiá-lo para a lista em ordem de índice
if ($document->access ($this->childrenByName[$name]->access, $this->childrenByName[$name]->groups))
$menu[] = $this->childrenByName[$name];
break;

case 'hidden':
case 'domain':
break;
case 'section':
default:
$names = [];
if (defined ($class . '::name'))
$names[] = $class::name;
elseif (is_callable ([$class, 'get_children_names']))
$names = $class::get_children_names ($this, $document);
foreach ($names as $childName)
{ // each name
// Se este filho não foi chamado pelo nome, crie-o
if (!isset ($this->childrenByName[$childName]))
$this->childrenByName[$childName] = new eclEngine_application ($applicationName, $childName, $this);

// Vamos copiá-lo para a lista em ordem de índice
if ($document->access ($this->childrenByName[$childName]->access, $this->childrenByName[$childName]->groups))
$menu[] = $this->childrenByName[$childName];
} // each name
} // switch type
} // each application
return $menu;
} // open children

foreach ($this->children as $child)
{ // each child
if ($child->menuType == 'hidden')
continue;

if ($child->menuType == 'domain')
continue;

if ($child->menuType == 'post' and $child->name != $name)
continue;

if (!$document->access ($child->access, $child->groups))
continue;

$menu[] = $child;
} // each child
return $menu;
} // function menuChildren

public function reset ()
{ // function reset
$this->children = false;
$this->childrenByName = [];
} // function reset

public function dispatch ($document)
{ // function dispatch
$document->data = array_replace_recursive (array ('flags' => $this->flags ()), $this->data);
$class = 'eclApp_' . $this->applicationName;
if (is_callable ([$class, 'dispatch']))
$class::dispatch ($document);
} // function dispatch

public function remove ()
{ // function remove
foreach ($this->children () as $child)
{ // each child
$child->remove ();
} // each child

$this->reset ();

$class = 'eclApp_' . $this->applicationName;
if (is_callable ($class . '::remove'))
$class::remove ($this);
} // function remove

public function flags ()
{ // function flags
if (is_object ($this->parent))
$flags = $this->parent->flags ();
else
$flags = [];

// Agora vamos fundir com as flags deste objeto - se houverem
if (isset ($this->data['flags']))
$flags = array_replace ($flags, $this->data['flags']);

return $flags;
} // function flags

public function findChild ($name)
{ // function findChild
global $store, $system;

if ($this->domainId)
{ // from domain
$pathway = $store->domainContent->pathway ($this->domainId, $name);
} // from domain
elseif ($this->userId)
{ // from profile
$pathway = $store->userContent->pathway ($this->userId, $name);
} // from profile

if ($pathway === false)
return false;

$me = $system;
foreach ($pathway as $folder)
{ // loop folders
$me = $me->child ($folder);
if (!$me)
return false;
} // loop folders

if (!$me)
return false;

return $me;
} // function findChild

public function findModuleTarget ($document, &$arguments)
{ // function findModuleTarget
if (!$document->domain->domainId)
return false;

if (!isset ($arguments[0]))
{ // no arguments
$arguments = [false, '0'];
return $document->application;
} // no arguments

if (is_numeric ($arguments[0]))
{ // numeric
$arguments[1] = $arguments[0];
return $document->application;
} // numeric

if (!isset ($arguments[1]) or !is_numeric ($arguments[1]))
$arguments[1] = '0';

$name = $arguments[0];
if (isset ($document->application->data['name']) and $name == $document->application->data['name'])
return $document->application;

if ($name == '-index')
return $document->domain->child ('');

if ($name == '-recents')
return $document->domain->child ('-recents');

$me = $document->application->findChild ($name);
if (!$me or !$document->access ($me->access, $me->groups))
return false;

return $me;
} // function findModuleTarget

public function ignoreSession ($set=123)
{ // function ignoreSession
static $ignore = false;
if ($set != 123)
$ignore = $set;
return $ignore;
} // function ignoreSession

} // class eclEngine_application

?>
