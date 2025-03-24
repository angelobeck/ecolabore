<?php

class eclRender_node
{
    public eclRender_component $component;
    public eclRender_node|null $parent;
    public string $type;
    public string $value;
    public array $staticAttributes = [];
    public array $dinamicAttributes = [];
    public array $children = [];
    public bool $closingTag = true;

    public function __construct(eclRender_node|null $parent, string $type, string $value)
    {
        $this->parent = $parent;
        $this->type = $type;
        $this->value = $value;
        if ($parent !== null) {
            $this->component = $parent->component;
        }
    }

}
