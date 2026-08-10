
class eclCom_tag_text extends eclCom {
    content = '';
    format = 'auto';

    connectedCallback() {
        this.api('content');
        this.api('format');
    }

    get _paragraphs_() {
        var paragraphs = [];
        var content = page.selectLanguage(this.content);
        var lines = content.value.split("\n");
        for (let i = 0; i < lines.length; i++) {
            let line = lines[i].trim();
            if (line.length > 0)
                paragraphs.push(line);
        }
        return paragraphs;
    }

}
