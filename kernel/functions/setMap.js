function setMap(parentName, childName) {
    if (!window.applicationsMaps)
        window.applicationsMaps = {};
    if (!window.applicationsMaps[parentName])
        window.applicationsMaps[parentName] = [];

    window.applicationsMaps[parentName].push(childName);
}
