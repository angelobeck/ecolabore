
class eclCom_tag_markdown extends eclCom {
    content = '';
    code = '';

    connectedCallback() {
        this.api('content');
        this.track('code');
    }

    get _html_() {
        if (typeof (this.content) !== 'string')
            this.content = '';

        var lines = this.content.split("\n");
        var markdown = new eclEngine_markdown(lines);
        this.code = markdown.render();
        return this.code;
    }

}
