@extends('layouts.inbox')

@section('messages')

<conversation-component
	:user="'{{ $user->name }}'"
></conversation-component>

<h2>Received Messages:</h2>
@foreach($recievedMessages as $m)
@if($user->can('view', $m))
	<div style="margin-bottom: 40px;">
		<p>From: {{$m->From}}</p>
		<p>Message: {{$m->Message}}</p>
		@if($m->Read)
			<p>Read</p>
		@else
			<p>Unread</p>
		@endif
		<a href="messages/{{$m->id}}/edit">Edit</a>
	</div>
@endif
@endforeach

<h2>Sent Messages:</h2>
@foreach($sentMessages as $m)
@if($user->can('view', $m))
	<div style="margin-bottom: 40px;">
		<p>From: {{$m->From}}</p>
		<p>Message: {{$m->Message}}</p>
		@if($m->Read)
			<p>Read</p>
		@else
			<p>Unread</p>
		@endif
		<a href="messages/{{$m->id}}/edit">Edit</a>
	</div>
@endif
@endforeach

<a href="/messages/create">Create new message</a>

@endsection
