
class eclMarkdownBlock_unorderedList {

    static check(line, indent, markdownBlock) {
        if (line.startsWith('- '))
            return true;
        else if (line.startsWith('* '))
            return true;
        else
            return false;
    }

    static block(line, indent, markdownBlock) {
        var lines = [];
        line = line.substring(2);
        lines.push(line);
        indent += '  ';
        markdownBlock.newParagraph = false;

        while (markdownBlock.lines.length > 0 && (markdownBlock.lines[0].startsWith(indent) || markdownBlock.lines[0].trim() == '')) {
            line = markdownBlock.lines.shift();
            if (line.trim() == '') {
                lines.push('');
                markdownBlock.newParagraph = true;
            } else {
                line = line.substring(indent.length);
                lines.push(line);
                markdownBlock.newParagraph = false;
            }
        }

        var block = new eclEngine_markdownBlock();
        block.lines = lines;
        block.tag = 'ul';
        block.renderer = eclMarkdownBlock_unorderedList.render;
        return block;
    }

    static render(block, markdownBlocks) {
        var tag = block.tag;

        var ul = [block];
        while (markdownBlocks.length && markdownBlocks[0].tag == tag) {
            block = markdownBlocks.shift();
            ul.push(block);
        }

        var buffer = '<' + tag + '>';
        while (ul.length > 0) {
            block = ul.shift();
            let markdown = new eclEngine_markdown(block.lines);
            buffer += '<li>' + markdown.render() + '</li>';
        }
        buffer += '</' + tag + '>';

        return buffer;
    }

}
