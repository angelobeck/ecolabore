
class eclMarkdownBlock_header {

    static check(line, indent, markdownBlock) {
        if (line.startsWith('# '))
            return true;
        else if (line.startsWith('## '))
            return true;
        else if (line.startsWith('### '))
            return true;
        else if (line.startsWith('#### '))
            return true;
        else if (line.startsWith('##### '))
            return true;
        else if (line.startsWith('###### '))
            return true;
        else
            return false;
    }

    static block(line, indent, markdownBlock) {
        var level;
        if (line.startsWith('# '))
            level = 1;
        else if (line.startsWith('## '))
            level = 2;
        else if (line.startsWith('### '))
            level = 3;
        else if (line.startsWith('#### '))
            level = 4;
        else if (line.startsWith('##### '))
            level = 5;
        else if (line.startsWith('###### '))
            level = 6;

        line = line.substring(level + 1);
        markdownBlock.newParagraph = false;
        var block = new eclEngine_markdownBlock();
        block.lines = [line];
        block.tag = 'h' + level;
        return block;
    }

}