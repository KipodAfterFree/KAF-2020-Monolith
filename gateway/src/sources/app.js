function load() {
    Module.load("UI", "API", "Global:Popup").then(() => {
        UI.show("home");
        UI.hide("loading");
    });
}

function provision(token) {
    // Create the call
    let call = API.call("provision", "provision", {
        name: UI.find("input-name").value,
        email: UI.find("input-email").value,
        token: token
    });
    // Create a popup
    Popup.progress("Validating information...", call).then((result) => {
        Popup.alert("Provisioning successful!", result).then(() => {
            location.reload();
        });
    }).catch((error) => {
        Popup.alert("An error has occurred.", error).then(() => {
            location.reload();
        });
    });
}