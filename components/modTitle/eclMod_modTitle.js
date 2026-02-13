
class eclMod_modTitle extends eclMod
{
    title;

    connectedCallback()
    {
        this.title = page.application.data['text']['title'] || this.page.application.name;
    }

}
