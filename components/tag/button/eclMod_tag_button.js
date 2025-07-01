
class eclMod_tag_button extends eclMod {
    label = '';
    style = '';

    handleClick() {
        this.dispatchEvent(new CustomEvent("click"));
    }

}
