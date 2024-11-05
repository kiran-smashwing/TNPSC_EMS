<?php

if (!function_exists('current_user')) {
    function current_user()
    {
        $role = session('auth_role');
        return $role ? Auth::guard($role)->user() : null;
    }
}