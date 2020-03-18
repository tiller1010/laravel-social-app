@extends('layouts.app')

@section('content')

<h2>All Messages:</h2>
@foreach($mostRecentConversationMessages as $m)
@if($user->can('view', $m))
	<div style="margin-bottom: 40px;">
		<p>From: {{$m->From}}</p>
		<p>To: {{$m->To}}</p>
		<p>Message: {{$m->Message}}</p>
		@if($m->Read)
			<p>Read</p>
		@else
			<p>Unread</p>
		@endif
		@if($user->id === $m->From_user_id)
		<a href="messages/{{$m->To_user_id}}">View</a>
		@else
		<a href="messages/{{$m->From_user_id}}">View</a>
		@endif
	</div>
@endif
@endforeach

<a href="/messages/create">Create new message</a>

@endsection
