<?php

class eclCom_comList extends eclCom
{
    public bool $showComponent = false;

    public function connectedCallback(): void
    {
        if (!$this->children) {
            foreach ($this->page->application->children() as $child) {
                if (!$this->page->access($child->access, $child->groups))
                    continue;
                if (isset($child->data['flags']['comList_show']) || isset($child->data['id']))
                    $this->appendChild($child->data)
                        ->url($child->path);
            }
        }
        if ($this->children)
            $this->showComponent = true;
    }

}
