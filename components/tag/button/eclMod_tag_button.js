
class eclMod_tag_button extends eclMod {
    label = '';
    id = '';
    style = '';
    value = '';

    connectedCallback() {
        this.api('label');
        this.api('id');
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

    handleKeydown(event) {
        if (event.altKey || event.ctrlKey || event.metaKey || event.shiftKey)
            return;

        switch (event.key) {
            case ' ':
            case 'Enter':
                this.handleClick();
        }
    }

}
