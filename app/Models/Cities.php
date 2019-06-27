<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    //
    public $table = 'cities';

    public $timestamps = false;
    public $fillable = [
        'pid',
        'name',
        'level',
        //'path'
    ];

    public function childCities() {
        return $this->hasMany('App\Models\Cities', 'pid', 'id');
    }

    public function allChildrenCities()
    {
        return $this->childCities()->with('allChildrenCities');
    }

    //加入的运费模板
    public function freightTems()
    {
        return $this->belongsToMany('App\Models\FreightTem','cities_freights','cities_id','freights_id')->withPivot('freight_type','freight_first_count','the_freight','freight_continue_count','freight_continue_price');
    }

    public function getParentCitiesAttribute(){
        $parentCities=Cities::find($this->pid);
        if(!empty($parentCities)){
            return $parentCities->name;
        }
    }

    public function getParentCitiesObjAttribute(){
        $parentCities=Cities::find($this->pid);
        if(!empty($parentCities)){
            return $parentCities;
        }
    }

    public function getFreightDetailAttribute(){
        return getFreightInfoByCitiesId($this->id);
    }


}
