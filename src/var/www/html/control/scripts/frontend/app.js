const MANAGER_ENDPOINT = "scripts/backend/manager/manager.php";
const MANAGER_API = "manager";

function load(loggedIn) {
    view("app");
    if (loggedIn) {
        manager_load(() => view("home"));
    } else {
        view("nologin");
    }
}

function manager_load(callback) {
    api(MANAGER_ENDPOINT, MANAGER_API, "read", {}, (success, result, error) => {
        if (success) {
            if (result.hasOwnProperty("database") && result.hasOwnProperty("parameter")) {
                get("create-button").onclick = () => manager_create(result.database);
                for (let name in result.database) {
                    if (result.database.hasOwnProperty(name)) {
                        let value = result.database[name];
                        if (value.hasOwnProperty("groups") && value.hasOwnProperty("endpoint")) {
                            let manageDiv = document.createElement("div");
                            let manageName = document.createElement("p");
                            let manageGroups = document.createElement("input");
                            let manageEndpoint = document.createElement("input");
                            let manageRemove = document.createElement("button");
                            let manageAppend = document.createElement("button");
                            let manageButtons = document.createElement("div");

                            let accessDiv = document.createElement("div");
                            let accessExecute = document.createElement("button");
                            let accessCopy = document.createElement("input");
                            let accessURI = () => "execute/?name=" + name + "&" + result.parameter + "=" + get("access-endpoints-parameter").value;
                            let accessURL = () => window.location.origin + (window.location.pathname.split("/").slice(-1)[0].includes(".") ? window.location.pathname.replace(window.location.pathname.split("/").slice(-1)[0], "") : window.location.pathname) + accessURI();

                            apply({row: ""}, accessDiv);
                            accessDiv.style.backgroundColor = "#DDDDEE";
                            accessExecute.innerText = name;
                            accessExecute.onclick = () => window.location = accessURL();
                            accessCopy.readonly = true;
                            accessCopy.value = "Copy";
                            accessCopy.onclick = () => {
                                accessCopy.value = accessURL();
                                accessCopy.select();
                                document.execCommand("copy");
                            };
                            accessDiv.appendChild(accessExecute);
                            accessDiv.appendChild(accessCopy);
                            get("access-endpoints-list").appendChild(accessDiv);

                            apply({column: ""}, manageDiv);
                            apply({row: ""}, manageButtons);
                            manageDiv.style.backgroundColor = "#DDDDEE";
                            manageRemove.style.backgroundColor = "#FF3300";
                            manageAppend.style.backgroundColor = "#00AA66";
                            manageRemove.innerText = "Remove " + name;
                            manageAppend.innerText = "Append Parameter";
                            manageName.innerText = name;
                            manageGroups.placeholder = "Endpoint groups, Separated by a comma.";
                            manageEndpoint.placeholder = "Endpoint URI";
                            manageEndpoint.value = value.endpoint;
                            manageGroups.value = manager_groups_to_text(value.groups);
                            let oninput = () => {
                                let endpoint = manageEndpoint.value;
                                if (endpoint.length > 0) {
                                    api(MANAGER_ENDPOINT, MANAGER_API, "write", {
                                        name: name,
                                        groups: manager_text_to_groups(manageGroups.value),
                                        endpoint: endpoint
                                    }, null, accounts_fill());
                                }
                            };
                            manageAppend.onclick = () => manageEndpoint.value += "#(" + result.parameter + ")#";
                            manageRemove.onclick = () => api(MANAGER_ENDPOINT, MANAGER_API, "write", {
                                name: name
                            }, () => window.location.reload(), accounts_fill());
                            manageGroups.oninput = oninput;
                            manageEndpoint.oninput = oninput;
                            manageDiv.appendChild(manageName);
                            manageDiv.appendChild(manageGroups);
                            manageDiv.appendChild(manageEndpoint);
                            manageButtons.appendChild(manageRemove);
                            manageButtons.appendChild(manageAppend);
                            manageDiv.appendChild(manageButtons);
                            get("manage-list").appendChild(manageDiv);
                        }
                    }
                }
            }
            if (result.hasOwnProperty("groups")) {
                for (let g = 0; g < result.groups.length; g++) {
                    let group = result.groups[g];
                    let accessDiv = document.createElement("div");
                    let accessExecute = document.createElement("button");
                    let accessCopy = document.createElement("input");
                    let accessURI = () => "execute/?group=" + group + "&" + result.parameter + "=" + get("access-groups-parameter").value;
                    let accessURL = () => window.location.origin + (window.location.pathname.split("/").slice(-1)[0].includes(".") ? window.location.pathname.replace(window.location.pathname.split("/").slice(-1)[0], "") : window.location.pathname) + accessURI();

                    apply({row: ""}, accessDiv);
                    accessDiv.style.backgroundColor = "#DDDDEE";
                    accessExecute.innerText = group;
                    accessExecute.onclick = () => window.location = accessURL();
                    accessCopy.readonly = true;
                    accessCopy.value = "Copy";
                    accessCopy.onclick = () => {
                        accessCopy.value = accessURL();
                        accessCopy.select();
                        document.execCommand("copy");
                    };
                    accessDiv.appendChild(accessExecute);
                    accessDiv.appendChild(accessCopy);
                    get("access-groups-list").appendChild(accessDiv);
                }
            }
            callback();
        }
    }, accounts_fill());
}

function manager_text_to_groups(value) {
    value = value.replace(" ", "");
    return value.split(",");
}

function manager_groups_to_text(groups) {
    let value = null;
    for (let g = 0; g < groups.length; g++) {
        let group = groups[g];
        if (value === null) {
            value = group;
        } else {
            value += ", " + group;
        }
    }
    return value;
}

function manager_create(database) {
    let name = get("create-name").value;
    if (name.length > 0) {
        if (!database.hasOwnProperty(name)) {
            api(MANAGER_ENDPOINT, MANAGER_API, "write", {
                name: name
            }, (success, result, error) => {
                if (success) {
                    window.location.reload();
                } else {
                    get("create-error").innerText = error;
                }
            }, accounts_fill());
        } else {
            get("create-error").innerText = "Endpoint with this name already exists.";
        }
    } else {
        get("create-error").innerText = "Name must not be empty";
    }
}