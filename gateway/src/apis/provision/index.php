<?php

include_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "base" . DIRECTORY_SEPARATOR . "Base.php";

Base::handle(function ($action, $parameters) {
    if ($action === "provision") {
        if (!isset($parameters->name) || !isset($parameters->email) || !isset($parameters->token))
            throw new Error("Parameter error");
        // Create keystore
        $keystore = new Keystore("provision");
        // Store name
        $name = $parameters->name;
        $name = strtolower($name);
        $id = bin2hex($name);
        // Check keystore
        if ($keystore->exists($id))
            throw new Error("Your already provisioned an instance");
        // TODO: Verify with CTF platform
        // Create new keystore entry
        $keystore->insert($id);
        // Generate an instance ID
        $instance = Base::random(10);
        // Set iID in keystore
        $keystore->set($id, "instance", $instance);
        // TODO: Dispatch a Docker API call to create the instance
        // TODO: Dispatch a Caddy2 API call to enable proxy routing to the instance
        // TODO: Dispatch an email to the team with their instance link at http://$instace.monolith.ctf.kaf.sh
        return "Check your email for instance details";
    }
    throw new Error("Unknown action");
});