<?php

namespace App\Transformers;

use App\Activity;
use League\Fractal\TransformerAbstract;

class ActivityTransformer extends TransformerAbstract
{
	public function transform(Activity $activity)
	{
		return [
			"description" => call_user_func_array([$this, $activity->name], [$activity]),
			"lapse" => $activity->created_at->diffForHumans(),
			"user" => $activity->user,
		];
	}

	protected function created_message(Activity $activity)
	{
		return \App\User::where('id', $activity->user_id)->first()->name . " created a message";
	}

	protected function updated_message(Activity $activity)
	{
		return \App\User::where('id', $activity->user_id)->first()->name . " updated a message";
	}
}