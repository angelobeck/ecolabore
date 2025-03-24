
class eclScope_label extends eclScope {
    static getScope(page, value, argument) {
        if (!argument)
            return [];

        var data = store.staticContent.open(argument);
        if (!isset(data.text))
            return [];
        if (data.text.label)
            return data.text.label;
        if (data.text.title)
            return data.text.title;
        else
            return [];
    }

}