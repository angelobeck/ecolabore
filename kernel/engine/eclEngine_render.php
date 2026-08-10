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

    public function render(eclCom $component, array $params = [], array $slot = []): string
    {
        global $store;
        $templateName = get_class($component);
        $template = $store->componentTemplate->open($templateName);
        $tokens = $this->tokenizer->tokenize($template);
        $children = $this->parser->parse($tokens, $component, $templateName, $slot);
        foreach ($params as $key => $value) {
            $component->$key = $value;
        }
        $component->connectedCallback();
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
                    $buffer .= $node->mediator->getProperty($node->value, true);
                    break;

                default:
                    if (isset($node->dinamicAttributes['if:true']) and !$node->mediator->getProperty($node->dinamicAttributes['if:true']))
                        break;
                    if (isset($node->dinamicAttributes['if:false']) and $node->mediator->getProperty($node->dinamicAttributes['if:false']))
                        break;
                    switch ($node->value) {
                        case 'com':
                            $buffer .= $this->renderComponent($node);
                            break;

                        case 'slot':
                            $buffer .= $this->renderChildren($node->mediator->slot);
                            break;

                        default:
                            $buffer .= $this->renderTag($node);
                    }
            }
        }
        return $buffer;
    }

    private function renderComponent(eclRender_node $node): string
    {
        if (isset($node->staticAttributes['name']))
            $name = $node->staticAttributes['name'];
        else if (isset($node->dinamicAttributes['name']))
            $name = $node->mediator->getProperty($node->dinamicAttributes['name']);
        else
            return '';

        if (isset($node->staticAttributes['prefix']))
            $name = $node->staticAttributes['prefix'] . $name;

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $name))
            return '';

        $component = $this->page->components->$name;
        $params = [];
        foreach ($node->staticAttributes as $attribute => $value) {
            if (strpos($attribute, ':') === false and $attribute !== 'name' and $attribute !== 'prefix')
                $params[$attribute] = $value;
        }
        foreach ($node->dinamicAttributes as $attribute => $value) {
            if (strpos($attribute, ':') === false and $attribute !== 'name')
                $params[$attribute] = $node->mediator->getProperty($value);
        }

        return $this->render($component, $params, $node->children);
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
                    $value = $node->mediator->getProperty($value, true);
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
        $array = $node->mediator->getProperty($node->dinamicAttributes['for:each']);
        if (!is_array($array) or count($array) === 0)
            return $buffer;

        if (isset($node->staticAttributes['for:item']))
            $name = $node->staticAttributes['for:item'];
        else if (isset($node->dinamicAttributes['for:item']))
            $name = $node->dinamicAttributes['for:item'];
        else
            $name = 'item';

        array_unshift($node->mediator->scopes, []);

        foreach ($array as $item) {
            $node->mediator->scopes[0][$name] = $item;
            $buffer .= $this->renderChildren($node->children);
        }

        array_shift($node->mediator->scopes);
        return $buffer;
    }

}
