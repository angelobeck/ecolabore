<?php

class eclMod_modHumperstilshen extends eclEngine_listItem
{ // class eclMod_modHumperstilshen

public $children = [];
public $submenus = [];
public $loginError = false;
public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;

$menu = $document->createListItem (array ('name' => 'menu', 'add_close_button' => 1));

if ( ($document->access (1) or $document->application->isDomain) and !$document->mod->context->help)
{ // application help
$help = $document->application->applicationName . '_help';
if ($help == 'section_help' and isset ($document->application->data['flags']['section_type']))
$help = 'section' . ucfirst ($document->application->data['flags']['section_type']) . '_help';
$data = $store->control->read ($help);
if ($data)
{ // context help found
$document->mod->context->help ($help);
} // context help found
} // application help

if ($document->application->isDomain)
{ // is domain
$menu->appendChild (array ('name' => 'context'));
} // is domain
elseif ($document->access (1))
{ // connected
if ($document->domain->name == SYSTEM_ADMIN_URI)
$menu_list = array ('context', 'user');
if ($document->domain->name == SYSTEM_ADMIN_URI)
$menu_list = array ('context', 'user');
elseif ($document->application->userId)
$menu_list = array ('context', 'user_tools', 'user');
elseif (!$document->contentEditable)
$menu_list = array ('context', 'editor_objects', 'interface', 'user');
else
$menu_list = array ('context', 'interface', 'user');
if (!$menu_list and $this->children)
$menu_list = array ('context');

foreach ($menu_list as $menuName)
{ // each menu
$menu->appendChild (false, array ('name' => $menuName));
} // each menu

if ($document->contentEditable)
{ // editor menu
$editor = $this->appendChild (array ('name' => 'editor', 'add_close_button' => 1));
$editor->appendChild (array ('name' => 'editor_document'));
$editor->appendChild (array ('name' => 'editor_objects'));
$editor->appendChild (array ('name' => 'editor_insert'));
} // editor menu
} // connected
else
{ // login
$menu->appendChild (array ('name' => 'login'));
$menu->appendChild (array ('name' => 'user_welcome'));

if ($this->loginError)
$menu->data['alert'] = 1;
} // login

if (!$menu->children)
return;

// Refresh window
$mod->data['local']['refresh'] = $document->url ();

if ($document->access (1))
{ // check session expiration
$mod->data['local']['session_check_url'] = $document->url ($document->domain->pathway, true, '_session-check');
$mod->data['local']['session_refresh_url'] = $document->url ($document->domain->pathway, true, '_session-refresh');
$mod->data['local']['session_ttl'] = SYSTEM_SESSION_TTL;
$formulary = $document->createFormulary ('modHumperstilshen_sessionCheck', [], 'session_check');
$this->dialog ('modHumperstilshen_sessionCheck', [], $formulary);
$mod->data['local']['session_dialog'] = 'dialog_' . count ($this->children);
} //  check session expiration

$mod->children = $this->children;
$mod->appendChild ($menu);
$mod->enabled = true;
} // function setModule

public function alert ($alert, $formulary=false)
{ // function alert
$local = array ('alert' => 1);
return $this->dialog ($alert, $local, $formulary);
} // function alert

public function dialog ($data, $local=[], $formulary=false)
{ // function dialog
global $store;
if (is_string ($data))
$data = $store->control->read ($data);
if (isset ($data['msg']))
$data = array_replace ($store->control->read ($data['msg']), $data);
if ($local)
$data = array_replace ($data, $local);

$data['is_formulary'] = 1;
$data['name'] = 'dialog_' .  (1 + count ($this->children));

if (isset ($data['flags']['icon']))
$icon = $data['flags']['icon'];
elseif (isset ($data['default_icon']))
$icon = $data['default_icon'];

if (isset ($icon))
{ // set icon
$data['icon'] = $this->document->urlFiles ('icons/ecolabore-humperstilshen-' . $icon . '.png', true, '-shared');
$iconDescription = $store->control->read ('modHumperstilshen_icon' . ucfirst ($icon));
if (isset ($iconDescription['text']['caption']))
$data['icon_description'] = $iconDescription['text']['caption'];
} // set icon

if (!isset ($data['caption']) and !isset ($data['text']['caption']))
{ // caption required
if (isset ($data['url']))
{ // confirm operation
$caption = $store->control->read ('labels/layout/dialog_confirm');
$data['caption'] = $caption['text']['caption'];
} // confirm operation
} // caption required

$dialog = $this->document->createListItem ($data);
if ($formulary)
$dialog->childrenMerge ($formulary->create ());
elseif (isset ($data['url']))
$dialog->data['add_confirm_button'] = 1;
else
$dialog->data['add_close_button'] = 1;

$this->children[] = $dialog;

return "javascript:gadgets.humperstilshen.actionOpen('dialog_" . count ($this->children) . TIC . ")";
} // function dialog

public function submenu ($name)
{ // function submenu
$data['name'] = 'submenu_' .  (1 + count ($this->submenus));
$data['add_close_button'] = 1;

$dialog = $this->document->createListItem ($data);
$dialog->appendChild (array ('name' => $name));

$this->submenus[] = $dialog;

return "javascript:gadgets.humperstilshen.actionOpen('submenu_" . count ($this->submenus) . TIC . ")";
} // function submenu

} // class eclMod_modHumperstilshen

?>