<?php

class eclMod_modUser_quoteoftheday
{ // class eclMod_modUser_quoteoftheday

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;

$mod->data = $store->control->read ('modUser_quoteoftheday_module');

$quotes = $store->control->read ('modUser_quoteoftheday_quotes');

if (!isset ($quotes['quotes']))
return;

$max = count ($quotes['quotes']) - 1;
$n = mt_rand (0, $max);
if (!isset ($quotes['quotes'][$n]))
return;

$content = '<blockquote>' . $quotes['quotes'][$n][0] . '</blockquote><p style="text-align:right">(' . $quotes['quotes'][$n][1] . ')</p>';
$data = array ('text' => array ('content' => array ($document->lang => array (
1 => $content, 
2 => 1, 
5 => 2, 
6 => 1
))));

$mod->appendChild ($data);

$mod->enabled = true;
} // function setModule

} // class eclMod_modUser_quoteoftheday

?>