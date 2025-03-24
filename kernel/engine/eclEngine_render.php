<?php

class eclEngine_render
{
    private eclEngine_page $page;
    private eclRender_tokenizer $tokenizer;
    private eclRender_parser $parser;

    public function __construct(eclEngine_page $page)
    {
        $this->page = $page;
        $this->tokenizer = new eclRender_tokenizer();
        $this->parser = new eclRender_parser();
    }

    public function render(eclMod $module, array $params = [], array $slot = []): string
    {
        global $store;
        $templateName = get_class($module);
        $template = $store->moduleTemplate->open($templateName);
        $tokens = $this->tokenizer->tokenize($template);
        $children = $this->parser->parse($tokens, $module, $templateName, $slot);
        foreach ($params as $key => $value) {
            $module->$key = $value;
        }
        $module->connectedCallback();
        return $this->renderChildren($children);
    }

    private function renderChildren(array $children): string
    {
        $buffer = '';
        $scope = [];
        foreach ($children as $node) {
            switch ($node->type) {
                case 'static_content':
                    $buffer .= $node->value;
                    break;

                case 'dinamic_content':
                    $buffer .= $node->component->getProperty($node->value, true);
                    break;

                default:
                    if (isset($node->dinamicAttributes['if:true']) and !$node->component->getProperty($node->dinamicAttributes['if:true']))
                        break;
                    if (isset($node->dinamicAttributes['if:false']) and $node->component->getProperty($node->dinamicAttributes['if:false']))
                        break;
                    switch ($node->value) {
                        case 'mod':
                            $buffer .= $this->renderModule($node);
                            break;

                        case 'slot':
                            $buffer .= $this->renderChildren($node->component->slot);
                            break;

                        default:
                            $buffer .= $this->renderTag($node);
                    }
            }
        }
        return $buffer;
    }

    private function renderModule(eclRender_node $node): string
    {
        if (isset($node->staticAttributes['name']))
            $name = $node->staticAttributes['name'];
        else if (isset($node->dinamicAttributes['name']))
            $name = $node->component->getProperty($node->dinamicAttributes['name']);
        else
            return '';

        if (isset($node->staticAttributes['prefix']))
            $name = $node->staticAttributes['prefix'] . $name;

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $name))
            return '';

        $module = $this->page->modules->$name;
        $params = [];
        foreach ($node->staticAttributes as $attribute => $value) {
            if (strpos($attribute, ':') === false and $attribute !== 'name' and $attribute !== 'prefix')
                $params[$attribute] = $value;
        }
        foreach ($node->dinamicAttributes as $attribute => $value) {
            if (strpos($attribute, ':') === false and $attribute !== 'name')
                $params[$attribute] = $node->component->getProperty($value);
        }

        return $this->render($module, $params, $node->children);
    }

    private function renderTag(eclRender_node $node): string
    {
        $buffer = '';
        if ($node->value !== 'template') {
            $buffer = '<' . $node->value . '';
            foreach ($node->staticAttributes as $attribute => $value) {
                if (strpos($attribute, ':') === false)
                    $buffer .= ' ' . $attribute . '="' . $value . '"';
            }
            foreach ($node->dinamicAttributes as $attribute => $value) {
                if (strpos($attribute, ':') === false) {
                    $value = $node->component->getProperty($value, true);
                    if ($value !== '')
                        $buffer .= ' ' . $attribute . '="' . $value . '"';
                }
            }

            if (!$node->closingTag) {
                $buffer .= ' />';
                return $buffer;
            }

            $buffer .= '>';
        }

        if (isset($node->dinamicAttributes['for:each']))
            $buffer .= $this->renderLoop($node);
        else
            $buffer .= $this->renderChildren($node->children);

        if ($node->value !== 'template') {
            $buffer .= '</' . $node->value . '>';
        }
        return $buffer;
    }

    private function renderLoop(eclRender_node $node): string
    {
        $buffer = '';
        $array = $node->component->getProperty($node->dinamicAttributes['for:each']);
        if (!is_array($array) or count($array) === 0)
            return $buffer;

        if (isset($node->staticAttributes['for:item']))
            $name = $node->staticAttributes['for:item'];
        else if (isset($node->dinamicAttributes['for:item']))
            $name = $node->dinamicAttributes['for:item'];
        else
            $name = 'item';

        array_unshift($node->component->scopes, []);

        foreach ($array as $item) {
            $node->component->scopes[0][$name] = $item;
            $buffer .= $this->renderChildren($node->children);
        }

        array_shift($node->component->scopes);
        return $buffer;
    }

}
