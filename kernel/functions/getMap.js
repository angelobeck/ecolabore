
function getMap(applicationName) {
    if (isset(applicationsMaps[applicationName]))
        return applicationsMaps[applicationName];
    else
        return [];
}
