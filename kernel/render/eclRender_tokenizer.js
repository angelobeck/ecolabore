
class eclRender_tokenizer {
    fromString;
    index;
    line;
    length;
    tokens;

    tokenize(fromString) {
        this.fromString = fromString;
        this.index = 0;
        this.line = 1;
        this.length = fromString.length;
        this.tokens = [];
    
        var char;
        var tagContext = false;
        while (this.index < this.length) {
            char = this.fromString.charAt(this.index);
            switch (char) {
                case "<":
                    if (this.fromString.startsWith("<!--", this.index)) {
                        this.findBoundary("-->");
                        break;
                    }

                    this.tokens.push({ type: char, line: this.line });
                    this.index++;
                    tagContext = true;
                    break;

                case ">":
                    tagContext = false;

                case "=":
                case "/":
                    this.tokens.push({ type: char, line: this.line });
                    this.index++;
                    break;

                case "{":
                    this.index++;
                    this.tokens.push({ type: char, value: this.findBoundary("}"), line: this.line });
                    break;

                case "\n":
                    this.index++;
                    this.line++;
                    break;

                case "'":
                case '"':
                    if (tagContext) {
                        this.index++;
                        this.tokens.push({ type: "string", value: this.findBoundary(char), line: this.line });
                        break;
                    }

                default:
                    if (tagContext) {
                        if (/[a-zA-Z]/.test(char)) {
                            this.tokens.push({ type: "name", value: this.findName(), line: this.line });
                        } else {
                            this.index++;
                        }
                    } else {
                        let literal = this.findLiteral();
                        if (literal.trim().length > 0) {
                            this.tokens.push({ type: "string", value: literal, line: this.line });
                        }
                    }
            }
        }
        return this.tokens;
    }

    findBoundary(boundary) {
        var startPosition = this.index;
        var endPosition = this.fromString.indexOf(boundary, this.index);
        this.index = endPosition + boundary.length;
        return this.fromString.substring(startPosition, endPosition);
    }

    findName() {
        var buffer = "";
        while (this.index < this.length) {
            const char = this.fromString.charAt(this.index);
            if (/[a-zA-Z0-9.:_-]/.test(char)) {
                this.index++;
                buffer += char;
            } else {
                return buffer;
            }
        }
        return buffer;
    }

    findLiteral() {
        var buffer = "";
        while (this.index < this.length) {
            const char = this.fromString.charAt(this.index);
            if (char !== "<" && char !== "{") {
                this.index++;
                buffer += char;
            } else {
                return buffer;
            }
        }
        return buffer;
    }

}
