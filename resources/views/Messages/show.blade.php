@extends('layouts.inbox')

@section('messages')

<h1>Showing Conversation</h1>

<a href="/messages">Go back</a>
<p>{{$connectedUser}}</p>

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
	</div>
@endif
@endforeach


<conversation-component
	:user="'{{ $user->name }}'"
></conversation-component>

@endsection
