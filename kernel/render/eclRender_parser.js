
class eclRender_parser {
    templateName;
    root;
    current;
    tokens;
    index;
    length;

    parse(rootNode, tokens, module) {
        this.templateName = module.constructor.name;
        this.root = rootNode;
        this.root.component = new eclRender_component(rootNode, module, rootNode.children);
        rootNode.children = [];
        this.current = this.root;
        this.tokens = tokens;
        this.index = 0;
        this.length = tokens.length;

        var node;
        while (this.index < this.length) {
            if (this.checkTokenType('<')) {
                this.index++;
                this.parseTagContext();
                continue;
            }

            if (this.checkTokenType('{')) {
                node = new eclRender_nodeDinamic(this.current, this.tokens[this.index]['value']);
                this.current.children.push(node);
                this.index++;
                continue;
            }

            if (this.checkTokenType('doctype')) {
                node = new eclRender_nodeDoctype(this.current, this.tokens[this.index]['value']);
                this.current.children.push(node);
                this.index++;
                continue;
            }

            if (this.index < this.length) {
                node = new eclRender_nodeText(this.current, this.tokens[this.index]['value']);
                this.current.children.push(node);
                this.index++;
            }
        }

        if (this.root !== this.current)
            this.throwError('Incorrect tag structure: some tag was not closed properly. Use "<tagName />" on single tags');
        return this.root.children;
    }

    parseTagContext() {
        var node;
        var attribute;
        if (this.checkTokenType('/')) {
            this.index++;
            if (!this.checkTokenType('name')) {
                this.throwError('expecting "' + this.current.value + '" after "</"');
            } else if (this.tokens[this.index].value !== this.current.value) {
                this.throwError("expecting " + this.current.value + " on closing tag, " + this.tokens[this.index]['value'] + ' guive');
            }
            this.index++;
            if (!this.checkTokenType('>')) {
                this.throwError("expecting '>' after </" + this.current.value);
            }
            this.index++;
            this.current = this.current.parent;
            return;
        }

        if (!this.checkTokenType('name')) {
            this.throwError('expected tag name after "<"');
        }

        var tagName = this.tokens[this.index]['value'];
        switch (tagName) {
            case 'cut':
                node = new eclRender_nodeCut(this.current, 'cut');
                break;

            case 'mod':
                node = new eclRender_nodeModule(this.current, 'mod');
                break;

            case 'paste':
                node = new eclRender_nodePaste(this.current, 'paste');
                break;

            case 'slot':
                node = new eclRender_nodeSlot(this.current, 'slot');
                break;

            case 'tag':
                node = new eclRender_nodeTag(this.current, tagName);
                break;

            case 'template':
                node = new eclRender_nodeTemplate(this.current, 'template');
                break;

            default:
                if (registeredTags && registeredTags[tagName]) {
                    node = new eclRender_nodeTag(this.current, tagName);
                } else {
                    node = new eclRender_nodeElement(this.current, tagName);
                }
        }

        this.current.children.push(node);
        this.current = node;
        this.index++;

        while (this.index < this.length) {
            if (this.checkTokenType('/')) {
                this.index++;
                if (!this.checkTokenType('>')) {
                    this.throwError('missing ">" after "/"');
                }
                this.index++;
                this.current.closingTag = false;
                this.current = this.current.parent;
                return;
            }

            if (this.checkTokenType('>')) {
                this.index++;
                return;
            }

            if (!this.checkTokenType('name')) {
                this.throwError('unexpected token inside tag <' + this.current.value + '>');
            }

            attribute = this.tokens[this.index]['value'];
            this.index++;

            if (!this.checkTokenType('=')) {
                this.current.staticAttributes[attribute] = "true";
                continue;
            }

            this.index++;

            if (this.checkTokenType('string')) {
                this.current.staticAttributes[attribute] = this.tokens[this.index]['value'];
                this.index++;
                continue;
            }

            if (this.checkTokenType('{')) {
                this.current.dinamicAttributes[attribute] = this.tokens[this.index]['value'];
                this.index++;
                continue;
            }

            this.throwError('attribute value missing or invalid inside tag <' + this.current.value + ' ' + attribute + '=?>');

        }
        this.throwError('missing ">" ');
    }

    checkTokenType(type) {
        if (this.index >= this.length)
            return false;

        return this.tokens[this.index]['type'] === type;
    }

    throwError(message) {
        var templateName = this.templateName;
        var line = 0;
        if (this.index < this.length) {
            line = this.tokens[this.index]['line'];
        } else {
            line = end(this.tokens)['line'];
        }
        throw new Error(`Template parsing error: ${message} on line ${line} in template of module ${templateName}`);
    }

}
