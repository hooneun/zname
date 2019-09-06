<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    //
    protected $fillable = [
        'user_id', 'title',
    ];

    public function getCreatedAtAttribute($value)
    {
		return date('y.m.d H:m', strtotime($value));
    }

	public function getUpdatedAtAttribute($value)
	{
		return date('y.m.d H:m', strtotime($value));
	}

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function details()
    {
    	return $this->hasOne('App\Detail');
    }
}
