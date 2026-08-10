<?php

class eclCom_comTitle extends eclCom
{
    public $title;

    public function connectedCallback(): void
    {
        $this->title = $this->page->application->data['text']['title'] ?? $this->page->application->name;
    }

}
