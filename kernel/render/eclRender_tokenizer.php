<?php

class eclRender_tokenizer
{
    private string $template;
    private int $index;
    private int $line;
    private int $length;
    private array $tokens;

    public function tokenize(string $template): array
    {
        $this->template = $template;
        $this->length = strlen($template);
        $this->index = 0;
        $this->line = 1;
        $this->tokens = [];
        $tagContext = false;
        while ($this->index < $this->length) {
            $char = $this->template[$this->index];
            switch ($char) {
                case '<':
                    if (strpos($this->template, '<!--', $this->index) === $this->index) {
                        $this->findBoundary('-->');
                        break;
                    }

                    if (strpos($this->template, '<!', $this->index) === $this->index) {
                        $this->index++;
                        $this->tokens[] = ['type' => 'doctype', 'value' => $this->findBoundary('>'), 'line' => $this->line];
                        break;
                    }

                    $this->tokens[] = ['type' => $char, 'line' => $this->line];
                    $this->index++;
                    $tagContext = true;
                    break;

                case '>':
                    $tagContext = false;

                case '=':
                case '/':
                    $this->tokens[] = ['type' => $char, 'line' => $this->line];
                    $this->index++;
                    break;

                case '{':
                    $this->index++;
                    $this->tokens[] = ['type' => $char, 'value' => $this->findBoundary('}'), 'line' => $this->line];
                    break;

                case "\n":
                    $this->index++;
                    $this->line++;
                    break;

                case "'":
                case '"':
                    if ($tagContext) {
                        $this->index++;
                        $this->tokens[] = ['type' => 'string', 'value' => $this->findBoundary($char), 'line' => $this->line];
                        break;
                    }

                default:
                    if ($tagContext) {
                        if (preg_match('/[a-zA-Z]/', $char)) {
                            $this->tokens[] = ['type' => 'name', 'value' => $this->findName(), 'line' => $this->line];
                        } else {
                            $this->index++;
                        }
                    } else {
                        $literal = $this->findLiteral();
                        if (strlen(trim($literal)) > 0) {
                            $this->tokens[] = ['type' => 'string', 'value' => $literal, 'line' => $this->line];
                        }
                    }
            }
        }
        return $this->tokens;
    }

    private function findBoundary(string $boundary): string
    {
        $startPosition = $this->index;
        $endPosition = strpos($this->template, $boundary, $this->index);
        if ($endPosition === false) {
            $this->index = $this->length;
            return substr($this->template, $startPosition);
        }

        $pos = $startPosition;
        while (true) {
            $pos = strpos($this->template, "\n", $pos);
            if ($pos === false or $pos >= $endPosition) {
                break;
            }
            $pos++;
            $this->line++;
        }
        $this->index = $endPosition + strlen($boundary);
        return substr($this->template, $startPosition, $endPosition - $startPosition);
    }

    private function findName(): string
    {
        $buffer = $this->template[$this->index];
        $this->index++;
        while ($this->index < $this->length) {
            $char = $this->template[$this->index];
            if (preg_match('/[a-zA-Z0-9.:_-]/', $char)) {
                $buffer .= $char;
                $this->index++;
            } else {
                return $buffer;
            }
        }
        return $buffer;
    }

    private function findLiteral(): string
    {
        $buffer = '';
        while ($this->index < $this->length) {
            $char = $this->template[$this->index];
            if ($char === "\n") {
                $this->index++;
                $buffer .= $char;
                $this->line++;
            } elseif ($char !== '<' and $char !== '{') {
                $buffer .= $char;
                $this->index++;
            } else {
                return $buffer;
            }
        }
        return $buffer;
    }

}
