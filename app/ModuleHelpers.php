<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

function GetTitles()
{
    $module = DB::table('view_get_titles_modules')
        ->where('role_id', Auth::user()->role_id)
        ->orderBy('must')
        ->get();

    return $module;
}

function GetModuleNames($title)
{
    $module_names = DB::table('view_get_module_name')
        ->where('role_id', Auth::user()->role_id)
        ->where('title', $title)
        ->orderBy('must')
        ->get();

    return $module_names;
}

function GetModulesAllInfo($module_name)
{
    $sub_module_names = DB::table('view_get_modules_all_info')
        ->where('role_id', Auth::user()->role_id)
        ->where('module_name', "$module_name")
        ->orderBy('sub_module_must')
        ->get();

    return $sub_module_names;
}

function GetLayoutInformaiton()
{
    $user['role'] = DB::table('users')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->where('users.id', Auth::user()->id)
        ->first();

    return $user;
    die();
}
