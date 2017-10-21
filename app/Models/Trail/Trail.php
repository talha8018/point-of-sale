<?php

namespace App\Models\Trail;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Trail extends Model
{
    protected $guarded = [];
    protected $timestamp = true;


    public static function makeTrail($description,$old_data,$new_data,$type)
    {
        Trail::create([
            'by'            => Auth::user()->id,
            'type'          => $type,
            'old_data'      => $old_data,
            'new_data'      => $new_data,
            'description'   => $description,
        ]);
    }
}
