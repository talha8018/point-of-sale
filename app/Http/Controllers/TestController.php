<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth;


class TestController extends Controller
{
    public function test()
    {
        /* $permission_name = 'movements-add';
        $permission = Permission::create(['name' => $permission_name]); */
        $all = Permission::get()->toArray();
        $user = Auth::user();
        foreach($all as $a)
        {
            if(!$user->hasPermissionTo($a['name']))
            {
                $user->givePermissionTo($a['name']);                        
            }
             
        }
    }
}
