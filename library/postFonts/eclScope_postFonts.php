<?php

class eclScope_postFonts
{ // class eclScope_postFonts

static function get ($render, $arguments)
{ // function get
global $store;
if (!isset ($render->data['extras']['fonts']))
return false;

$document = $render->document;
$fonts = $render->data['extras']['fonts'];
$data = [];

$demonstration = $store->control->read ('dialog_fonts_demonstration');
if (isset ($document->session['scope-fonts-demonstration']))
$demonstration = array_replace ($demonstration, $document->session['scope-fonts-demonstration']);
$demonstration['ref_demonstration'] = $document->url ($document->domain->name, '-dialog', 'font-change-preview');

foreach (array ('light-' => '-light', '' => '', 'heavy-' => '-heavy') as $prefix => $sufix)
{ // each family
if (!isset ($fonts[$prefix . 'regular']))
continue;

$local = [];
$local['font-family'] = 'ecl-' . $render->data['name'] . $sufix;

foreach (array ('regular', 'italic', 'bold', 'bold-italic') as $style)
{ // each style
if (!isset ($fonts[$prefix . $style]))
continue;

$local[$style . '-url'] = $document->urlFiles ($fonts[$prefix . $style], true, '-files/fonts');
$parts = explode ('.', $fonts[$prefix . $style]);
$local[$style . '-format'] = end ($parts);
} // each style

if (isset ($fonts[$prefix . 'stack-start']))
{ // start of stack
$buffer = '';
$list = explode (',', $fonts[$prefix . 'stack-start']);
foreach ($list as $font)
{ // each font
$font = trim ($font, '"\' ');
if (isset ($font[0]))
{ // add font
$buffer .= 'local(' . QUOT . $font . QUOT . '), ';
} // add font
} // each font
if (isset ($buffer[0]))
$local['stack-start'] = $buffer;
} // start of stack
$buffer = $local['font-family'];
if (isset ($fonts[$prefix . 'stack-end']))
{ // end of stack
$list = explode (',', $fonts[$prefix . 'stack-end']);
foreach ($list as $font)
{ // each font
$font = trim ($font, '"\' ');
if (isset ($font[0]))
{ // add font
if (strpos ($font, ' '))
$buffer .= ", " . TIC . $font . TIC . "";
else
$buffer .= ', ' . $font;
} // add font
} // each font
} // end of stack
$local['stack'] = $buffer;

if ($prefix == 'light-')
$caption = $store->control->read ('labels/detail/font_light');
elseif ($prefix == 'heavy-')
$caption = $store->control->read ('labels/detail/font_heavy');
else
$caption = $store->control->read ('labels/detail/font_normal');

$local['caption'] = $caption['text']['caption'];

$data['children'][] = $document->createListItem ($local, $demonstration);
} // each family
return $data;
} // function get

} // class eclScope_postFonts

?>