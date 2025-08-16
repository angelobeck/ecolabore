
class eclMod_tag_link extends eclMod {
    ariaCurrent = '';
    label = '';
    style = '';
    url = '#';

    connectedCallback() {
        this.api('ariaCurrent');
        this.api('label');
        this.api('style');
        this.api('url');
    }

    handleClick() {
        navigate(this.url);
    }

}
