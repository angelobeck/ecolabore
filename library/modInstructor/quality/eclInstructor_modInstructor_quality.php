<?php

class eclInstructor_modInstructor_quality
{ // class eclInstructor_modInstructor_quality

static function route ($instructor)
{ // function route
global $store;
$document = $instructor->document;
$me = $document->application;

if (!$document->access (4, $document->domain->groups))
return false;

if ($instructor->messages)
return true;

$tasks = $store->control->read ('modInstructor_quality_index');
$length = count ($tasks);
$last = [];

if ($me->name == '-new-section' and isset ($document->actions['create'][1]) and $store->control->read ('section' . ucfirst ($document->actions['create'][1]) . '_helpCreate'))
return self::taskExec ($document, 'section' . ucfirst ($document->actions['create'][1]), $instructor, 'Create');

for ($index = 0; $index < $length; $index++)
{ // each task
$task = $tasks[$index];
$next = isset ($tasks[$index + 1]) ? $tasks[$index + 1] : [];

if (self::taskIsDone ($document, $task))
{ // task is done
$last = $task;
continue;
} // task is done

if (isset ($document->actions['login']))
{ // login
if (self::isInSection ($document, $last))
return self::taskExec ($document, $last, $instructor, 'Return', $task);

return self::taskExec ($document, 'modInstructor_quality', $instructor, 'Redirect', $last);
} // login

if ($document->actions ('instructor', 'redirect'))
return self::taskExec ($document, $last, $instructor, 'Return', $task);

if (self::isCreating ($document, $task))
return self::taskExec ($document, $task, $instructor, 'Create');

if (self::isInSection ($document, $last))
return self::taskExec ($document, $last, $instructor, 'Done', $task);

if ($document->actions ('instructor', 'message', 'create') and isset ($me->data['flags']['section_type']) and $store->control->read ('section' . ucfirst ($me->data['flags']['section_type']) . '_helpCreate'))
return self::taskExec ($document, 'section' . ucfirst ($me->data['flags']['section_type']), $instructor, 'Create');

$name = $document->application->applicationName;
if ($name == 'section' and isset ($document->application->data['flags']['section_type']))
$name = 'section' . ucfirst ($document->application->data['flags']['section_type']);
if ($store->control->read ($name . '_helpNavigate'))
{ // navigation context help
if ($document->application->isDomain)
return self::taskExec ($document, $name, $instructor, 'Navigate');

return self::taskExec ($document, $name, $instructor, 'Navigate', $task);
} // navigation context help

return self::taskExec ($document, 'modInstructor_quality', $instructor, 'Navigate', $task);
} // each task

return false;
} // function route

static function taskIsDone ($document, $task)
{ // function taskIsDone
global $store;
$data = $store->control->read ($task . '_helpInstructor');
if (!isset ($data['check']) or !is_array ($data['check']) or !isset ($data['check']['mode']))
return true;

switch ($data['check']['mode'])
{ // switch mode
case 'section':
return $store->domainContent->findMarker ($document->domain->domainId, $data['check']['marker'], MODE_SECTION);
} // switch mode

return false;
} // function taskIsDone

static function isInSection ($document, $task)
{ // function isInSection
global $store;
$me = $document->application;
$data = $store->control->read ($task . '_helpInstructor');
if (!isset ($data['check']) or !is_array ($data['check']) or !isset ($data['check']['mode']))
return false;
switch ($data['check']['mode'])
{ // switch mode
case 'section':
if (isset ($me->data['mode']) and $me->data['mode'] == MODE_SECTION and $me->data['marker'] == $data['check']['marker'])
return true;

return false;
} // switch mode

return false;
} // function isInSection

static function isCreating ($document, $task)
{ // function isCreating
global $store;
$me = $document->application;
$data = $store->control->read ($task . '_helpInstructor');
if (!isset ($data['create']) or !is_array ($data['create']) or !isset ($data['create']['mode']))
return false;
switch ($data['create']['mode'])
{ // switch mode
case 'section':
if (!isset ($data['create']['type']))
return false;
if ($me->name == '-new-section' and $document->actions ('create', $data['create']['type']))
return true;

return false;
} // switch mode

return false;
} // function isCreating

static function taskExec ($document, $task, $instructor, $type, $next=[])
{ // function taskExec
global $store;
$name = $task . '_help' . $type;
$message = $instructor->addMessage ($name);

if (!$next)
return true;

$data = $store->control->read ($next . '_helpInstructor');

if ($type == 'Redirect')
{ // redirect
if (!isset ($data['check']))
return true;

switch ($data['check']['mode'])
{ // switch mode
case 'section':
$id = $store->domainContent->findMarker ($document->domain->domainId, $data['check']['marker']);
$message->url ($store->domainContent->pathway ($document->domain->domainId, $id), true, '_instructor-redirect');
return true;
} // switch mode
return true;
} // redirect

if (isset ($data['parent']))
{ // goto parent
switch ($data['parent']['mode'])
{ // switch mode
case 'section':
$id = $store->domainContent->findMarker ($document->domain->domainId, $data['parent']['marker']);
if ($id)
{ // section found
$pathway = $store->domainContent->pathway ($document->domain->domainId, $id);
break;
} // section found

case 'index':
$pathway = $document->domain->pathway;
break;
} // switch mode
} // goto parent
else
$pathway = $document->domain->pathway;

switch ($data['create']['mode'])
{ // switch creating mode
case 'section':
$pathway[] = '-new-section';
$message->url ($pathway, true, '_create-' . $data['create']['type']);
return true;
} // switch creating mode
return true;
} // function taskExec

} // class eclInstructor_modInstructor_quality

?>