
class eclMod_tag_modal extends eclMod {
    message = '';
    name = 'alert';
    context = {};

    header;

    connectedCallback() {
        this.api('message');
        this.api('name');
        this.api('context');
    }

    renderedCallback() {
        if (this.header)
            this.header.focus();
    }

    get _content_() {
        var control = store.staticContent.open(this.message);

        if (control && control.text && control.text.content) {
            var content = control.text.content.pt.value;
            if (this.context.label) {
                let index = content.indexOf('{label}');
                if(index) {
                    content = content.substring(0, index) + this.context.label.pt.value + content.substring(index + 7);
                }
            }

            if (this.context.value) {
                let index = content.indexOf('{value}');
                if(index) {
                    content = content.substring(0, index) + this.context.value + content.substring(index + 7);
                }
            }

            return content;
        }
        return '';
    }

    get _title_() {
        var control = store.staticContent.open(this.message);
        return control.text.title.pt.value;
    }

    closeAlert() {
        page.alertClose(this.name);
    }

}
