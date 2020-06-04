<?php

namespace App;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;


class JsonData extends Eloquent
{
   protected $connection = 'mongodb';
   protected $guarded = [];
   // protected $fillable = ['final_data'];
}
