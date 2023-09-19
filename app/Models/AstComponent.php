<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AstComponent extends Model
{
    use HasFactory;


    // public function astCurnUsername()
    // {
    //     return $this->hasOne(AstHistory::class, 'asset_id', 'id')
    //         ->join('users', 'ast_histories.ast_user_id', '=', 'users.id')
    //         ->select('users.name as username', 'ast_histories.*');
    // }

    public function astType()
    {
        return $this->hasOne(AstLookUp::class, 'data_keys', 'ast_type')
                ->where('data_type', 'type');
    }
    public function componentStatus()
    {
        return $this->hasOne(AstLookUp::class, 'data_keys', 'status')
                ->where('data_type', 'ast.status');
    }


    public function cmpntName()
    {
        return $this->hasOne(AstLookUp::class, 'data_keys', 'cmpnt_name')
                ->where('data_type', 'ast.component');
    }


    
}
