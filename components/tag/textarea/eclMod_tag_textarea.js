
class eclMod_tag_textarea extends eclMod {
    label = '';
    required = false;
    value = '';

    input;

    connectedCallback() {
        this.api('label');
        this.api('required');
        this.api('value');
    }

    focus() {
        this.input.focus();
    }

    handleChange(event) {
        this.dispatchEvent(new CustomEvent("change", {
            detail: {
                value: event.currentTarget.value
            }
        }));
    }

}
