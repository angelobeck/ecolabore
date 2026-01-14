
class eclMod_tag_select extends eclMod {
    label = '';
    id = '';
    options = [];
    required = false;
    value = '';

    select;

    connectedCallback() {
        this.api('label');
        this.api('id');
        this.api('options');
        this.api('required');
        this.api('value');
    }

    focus() {
        this.select.focus();
    }

    handleChange(event) {
        this.dispatchEvent(new CustomEvent("change", {
            detail: {
                value: event.currentTarget.value
            }
        }));
    }

    get _options_() {
        if (!Array.isArray(this.options))
            return [];

        return this.options.map(option => {
            return {
                label: option.label || '',
                selected: option.selected || false,
                value: option.value || ''
            };
        });
    }

}
