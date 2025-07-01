function setTag(tagName, className) {
    if (!window.registeredTags)
        window.registeredTags = {};

    window.registeredTags[tagName] = className;
}
