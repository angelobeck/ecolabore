
class eclMod_tag_button extends eclMod {
    label = '';
    style = '';
    value = '';

    connectedCallback() {
        this.api('label');
        this.api('style');
        this.api('value');
    }

    handleClick() {
        this.dispatchEvent(new CustomEvent("click", {
            detail: {
                value: this.value
            }
        }));
    }

}
