
class eclMod_filter_select extends eclMod {
    control;
    formulary;
    value = '';

    connectedCallback() {
        this.api('formulary');
        this.api('control');
    }

    refreshCallback() {
        if (!this.formulary)
            return;

        if (this.control.flags && this.control.flags.target)
            this.value = this.formulary.getField(this.control.flags.target);

        if (this.value === undefined || this.value === null || this.value === '') {
            if (this.control.flags && this.control.flags.default)
                this.value = this.control.flags.default;

            else if (this.control.optionsList) {
                let options = this.control.optionsList.split(', ');
                this.value = options[0];
                this.formulary.setField(this.control.flags.target, this.value);
            }
        }
    }

    handleChange(event) {
        if (!this.formulary || !this.control.flags || !this.control.flags.target)
            return;

        this.formulary.setField(this.control.flags.target, event.detail.value);
    }

    get _options_() {
        if (this.control.optionsList) {
            let options = this.control.optionsList.split(', ');
            return options.map(value => {
                return {
                    label: value,
                    selected: value == this.value,
                    value: value
                };
            });
        } else {
            return [];
        }
    }

}
