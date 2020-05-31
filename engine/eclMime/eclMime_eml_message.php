<?php

class eclMime_eml_message
{ // class eclMime_eml_message

public $headers = [];
public $alternativeContents = [];
public $relatedContents = [];
public $attachedContents = [];

public function addAlternativeContent ($content, $head)
{ // function addAlternativeContent
$this->alternativeContents[] = array ('head' => $head, 'content' => $content);
} // function addAlternativeContent

public function addRelatedContent ($content, $head)
{ // function addRelatedContent
$this->relatedContents[] = array ('head' => $head, 'content' => $content);
} // function addRelatedContent

public function addAttachment ($content, $head)
{ // function addAttachment
$this->attachedContents[] = array ('head' => $head, 'content' => $content);
} // function addAttachment

} // class eclMime_eml_message

?>