<?php

class eclTag_text_language_caption
{ // class eclTag_text_language_caption

static function render ($render, $arguments)
{ // function render
global $store;

$data = $store->control->read ('labels/lang/' . $render->document->lang);
if (!isset ($data['text']['caption']))
return;
$field = $data['text']['caption'];

$render->render ($render->document->textSelectLanguage ($field));
} // function render

static function close ($render)
{ // function close

} // function close

} // class eclTag_text_language_caption

?>