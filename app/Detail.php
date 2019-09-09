<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Detail extends Model
{
	static public $IMAGE_COLUMNS = [
		'main_image', 'main_profile',
		'ad_image_top', 'ad_image_middle',
		'ad_image_bottom'
	];

	protected $guarded = ['id'];

	public function getMainImageUrlAttribute()
	{
		return !empty($this->main_image) ? Storage::url($this->main_image) : '';
	}

	public function getMainProfileUrlAttribute()
	{
		return !empty($this->main_profile) ? Storage::url($this->main_profile) : '';
	}

	public function getAdImageTopUrlAttribute()
	{
		return !empty($this->ad_image_top) ? Storage::url($this->ad_image_top) : '';
	}

	public function getAdImageMiddleUrlAttribute()
	{
		return !empty($this->ad_image_middle) ? Storage::url($this->ad_image_middle) : '';
	}

	public function getAdImageBottomUrlAttribute()
	{
		return !empty($this->ad_image_bottom) ? Storage::url($this->ad_image_bottom) : '';
	}

	public function card()
	{
		return $this->belongsTo('App\Card');
	}
}
