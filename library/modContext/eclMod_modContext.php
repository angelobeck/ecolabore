<?php

class eclMod_modContext extends eclEngine_listItem
{ // class eclMod_modContext

public $mode = false;
public $help = false;
public $enabled = true;

public $document;

public function __construct ($document)
{ // function __construct
global $store;
$this->document = $document;
} // function __construct

public function help ($name)
{ // function help
global $store;
$data = $store->control->read ($name);

if (!$data)
return false;

if (!isset ($data['text']['caption']))
{ // get title
$title = $store->control->read ('modContext_goHelp');
$data['text']['caption'] = $title['text']['caption'];
if (!isset ($data['text']['title']))
$data['text']['title'] = $title['text']['caption'];
} // get title

$this->help = $this->document->createListItem ($data)
->dialog ($data);

return true;
} // function help

public function setModule ($mod, $arguments)
{ // function setModule
global $store;

if (!$this->enabled)
return;

$document = $this->document;
$me = $document->application;
$render = $document->render;

$row = $mod->appendChild ();
$row->children = $this->children;
if ($document->user->userId and $me->userId and $me->userId != $document->user->userId)
$this->socialTools ($row);

if ($this->help)
$row->children[] = $this->help;

foreach ($row->children as &$child)
{ // each child
$child->data['virtual'] = 1;
} // each child

if (!$document->access (2) and ($document->application->userId or $document->application->id))
{ // abuse report
$row->appendChild ('modWelcome_goReport')
->url (true, true, '_preload-report')
->active ($document->actions ('alert', 'report'));
} // abuse report

if (!$row->children)
return;

$mod->data = array_replace ($render->block ('modules/system_menu'), $store->control->read ('modContext_module'));

$mod->enabled = true;
} // function setModule

private function socialTools ($row)
{ // function socialTools
global $store;
$document = $this->document;
$me = $document->application;
$user = $document->user;

$friend = $store->userContent->open ($me->userId, '-register');
if ($chatId = $store->userContent->findMarker ($user->userId, 3))
$pathway = $store->userContent->pathway ($user->userId, $chatId);
else
$pathway = array (SYSTEM_PROFILES_URI, $user->name, '-chat');
$pathway[] = $store->user->getName ($me->userId);

$row->appendChild ('modContext_goChat')
->set ('friend_caption', $friend['text']['caption'])
->url ($pathway);
} // function socialTools

} // class eclMod_modContext

?>