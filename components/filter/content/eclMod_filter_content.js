
class eclMod_filter_content extends eclMod {
    control;
    formulary;
    value = '';

    connectedCallback() {
        this.api('formulary');
        this.api('control');
    }

    get _paragraphs_() {
        var paragraphs = [];
var value = '';
if(this.control.text && this.control.text.content)
    value = page.selectLanguage(this.control.text.content).value;
paragraphs = value.split("\n");
        return paragraphs;
    }
}
