
class eclEngine_markdown {
    block;
    blocks = [];
    classNames = [];

    constructor(lines) {
        this.block = new eclEngine_markdownBlock();
        this.block.lines = lines;
        this.block.newParagraph = true;

        if (registeredClasses.eclMarkdownBlock) {
            this.classNames = Object.keys(registeredClasses.eclMarkdownBlock);
        }

        this.parseLines();
    }

    parseLines() {
        var indent, line;

        while (this.block.lines.length > 0) {
            line = this.block.lines.shift();
            [line, indent] = this.lineIndentation(line);

            if (line.trim() == '') {
                this.block.newParagraph = true;
                continue;
            }

            let found = false;
            for (let i = 0; i < this.classNames.length; i++) {
                const name = this.classNames[i];
                const renderer = registeredClasses.eclMarkdownBlock[name];
                if (renderer.check(line, indent, this.block)) {
                    this.blocks.push(renderer.block(line, indent, this.block));
                    found = true;
                    break;
                }
            }

            if (found)
                continue;

            if (this.block.newParagraph)
                this.blockParagraph(line, indent);
            else if (this.blocks.length == 0)
                this.blockParagraph(line, indent);
            else
                this.blocks[this.blocks.length - 1].lines.push(line);
        }
    }

    lineIndentation(line) {
        var indent = '';
        while (line.length > 0 && indent.length < 4) {
            if (line.startsWith(' ')) {
                indent += ' ';
                line = line.substring(1);
            } else {
                break;
            }
        }

        return [line, indent];
    }

    blockParagraph(line, indent) {
        var block = new eclEngine_markdownBlock();
        block.lines = [line];
        block.tag = 'p';

        this.block.newParagraph = false;
        this.blocks.push(block);
    }

    render() {
        var buffer = '';
        while (this.blocks.length > 0) {
            let block = this.blocks.shift();
            if (block.renderer) {
                buffer += block.renderer(block, this.blocks);
            } else if (block.tag) {
                let content = new eclEngine_markdownInline(block.lines.join(' ')).render();
                buffer += '<' + block.tag + '>' + content + '</' + block.tag + '>';
            }
        }
        return buffer;
    }

}
