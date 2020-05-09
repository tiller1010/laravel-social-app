<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('activity.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('message.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('presence-user-present.{roomID}', function($user, $roomID){
	$memberOne = preg_split('/-/', $roomID)[0];
	$memberTwo = preg_split('/-/', $roomID)[1];
	if((int) $user->id === (int) $memberOne || (int) $user->id === (int) $memberTwo){
		return ['id' => $user->id, 'name' => $user->name];
	}
	return false;
});