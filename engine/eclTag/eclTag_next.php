<?php

class eclTag_next
{ // class eclTag_next

static function render ($render)
{ // function render
if (!$render->index)
return;

if ($render->index < count ($render->children))
{ // next item
$render->index++;
$render->data = $render->children[$render->index - 1]->data;
$render->me = $render->children[$render->index - 1]->me;
$render->data['index'] = $render->index;
if ($render->index == count ($render->children))
$render->data['last'] = 1;
return;
} // next item

$render->data = [];
$render->data = [];
return;
} // function render

static function close ($render)
{ // function close

} // function close

} // class eclTag_next

?>