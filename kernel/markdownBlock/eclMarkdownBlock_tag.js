
class eclMarkdownBlock_tag {

    static check(line, indent, markdownBlock) {
        if (/^\[[^]]+\]$/.test(line.trim()))
            return true;
        else
            return false;
    }

    static block(line, indent, markdownBlock) {
        var lines = [];
        const match = /\[([^]]+)\]/.exec(line);
        const parts = match[1].split(':');
        const closeTag = indent + '[/' + parts[0] + ']';
        markdownBlock.newParagraph = true;

        var closeTagFound = false;
        var closeTagIndex;
        for (closeTagIndex = 0; closeTagIndex < markdownBlock.lines.length; closeTagIndex++) {
            line = markdownBlock.lines[closeTagIndex];
            if (line.startsWith(closeTag)) {
                closeTagFound = true;
                break;
            }
        }

        if (closeTagFound) {
            for (let i = 0; i < closeTagIndex; i++) {
                line = markdownBlock.lines.shift();
                if (line.trim() == '') {
                    lines.push('');
                } else {
                    line = line.substring(indent.length);
                    lines.push(line);
                }
            }
        }
        markdownBlock.lines.shift();

        var block = new eclEngine_markdownBlock();
        block.lines = lines;
        block.tag = 'ecl-' + parts[0];
        block.value = parts[1] || '';
        block.renderer = eclMarkdownBlock_tag.render;
        return block;
    }

    static render(block, markdownBlocks) {
        var tag = block.tag;

        var buffer = '<' + tag + ' value="' + block.value + '"';
        if (block.lines.length == 0) {
            buffer += ' />';
            return buffer;
        }

        buffer += '>';

        let markdown = new eclEngine_markdown(block.lines);
        buffer += markdown.render();
        buffer += '</' + tag + '>';

        return buffer;
    }

}
