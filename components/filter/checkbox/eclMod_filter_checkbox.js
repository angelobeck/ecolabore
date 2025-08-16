
class eclMod_filter_checkbox extends eclMod {
    control;
    formulary;
    value = false;

    connectedCallback() {
        this.api('formulary');
        this.api('control');
    }

    refreshCallback() {
        if (!this.formulary)
            return;

        if (this.control.flags && this.control.flags.target)
            this.value = this.formulary.getField(this.control.flags.target);
    }

    disconnectedCallback() {
        if (!this.formulary)
            return;

        this.formulary.unsubscribe(this);
    }

    get _checked_() {
        if (this.control.flags && this.control.flags.trueValue && this.value === this.control.flags.trueValue)
            return true;
        else
            return false;
    }

    handleChange(event) {
        if (!this.formulary || !this.control.flags || !this.control.flags.target)
            return;

        var value = event.detail.value;
        if (value && isset(this.control.flags.trueValue))
            value = this.control.flags.trueValue;
        else if (!value && isset(this.control.flags.falseValue))
            value = this.control.flags.falseValue;
        this.formulary.setField(this.control.flags.target, value);
    }

}
