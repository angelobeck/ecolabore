
class eclMod_tag_link extends eclMod {
    ariaCurrent = '';
    label = '';
    url = '#';
    style = '';

    handleClick() {
        navigate(this.url);
    }

}
