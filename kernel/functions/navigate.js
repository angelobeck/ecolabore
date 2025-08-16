
function navigate(href) {
    var lastPath = page.getPathFromUrl(window.location.href);
    var currentPath = page.getPathFromUrl(href);
    var rewindCount = getRewindCount(lastPath, currentPath);

    if (lastPath.length === currentPath.length && rewindCount + 1 === currentPath.length) {
        history.replaceState('', '', href);
        init();
        return;
    }

    /*
    if (lastPath.length > rewindCount) {
        history.go(rewindCount - lastPath.length);
        setTimeout(() => {
            history.pushState('', '', href);
            init();
        }, 200);
        return;
    }
*/

    history.pushState('', '', href);
    init();

    function getRewindCount(lastPath, currentPath) {
        var counter = 0;
        while (
            counter < lastPath.length &&
            counter < currentPath.length &&
            lastPath[counter] === currentPath[counter]
        ) {
            counter++;
        }
        return counter;
    }

}
