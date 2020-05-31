<?php

class eclFilter_personaliteFields_mail
{ // class eclFilter_personaliteFields_mail

static function create ($fieldName, $control, $formulary)
{ // function create
$document = $formulary->document;

// type
if (isset ($control['flags']['type']))
$control['type'] = $control['flags']['type'];
else
$control['type'] = 'text';

$control['name'] = $fieldName;

if (isset ($formulary->data['local']['mail']))
$control['value'] = $formulary->htmlSpecialChars ($formulary->data['local']['mail']);
elseif (isset ($control['local']['share_user_data']) and isset ($formulary->document->user->data['local']['mail']) and isset ($formulary->data['share_user_data']))
$control['value'] = $formulary->document->user->data['local']['mail'];

$formulary->appendChild ($control);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
if (!isset ($formulary->received[$fieldName][0]))
{ // empty
if (isset ($control['local']['required']) and $control['local']['required'])
return $formulary->setRequiredMsg ($control, $fieldName);

unset ($formulary->data['local']['mail']);
return;
} // empty

$formulary->data['local']['mail'] = $formulary->received[$fieldName];

if (preg_match ('/^[a-zA-Z0-9._-]+[@][a-zA-Z0-9_-]+[.][a-zA-Z0-9._-]+[;].+/', $formulary->received[$fieldName]))
$formulary->setErrorMsg ($control, $fieldName, 'personaliteFields_mail_alertMultipleNotAllowed');

if (!preg_match ('/^[a-zA-Z0-9._-]+[@][a-zA-Z0-9_-]+[.][a-zA-Z0-9._-]+$/', $formulary->received[$fieldName]))
$formulary->setErrorMsg ($control, $fieldName, 'personaliteFields_mail_alertInvalidMail', $formulary->received[$fieldName]);
} // function save

static function view ($fieldName, $control, $formulary)
{ // function view
$document = $formulary->document;

if (!isset ($formulary->data['local']['mail']))
return;

$control['type'] = 'view';

$mail = $formulary->data['local']['mail'];
$content = '<a href="mailto:' . QUOT . $mail . QUOT . '>' . $mail . '</a>';
if ($document->protectedLayout)
$content = '<span class="humperstilshen-shuffled" data-shuffled=' . QUOT . base64_encode ($content) . QUOT . '></span>';
$control['content'] = $document->textMerge ($content);

$formulary->appendChild ($control);
} // function view

static function column ($document, $data, $name, $field)
{ // function column
if (!isset ($data['local']['mail']))
return array ('caption' => $document->textMerge ('-'));

$mail = $data['local']['mail'];
$content = '<a href="mailto:' . QUOT . $mail . QUOT . '>' . $mail . '</a>';
if ($document->protectedLayout)
$content = '<span class="humperstilshen-shuffled" data-shuffled=' . QUOT . base64_encode ($content) . QUOT . '></span>';

return array ('caption' => $document->textMerge ($content));
} // function column

} // class eclFilter_personaliteFields_mail

?>