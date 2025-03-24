
class eclScope_lang extends eclScope
{

    static getScope(page, value, argument)
    {
        scope = page.selectLanguage(value);
        if (scope['lang'] === page.lang) {
            return '';
        }

        return scope['lang'];
    }

}
