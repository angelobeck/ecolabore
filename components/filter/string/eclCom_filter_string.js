
class eclCom_filter_string extends eclCom {
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

        if (this.value == '' && this.control.flags && this.control.flags.default)
            this.value = this.control.flags.default;
    }

    disconnectedCallback() {
        if (!this.formulary)
            return;

        this.formulary.unsubscribe(this);
    }

    get _autocomplete_() {
        if (this.control.flags && this.control.flags.autocomplete)
            return this.control.flags.autocomplete;
        else
            return 'off';
    }

    handleChange(event) {
        if (!this.formulary || !this.control.flags || !this.control.flags.target)
            return;

        this.formulary.setField(this.control.flags.target, event.detail.value);
    }

}
