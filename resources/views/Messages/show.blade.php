@extends('layouts.app')

@section('content')

<h1>Showing Conversation</h1>

<a href="/messages">Go back</a>

<h2>All Messages:</h2>
@foreach($allMessages as $m)
@if($user->can('view', $m))
	@if($sentMessages->contains($m))
	<div style="margin-bottom: 40px;" class="alert alert-dark sentMessage">
	@else
	<div style="margin-bottom: 40px;" class="alert alert-info receivedMessage">
	@endif
		<p>From: {{$m->From}}</p>
		<p>Message: {{$m->Message}}</p>
	</div>
@endif
@endforeach

<form action="/messages" method="POST">
	@csrf
	<conversation-component
		:user="'{{ \App\User::where('id', $connectedUser)->first()->name }}'"
		:currentuser="{{ $user }}"
	></conversation-component>
	<input type="hidden" name="To" value="{{ \App\User::where('id', $connectedUser)->first()->name }}" />
	<input type="submit" class="btn btn-primary" value="Send">
</form>

@endsection
