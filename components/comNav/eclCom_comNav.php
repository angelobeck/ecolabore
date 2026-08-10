<?php

class eclCom_comNav extends eclCom
{

    public function connectedCallback(): void
    {
        if (!$this->children) {
            foreach ($this->page->domain->children() as $child) {
                if (!$this->page->access($child->access, $child->groups))
                    continue;
                if (isset($child->data['flags']['comNav_show'])) {
                    $this->appendChild($child->data)
                        ->swapTitle()
                        ->url($child->path);
                }
            }
        }
    }

}
