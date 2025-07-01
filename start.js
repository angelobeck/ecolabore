
window.io = new eclEngine_io();
window.store = new eclEngine_store();
window.root = new eclEngine_application();
window.page = new eclEngine_page();

function init(event = false) {

    page.reset();
    page.route();
    page.dispatch();
    page.render();
}

window.addEventListener("popstate", init);

window.addEventListener("load", init);
