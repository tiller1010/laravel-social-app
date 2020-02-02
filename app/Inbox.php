<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;

class Inbox extends Model
{
    public function Messages()
    {
    	return $this->hasMany(Messages::class);
    }

}
