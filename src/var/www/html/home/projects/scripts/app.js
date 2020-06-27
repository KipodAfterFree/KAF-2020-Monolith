function load() {
    document.body.background = "images/background/" + (Math.floor((Math.random() * 3)) + 1) + ".webp";
    projects();
}

function projects() {
    API.call("projects", "list", {
        extension: "png"
    }).then((results) => {
        UI.clear(document.body);
        for (let result of results) {
            document.body.appendChild(UI.populate("project", result));
        }
    }).catch(alert);
}