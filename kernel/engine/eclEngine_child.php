<?php

class eclEngine_child
{
    public eclEngine_page $page;
    public $data = [];
    public array $children = [];

    public function __construct(eclEngine_page $page, $fromData)
    {
        global $store;
        $this->page = $page;
        if (is_object($fromData) and get_class($fromData) == 'eclEngine_application') {
            $this->data = $fromData->data;
        } else if (is_array($fromData)) {
            $this->data = $fromData;
        } else if (is_string($fromData)) {
            $this->data = $store->staticContent->open($fromData);
        }
    }

    public function appendChild($fromData): eclEngine_child
    {
        $child = new eclEngine_child($this->page, $fromData);
        $this->children[] = $child;
        return $child;
    }

    public function swapTitle(): eclEngine_child
    {
        if (isset($this->data['text']['label'])) {
            $this->data['text']['title'] = $this->data['text']['label'];
        }
        return $this;
    }

    public function url(array|bool $path = true, string|bool $lang = true, string|bool $action = true): eclEngine_child
    {
        $this->data['url'] = $this->page->url($path, $lang, $action);
        return $this;
    }

}
