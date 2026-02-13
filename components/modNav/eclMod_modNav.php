<?php

class eclMod_modNav extends eclMod
{

    public function connectedCallback(): void
    {
        if (!$this->children) {
            foreach ($this->page->domain->children() as $child) {
                if (!$this->page->access($child->access, $child->groups))
                    continue;
                if (isset($child->data['flags']['modNav_show'])) {
                    $this->appendChild($child->data)
                        ->swapTitle()
                        ->url($child->path);
                }
            }
        }
    }

}
