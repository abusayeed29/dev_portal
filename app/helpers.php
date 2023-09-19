<?php

use App\Models\Permission;
use App\Models\TktSupervisor;
use App\Models\TktTeamUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


if(!function_exists('getReadPermission')){

    function getReadPermission($modId=null, $param=null){
        $result = Permission::where('user_id', Auth::user()->id)
                ->where('module_id', $modId)
                ->where('read', $param)
                ->first();
        if($result){
            return true;
        }else{
            return false;
        }
    }
}

if(!function_exists('getCreatePermission')){
    function getCreatePermission($modId=null, $param=null){
        $result = Permission::where('user_id', Auth::user()->id)
                ->where('module_id', $modId)
                ->where('create', $param)
                ->first();
        if($result){
            return true;
        }else{
            return false;
        }
    }
}

if(!function_exists('getUpdatePermission')){
    function getUpdatePermission($modId=null, $param=null){
        $result = Permission::where('user_id', Auth::user()->id)
                ->where('module_id', $modId)
                ->where('update', $param)
                ->first();
        if($result){
            return true;
        }else{
            return false;
        }
    }
}

if(!function_exists('getDeletePermission')){
    function getDeletePermission($modId=null, $param=null){
        $result = Permission::where('user_id', Auth::user()->id)
                ->where('module_id', $modId)
                ->where('delete', $param)
                ->first();
        if($result){
            return true;
        }else{
            return false;
        }
    }
}

if(!function_exists('getEmpUpdatePermission')){
    function getEmpUpdatePermission($modId=null, $param=null){
        $result = Permission::where('user_id', Auth::user()->id)
                ->where('module_id', $modId)
                ->where('update', $param)
                ->first();
        if($result){
            return true;
        }else{
            return false;
        }
    }
}

if(!function_exists('getUserName')){
    function getUserName($user_id = null){
        $result = User::where('id', $user_id)->select('name')->first();
        if($result){
            return $result->name;
        }else{
            return false;
        }
    }
}

if(!function_exists('getServiceDeptment')){
    function getServiceDeptment($user_id = null){
        $result = TktSupervisor::where('user_id', $user_id)->select('tkt_dprtmnt_id')->first();
        if($result){
            return $result->tkt_dprtmnt_id;
        }else{
            return false;
        }
    }
}
if(!function_exists('getServiceHeadDeptment')){
    function getServiceHeadDeptment($user_id = null){
        $result = TktTeamUser::where('user_id', $user_id)->select('tkt_department_id')->first();
        if($result){
            return $result->tkt_department_id;
        }else{
            return false;
        }
    }
}


