<?php

class eclMod_modList extends eclMod
{
    public bool $showModule = false;

    public function connectedCallback(): void
    {
        if (!$this->children) {
            foreach ($this->page->application->children() as $child) {
                if (!$this->page->access($child->access, $child->groups))
                    continue;
                if (isset($child->data['flags']['modList_show']) || isset($child->data['id']))
                    $this->appendChild($child->data)
                        ->url($child->path);
            }
        }
        if ($this->children)
            $this->showModule = true;
    }

}
