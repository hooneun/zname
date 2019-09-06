<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Detail extends Model
{
    static public $IMAGE_COLUMNS = [
        'main_image', 'main_profile',
        'ad_image_top', 'ad_image_middle',
        'ad_image_bottom'
    ];

    protected $guarded = ['id'];

	public function getMainImageAttribute($value)
	{
		return !empty($value) ? Storage::url($value) : '';
	}

	public function getMainProfileAttribute($value)
	{
		return !empty($value) ? Storage::url($value) : '';
	}

	public function getAdImageTopAttribute($value)
	{
		return !empty($value) ? Storage::url($value) : '';
	}

	public function getAdImageMiddleAttribute($value)
	{
		return !empty($value) ? Storage::url($value) : '';
	}

	public function getAdImageBottomAttribute($value)
	{
		return !empty($value) ? Storage::url($value) : '';
	}

    public function card()
    {
    	return $this->belongsTo('App\Card');
    }
}
