<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    static public $IMAGE_COLUMNS = [
        'main_image', 'main_profile',
        'ad_image_top', 'ad_image_middle',
        'ad_image_bottom'
    ];

    protected $guarded = ['id'];
}
