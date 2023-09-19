<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;


    public function setComponentsAttribute($value)
    {
        $components = [];

        foreach ($value as $array_item) {
            if (!is_null($array_item['cname'])) {
                $components[] = $array_item;
            }
        }

        $this->attributes['components'] = json_encode($components);
    }


    // public function astDetails()
    // {
    //     return $this->hasMany(AstDetails::class, 'asset_id','id')
    //         ->leftJoin('ast_look_ups', 'ast_details.ast_lookup_id', '=', 'ast_look_ups.id')
    //         ->select('ast_look_ups.data_values', 'ast_details.*')
    //         ->orderBy('ast_components.cmpnt_data', 'ASC');
    // }

    public function astDetails()
    {
        return $this->hasMany(AstDetails::class, 'asset_id','id')
            ->leftJoin('ast_components', 'ast_details.component_id', '=', 'ast_components.id')
            ->select('ast_details.id','ast_details.asset_id','ast_components.*')
            ->orderBy('ast_components.id', 'ASC');
    }



    public function astBrand()
    {
        return $this->hasOne(AstLookUp::class, 'id', 'brand');
    }
    public function astCurnUsername()
    {
        return $this->hasOne(AstHistory::class, 'asset_id', 'id')
            ->join('users', 'ast_histories.ast_user_id', '=', 'users.id')
            ->select('users.name as username', 'ast_histories.*');
    }

    public function astType()
    {
        return $this->hasOne(AstLookUp::class, 'id', 'ast_type');
    }
    // public function astCurnUsername(){
    //     return $this->hasOne(User::class, 'users.id','ast_histories.ast_user_id')->join('ast_histories', 'ast_histories.asset_id', '=', 'assets.id')->select('users.name','ast_histories.*');
    // }
    public function astHistories()
    {
        return $this->hasMany(AstHistory::class, 'asset_id', 'id')
            ->leftJoin('users', 'ast_histories.ast_user_id', '=', 'users.id')
            ->select('users.name as username', 'ast_histories.*');
    }

    protected $casts = [
        'components' => 'array'
    ];
}
