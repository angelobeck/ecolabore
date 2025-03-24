
class eclMod_modTitle extends eclMod
{
    title;

    connectedCallback()
    {
        this.title = this.page.application.data['text']['title'] || this.page.application.name;
    }

}
