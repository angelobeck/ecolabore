<?php

class eclRender_parser
{
    private string $templateName;
    private eclRender_node $root;
    private eclRender_node $current;
    private array $tokens;
    private int $index;
    private int $length;

    public function parse(array $tokens, eclMod $module, string $templateName, array $slot = []): array
    {
        $this->templateName = $templateName;
        $this->root = new eclRender_node(null, '', '');
        $this->root->component = new eclRender_component($module, $slot);
        $this->current = $this->root;
        $this->tokens = $tokens;
        $this->index = 0;
        $this->length = count($tokens);
        while ($this->index < $this->length) {
            if ($this->checkTokenType('<')) {
                $this->index++;
                $this->parseTagContext();
                continue;
            }

            if ($this->checkTokenType('{')) {
                $node = new eclRender_node($this->current, 'dinamic_content', $this->tokens[$this->index]['value']);
                $this->current->children[] = $node;
                $this->index++;
                continue;
            }

            if ($this->checkTokenType('doctype')) {
                $node = new eclRender_node($this->current, 'static_content', '<' . $this->tokens[$this->index]['value'] . '>');
                $this->current->children[] = $node;
                $this->index++;
                continue;
            }

            if ($this->index < $this->length) {
                $node = new eclRender_node($this->current, 'static_content', $this->tokens[$this->index]['value']);
                $this->current->children[] = $node;
                $this->index++;
            }
        }

        if ($this->root !== $this->current)
            $this->throwError('Incorrect tag structure: some tag was not closed properly. Use "<tagName />" on single tags');
        return $this->root->children;
    }

    private function parseTagContext()
    {
        if ($this->checkTokenType('/')) {
            $this->index++;
            if (!$this->checkTokenType('name')) {
                $this->throwError('expecting "' . $this->current->value . '" after "</"');
            } elseif ($this->tokens[$this->index]['value'] !== $this->current->value) {
                $this->throwError("expecting " . $this->current->value . " on closing tag, " . $this->tokens[$this->index]['value'] . ' guive');
            }
            $this->index++;
            if (!$this->checkTokenType('>')) {
                $this->throwError("expecting '>' after </" . $this->current->value);
            }
            $this->index++;
            $this->current = $this->current->parent;
            return;
        }

        if (!$this->checkTokenType('name')) {
            $this->throwError('expected tag name after "<"');
        }

        $node = new eclRender_node($this->current, 'tag', $this->tokens[$this->index]['value']);
        $this->current->children[] = $node;
        $this->current = $node;
        $this->index++;

        while ($this->index < $this->length) {
            if ($this->checkTokenType('/')) {
                $this->index++;
                if (!$this->checkTokenType('>')) {
                    $this->throwError('missing ">" after "/"');
                }
                $this->index++;
                $this->current->closingTag = false;
                $this->current = $this->current->parent;
                return;
            }

            if ($this->checkTokenType('>')) {
                $this->index++;
                return;
            }

            if (!$this->checkTokenType('name')) {
                $this->throwError('unexpected token inside tag <' . $this->current->value . '>');
            }

            $attribute = $this->tokens[$this->index]['value'];
            $this->index++;

            if (!$this->checkTokenType('=')) {
                $this->current->staticAttributes[$attribute] = "true";
                continue;
            }

            $this->index++;

            if ($this->checkTokenType('string')) {
                $this->current->staticAttributes[$attribute] = $this->tokens[$this->index]['value'];
                $this->index++;
                continue;
            }

            if ($this->checkTokenType('{')) {
                $this->current->dinamicAttributes[$attribute] = $this->tokens[$this->index]['value'];
                $this->index++;
                continue;
            }

            $this->throwError('attribute value missing or invalid inside tag <' . $this->current->value . ' ' . $attribute . '=?>');

        }
        $this->throwError('missing ">" ');
    }

    private function checkTokenType(string $type)
    {
        if ($this->index >= $this->length)
            return false;

        return $this->tokens[$this->index]['type'] === $type;
    }

    private function throwError(string $message)
    {
        $templateName = $this->templateName;
        $line = 0;
        if ($this->index < $this->length) {
            $line = $this->tokens[$this->index]['line'];
        } else {
            $line = end($this->tokens)['line'];
        }
        throw new Exception("Template parsing error: $message on line $line in template of module $templateName");
    }

}
