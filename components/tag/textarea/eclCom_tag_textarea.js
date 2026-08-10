
class eclCom_tag_textarea extends eclCom {
    label = '';
    id = '';
    required = false;
    value = '';

    input;

    connectedCallback() {
        this.api('label');
        this.api('id');
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
