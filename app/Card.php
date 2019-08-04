<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    //
    protected $fillable = [
        'user_id', 'email', 'company_name',
        'contact_address', 'position', 'company_name',
        'address'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
