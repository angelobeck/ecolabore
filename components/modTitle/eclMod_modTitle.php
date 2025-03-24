<?php

class eclMod_modTitle extends eclMod
{
    public $title;

    public function connectedCallback(): void
    {
        $this->title = $this->page->application->data['text']['title'] ?? $this->page->application->name;
    }

}
