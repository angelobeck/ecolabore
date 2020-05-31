<?php

class eclTag_cut
{ // class eclTag_cut

static function render ($render, $arguments)
{ // function render
@list ($name, $once) = explode (' ', $arguments[0]);
$render->tagsStack[] = array ('cut', $name);
$render->scissorsIndex++;
$render->scissors[$render->scissorsIndex] = array (
'position' => strlen ($render->buffer), 
'label' => $name, 
'once' => $once
);
} // function render

static function close ($render)
{ // function close
$name = $render->stackPop ('cut');
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

while ($render->pasteIndex and $render->pastePointer[$render->pasteIndex] > $position)
{ // clear paste points
$render->pasteIndex--;
} // clear paste points

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

} // class eclTag_cut

?>