
class eclMarkdownBlock_codeFence {

    static check(line, indent, markdownBlock) {
        if (line.startsWith('```'))
            return true;
        else
            return false;
    }

    static block(line, indent, markdownBlock) {
        var lines = [];
        const closeTag = indent + '```';
        markdownBlock.newParagraph = true;

        var closeTagFound = false;
        var closeTagIndex = 0;
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
        block.renderer = eclMarkdownBlock_codeFence.render;
        return block;
    }

    static render(block, markdownBlocks) {
        var buffer = '<code><pre><escape value="';
        var content = block.lines.join('\n');
        content = content.replace(/["]/g, '#q');
        buffer += content;
        buffer += '" /></pre></code>';

        return buffer;
    }

}
