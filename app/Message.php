<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
	protected $fillable = [
		'Read',
		'From',
		'From_user_id',
		'To_user_id',
		'To',
		'Message'
	];
	/**/
    public function Tasks()
    {
    	return $this->belongsTo(Inbox::class);
    }
}
