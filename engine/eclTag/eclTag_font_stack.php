<?php

class eclTag_font_stack
{ // class eclTag_font_stack

static function render ($render, $arguments)
{ // function render
if (!$arguments)
return;

$font = $render->block ('fonts/' . $arguments[0]);
if (isset ($font['local']['font-stack']))
$render->buffer .= $font['local']['font-stack'];

if (isset ($font['local']['font-face']))
{ // append font-face
$fontFace = $font['local']['font-face'];
if (!isset ($render->cuts['font-face']))
{ // first cut
$render->cuts['font-face'] = $fontFace . CRLF;
return;
} // first cut

if (strpos ($render->cuts['font-face'], $fontFace) === false)
$render->cuts['font-face'] .= $fontFace . CRLF;
} // append font-face
} // function render

static function close ($render)
{ // function close

} // function close

} // class eclTag_font_stack

?>