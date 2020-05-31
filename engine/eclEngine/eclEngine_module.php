<?php

class eclEngine_module extends eclEngine_listItem
{ // class eclEngine_module

public $document;
public $data = [];
public $children = [];
public $enabled = false;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function tr ()
{ // function tr
return $this->appendChild ();
} // function tr

public function td ($data)
{ // function td
if (!$this->children)
$this->appendChild ();

$child = $this->document->createListItem ($data);
$this->children[count ($this->children) - 1]->children[] = $child;
return $child;
} // function td

} // class eclEngine_module

?>