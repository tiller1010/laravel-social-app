@extends('layouts.app')

@section('content')

<h1>Showing Conversation</h1>

<a href="/messages">Go back</a>

{{-- <h2>Received Messages:</h2> --}}
{{-- @foreach($recievedMessages as $m)
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
@endforeach --}}

<h2>All Messages:</h2>
@foreach($allMessages as $m)
@if($user->can('view', $m))
	@if($sentMessages->contains($m))
	<div style="margin-bottom: 40px; margin-left: 40px;">
	@else
	<div style="margin-bottom: 40px;">
	@endif
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

<form action="/messages" method="POST">
	@csrf
	<conversation-component
		:user="'{{ \App\User::where('id', $connectedUser)->first()->name }}'"
	></conversation-component>
	<input type="hidden" name="To" value="{{ \App\User::where('id', $connectedUser)->first()->name }}" />
	<input type="submit" value="Send">
</form>

@endsection
