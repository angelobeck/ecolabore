
class eclEngine_markdownInline {
    content;
    buffer = '';

    static patterns = {
        bold: /^\*\*(([^*]|\*[^*])+)\*\*/,
        code: /^[`]{2}([^`]+)[`]{2}/,
        italic: /^\_([^\_]+)\_/,
        link: /^\[([^\]]+)\]\(([^)]+)\)/,
        tag: /^\[(^\]]+\])/
    };

    constructor(content) {
        this.content = content;
    }

    render() {
        this.buffer = '';
        var found, match;
        var names = Object.keys(eclEngine_markdownInline.patterns);
        while (this.content.length > 0) {
            found = false;
            for (let i = 0; i < names.length; i++) {
                const name = names[i];
                const pattern = eclEngine_markdownInline.patterns[name];
                if ((match = pattern.exec(this.content)) !== null) {
                    this.content = this.content.substring(match[0].length);
                    this[name](match);
                    found = true;
                    break;
                }
            }

            if (!found) {
                this.buffer += this.content[0];
                this.content = this.content.substring(1);
            }
        }
        return this.buffer;
    }

    bold(match) {
        this.buffer += '<b>';
        var render = new eclEngine_markdownInline(match[1]);
        this.buffer += render.render();
        this.buffer += '</b>';
    }

    code(match) {
        var content = match[1];
        content.replace(/\"/g, '#q')
        this.buffer += '<code><escape value="' + content + '" /></code>';
    }

    italic(match) {
        this.buffer += '<i>';
        var render = new eclEngine_markdownInline(match[1]);
        this.buffer += render.render();
        this.buffer += '</i>';
    }

    link(match) {
        this.buffer += '<a href="' + match[2] + '">';
        var render = new eclEngine_markdownInline(match[1]);
        this.buffer += render.render();
        this.buffer += '</a>';
    }

    tag(match) {
        var parts = match[1].split(':');
        const name = parts[0];

        const translation = {
            arquivo: 'file',
            audio: 'audio',
            file: 'file',
            figura: 'img',
            imagem: 'img',
            img: 'img'
        };

        this.content = this.content.substring(match[0].length);

        if (!translation[name]) {
            return;
        }

        this.buffer += '<ecl-' + translation[name] + ' value="' + (parts[1] || '') + '" />';
    }

}
