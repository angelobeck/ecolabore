
class eclMod_tag_markdown extends eclMod {
    value = '';
    code = '';

    connectedCallback() {
        this.api('value');
        this.track('code');
    }

    get _html_() {
        var lines = this.value.split("\n");
        var markdown = new eclEngine_markdown(lines);
        this.code =  markdown.render();
        return this.code;
    }

}
