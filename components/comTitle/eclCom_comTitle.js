
class eclCom_comTitle extends eclCom
{
    title;

    connectedCallback()
    {
        this.title = page.application.data['text']['title'] || this.page.application.name;
    }

}
