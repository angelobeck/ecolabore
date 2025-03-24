<?php

class eclMod
{
    public eclEngine_page $page;
    public array $children = [];
    public array $data = [];

    public function __construct(eclEngine_page $page)
    {
        $this->page = $page;
    }

    public function connectedCallback(): void
    {

    }

    public function appendChild($fromData): eclEngine_child
    {
        $child = new eclEngine_child($this->page, $fromData);
        $this->children[] = $child;
        return $child;
    }

}
