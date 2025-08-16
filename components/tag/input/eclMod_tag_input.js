
class eclMod_tag_input extends eclMod {
    autocomplete = "off";
    label = '';
    required = false;
    type = 'text';
    value = '';

    input;

    connectedCallback() {
        this.api('autocomplete');
        this.api('label');
        this.api('required');
        this.api('type');
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
