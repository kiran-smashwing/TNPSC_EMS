<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
// Secure channel for emergency alerts.
// Optionally, you can add additional checks like IP whitelist validation.
Broadcast::channel('alerts', function ($user) {
    dd($user);
    return $user &&
        $user->role &&
        $user->role->role_department === 'MCD';  // Add additional checks as needed
});
