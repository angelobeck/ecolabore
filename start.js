
window.io = new eclEngine_io();
window.store = new eclEngine_store();
window.root = new eclEngine_application();
window.page = new eclEngine_page();

page.sessionRestore();

function init(event = false) {
    if (page.blocked) {
        setTimeout(() => {
            init();
        }, 200);
        return;
    }

    page.route();
    if (page.blocked) {
        setTimeout(() => {
            init();
        }, 200);
        return;
    }
    page.reset();
    page.dispatch();
    page.render();
}

window.addEventListener("popstate", init);

window.addEventListener("load", init);
