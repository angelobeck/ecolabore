
class eclCom_tag_checkbox extends eclCom {
    checked = false;
    label = '';
    id = '';
    name = '';

    input;

    connectedCallback() {
        this.api('checked');
        this.api('label');
        this.api('id');
        this.api('name');
    }

    focus() {
        this.input.focus();
    }

    handleChange(event) {
        var checked = event.currentTarget.checked;
        var value = true;
        if(checked === "false" || checked === false)
            value = false;

        this.dispatchEvent(new CustomEvent("change", {
            detail: {
                name: this.name,
                checked: value
            }
        }));
    }

}
