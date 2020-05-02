@extends('layouts.app')

@section('content')

<div class="back-button position-fixed ">
	<div class="bg-primary px-3 py-1">
		<h3 class="text-white my-0">{{ \App\User::where('id', $connectedUser)->first()->name }}</h3>
	</div>
	<a href="/messages"  class="btn btn-danger">
		<svg class="bi bi-arrow-left-short" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
			<path fill-rule="evenodd" d="M7.854 4.646a.5.5 0 010 .708L5.207 8l2.647 2.646a.5.5 0 01-.708.708l-3-3a.5.5 0 010-.708l3-3a.5.5 0 01.708 0z" clip-rule="evenodd"/>
			<path fill-rule="evenodd" d="M4.5 8a.5.5 0 01.5-.5h6.5a.5.5 0 010 1H5a.5.5 0 01-.5-.5z" clip-rule="evenodd"/>
		</svg>
		<span class="text-white">Go back</span>
	</a>
</div>

<div class="container">
	<div class="p-5"></div>
</div>

<div class="conversation">
	@foreach($allMessages as $m)
	@if($user->can('view', $m))
		@if($sentMessages->contains($m))
		<div style="margin-bottom: 40px;" class="alert alert-dark sentMessage">
		@else
		<div style="margin-bottom: 40px;" class="alert alert-info receivedMessage">
		@endif
			<p>{{$m->Message}}</p>
		</div>
	@endif
	@endforeach
</div>

<form class="conversation-form" action="/messages" method="POST">
	@csrf
	<conversation-component
		:user="'{{ \App\User::where('id', $connectedUser)->first()->name }}'"
		:currentuser="{{ $user }}"
	></conversation-component>
	<input type="hidden" name="To" value="{{ \App\User::where('id', $connectedUser)->first()->name }}" />
</form>

<div style="padding: 40px"></div>

@endsection
