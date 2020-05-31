<?php

class eclTag_loop
{ // class eclTag_loop

static function render ($render)
{ // function render
$render->index++;

// Se não houver um próximo item, prosseguiremos
if ($render->index > count ($render->children))
return false;

$render->data = $render->children[$render->index - 1]->data;
$render->me = $render->children[$render->index - 1]->me;
$render->data['index'] = $render->index;
if ($render->index == count ($render->children))
$render->data['last'] = 1;
return true;
} // function render

static function close ($render)
{ // function close

} // function close

} // class eclTag_loop

?>