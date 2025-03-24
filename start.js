
window.store = new eclEngine_store();
window.root = new eclEngine_application();
window.page = new eclEngine_page();

function init(event) {
    page.reset();
    page.route();
    page.dispatch();
    page.render();
}

    window.addEventListener("hashchange", init);

window.addEventListener("load", init);
