<?php

class eclMod_modEditor
{ // class eclMod_modEditor

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function enable ()
{ // function enable
return;

$document = $this->document;
if ($document->printableLayout)
return;

if ($document->actions ('wysiwyg', 'enable'))
unset ($document->user->data['flags']['modContent_wysiwygDisabled']);
elseif ($document->actions ('wysiwyg', 'disable'))
$document->user->data['flags']['modContent_wysiwygDisabled'] = 1;

if (isset ($document->user->data['flags']['modContent_wysiwygDisabled']))
{ // disabled
$document->mod->interface->appendChild ('modEditor_contextWisiwygEnable')
->url (true, true, '_wysiwyg-enable');
} // disabled
else
{ // enabled
$document->mod->interface->appendChild ('modEditor_contextWisiwygDisable')
->url (true, true, '_wysiwyg-disable');
$document->contentEditable = true;
} // enabled
} // function enable

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;

if (!$document->contentEditable)
return;

$control = $store->control->read ('modEditor_module');
$mod->data = $control;

$mod->data['local']['ref_submit'] = $document->url (true, true, '_preload-wysiwyg');

$mod->enabled = true;
} // function setModule

} // class eclMod_modEditor

?>