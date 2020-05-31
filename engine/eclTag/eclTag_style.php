<?php

class eclTag_style
{ // class eclTag_style

static function render ($render)
{ // function render
$render->tagsStack[] = array ('style', true);
$render->scissorsIndex++;
$render->scissors[$render->scissorsIndex] = array (
'position' => strlen ($render->buffer), 
'label' => 'style', 
'once' => false
);
} // function render

static function close ($render)
{ // function close
$name = $render->stackPop ('style');
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

} // class eclTag_style

?>