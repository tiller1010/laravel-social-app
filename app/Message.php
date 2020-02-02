<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
	protected $fillable = [
		'read',
		'From'
	];
	/**/
    public function Tasks()
    {
    	return $this->belongsTo(Inbox::class);
    }
}
