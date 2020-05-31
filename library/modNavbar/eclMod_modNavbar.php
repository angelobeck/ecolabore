<?php

class eclMod_modNavbar
{ // class eclMod_modNavbar

public $document;
public $showSublevel = false;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;
$render = $document->render;

// Condições
if ($document->printableLayout)
return;

if ($document->pathway[0] == SYSTEM_PROFILES_URI)
return $this->profileNavBar ($mod);

// if ($document->pathway[0] == SYSTEM_ADMIN_URI)
// return $this->adminNavBar ($mod);

// Vamos iniciar a partir da aplicação ativa neste documento
$level = 1;
$app[1] = $document->application;
$names[1] = $app[1]->name;

// Agora, vamos subir a escada, percorrendo o endereço reversamente
while (!$app[$level]->isDomain and !$app[$level]->parent->isDomain)
{ // loop levels
$level++;
$app[$level] = $app[$level - 1]->parent;
$names[$level] = $app[$level]->name;
} // loop levels

if ($app[$level]->parent->applicationName == 'domain')
$isDomain = true;

// Vamos criar as camadas
static $menuIndex = 0;
for ($i = $level; $i; $i--)
{ // loop each layer

$children = $app[$i]->parent->menuChildren ($document, $names[$i]);

if ($i == $level and count ($children) == 1)
continue;

if ($i == 1 and count ($children) == 1)
continue;

if (!count ($children))
break;

$layer = $mod->appendChild ();

foreach ($children as $index => $child)
{ // each child
if (isset ($isDomain) and $i == $level and $index == 0 and !isset ($mod->data['local']['home_display']))
continue;

$menu = $layer->appendChild ($child->data)
->url ($child->pathway)
->virtual ($child->access)
->active ($i == 1 and $child->name == $names[$i])
->set ('editable', $i == 1 and $child->name == $names[$i] and $child->id and $document->contentEditable and $document->access (4, $child->groups));

if (isset ($child->data['flags']['section_type']) and $child->data['flags']['section_type'] == 'menu')
{ // submenu
$menu->data['menu'] = 'menu' .  (++$menuIndex);
if ($document->access (4, $child->groups))
$menu->data['can_edit'] = 1;

$menuItens = $child->menuChildren ($document);
foreach ($menuItens as $menuItem)
{ // each menu item
$menu->appendChild ($menuItem->data)
->url (array ($document->domain->name, $menuItem->name))
->virtual ($child->access);
} // each menu item
} // submenu
} // each child
} // loop each layer

if ($this->showSublevel)
{ // last level
$lastLayer = $document->application->menuChildren ($document);
if ($lastLayer)
$layer = $mod->appendChild ();
foreach ($lastLayer as $child)
{ // each child
$layer->appendChild ($child)
->url ($child->pathway);
} // each child
} // last level

if (!$mod->children)
return;

// Configurações
$name = 'navbar';
if (isset ($arguments[0]))
$name .= '_' . $arguments[0];
$mod->data = $render->block ('modules/' . $name);
if (!$mod->data and isset ($arguments[0]))
$mod->data = $render->block ('modules/navbar');

// Se o módulo pode ser editado
if ($document->templateEditable and $document->access (4, $document->domain->groups))
{ // reference
$pathway = array ($document->domain->name, '-personalite', 'modules', $name);
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modNavbar_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // reference

$mod->enabled = true;
} // function setModule

private function profileNavBar ($mod)
{ // function profileNavBar
global $store;
$document = $this->document;
$me = $document->application;

while (!$me->parent->isDomain)
{ // find domain
$me = $me->parent;
} // find domain
$row = $mod->appendChild ();
foreach ($me->menuChildren ($document) as $child)
{ // each child

$item = $row->appendChild ($child->data)
->url ($child->pathway)
->active ($child->pathway == $document->pathway);

if (isset ($child->data['flags']['modNavbar_popUp']))
$item->popUpOpen ();
} // each child

if (!$row->children)
return;

$mod->data = $store->control->read ('modules/navbar');
$mod->enabled = true;
} // function profileNavBar

private function adminNavBar ($mod)
{ // function adminNavBar
global $store;
$document = $this->document;
$me = $document->domain;

$row = $mod->appendChild ();
foreach ($me->menuChildren ($document) as $child)
{ // each child

$item = $row->appendChild ($child->data)
->url ($child->pathway)
->active ($child->pathway == $document->pathway);

if (isset ($child->data['flags']['modNavbar_popUp']))
$item->popUpOpen ();
$this->createSubmenu ($item, $child);
if($item->children)
$item->data['menu'] = 1;
} // each child

if (!$row->children)
return;

$mod->data = $store->control->read ('modules/navbar');
$mod->enabled = true;
} // function adminNavBar

private function createSubmenu($row, $me)
{ // function createSubmenu
foreach ($me->menuChildren ($this->document) as $child)
{ // each child
$item = $row->appendChild ($child->data)
->url ($child->pathway)
->active ($child->pathway == $this->document->pathway);

if (isset ($child->data['flags']['modNavbar_popUp']))
$item->popUpOpen ();
} // each child

} // function createSubmenu
} // class eclMod_modNavbar

?>