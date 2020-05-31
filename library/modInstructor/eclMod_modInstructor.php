<?php

class eclMod_modInstructor
{ // class eclMod_modInstructor

public $document;
public $messages = [];

public function __construct ($document)
{ // function __construct
global $store;
$this->document = $document;

if ($document->access (1))
{ // context help
$help = $document->application->applicationName . '_helpAbout';
if ($help == 'section_helpAbout' and isset ($document->application->data['flags']['section_type']))
$help = 'section' . ucfirst ($document->application->data['flags']['section_type']) . '_helpAbout';
$data = $store->control->read ($help);
if ($data)
{ // context help found
$document->mod->context->appendChild ($help)
->swapTitle ()
->virtual ()
->dialog ($help);
} // context help found
} // context help
} // function __construct

public function addMessage ($name, $formulary=false)
{ // function addMessage
global $store;
$document = $this->document;
$data = $store->control->read ($name);
if (!$data)
return $document->createListItem ();

// audio
// if (isset ($data['local']['audio']))
// $data['audio'] = $document->urlFiles ($data['local']['audio'], true, '-media');
$data['audio'] = '';
// autoplay
if (!isset ($document->session['modInstructor'][$name]))
$data['instructor_autoplay'] = 1;
$document->session['modInstructor'][$name] = true;

// onload
if (!isset ($data['local']['instructor_onmenuopen']))
$data['instructor_onload'] = 1;

// name
$data['name'] = 'message' . strval (count ($this->messages));
$message = $this->document->createListItem ($data);
$this->messages[] = $message;
if ($formulary)
$message->childrenMerge ($formulary->create ());
return $message;
} // function addMessage

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;

if (!$document->domain->domainId)
return;

// Enable or disable instructor
return;

eclInstructor_modInstructor_welcome::route ($this) or eclInstructor_modInstructor_quality::route ($this) or eclInstructor_modInstructor_ava::route ($this);

if (!$this->messages)
return;

$mod->data = $store->control->read ('modInstructor_module');
$mod->children = $this->messages;
$mod->enabled = true;
} // function setModule

} // class eclMod_modInstructor

?>