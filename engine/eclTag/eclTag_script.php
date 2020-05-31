<?php

class eclTag_script
{ // class eclTag_script

static function render ($render)
{ // function render
$render->tagsStack[] = array ('script', true);
$render->scissorsIndex++;
$render->scissors[$render->scissorsIndex] = array (
'position' => strlen ($render->buffer), 
'label' => 'script', 
'once' => false
);
} // function render

static function close ($render)
{ // function close
$name = $render->stackPop ('script');
if ($name === false)
return;

if (!$render->scissorsIndex)
return;

$position = $render->scissors[$render->scissorsIndex]['position'];
$label = $render->scissors[$render->scissorsIndex]['label'];
$once = $render->scissors[$render->scissorsIndex]['once'];

$render->scissorsIndex--;
$cut = substr ($render->buffer, $position);
$render->buffer = substr ($render->buffer, 0, $position);

if (is_bool ($label) or $label == '')
return;

if ($once)
{ // cut once

// Se já houvermos recortado este trecho
if (isset ($render->cutOnce[$once]))
return;
$render->cutOnce[$once] = true;
} // cut once

if (isset ($render->cuts[$label]))
$render->cuts[$label] .= $cut;
else
$render->cuts[$label] = $cut;
} // function close

} // class eclTag_script

?>