@extends('layouts.app')

@section('content')

<div class="position-sticky py-1 message-heading">
	<h2>All Messages:</h2>
</div>
@foreach($mostRecentConversationMessages as $m)
@if($user->can('view', $m))
	@if($m->From === auth()->user()->name)
	<div style="margin-bottom: 40px;" class="alert alert-dark">
		<h4>{{$m->To}}</h4>
	@else
	<div style="margin-bottom: 40px;" class="alert alert-info">
		<h4>{{$m->From}}</h4>
	@endif
		<p>{{$m->Message}}</p>
		@if($m->Read)
			<p>Read</p>
		@else
			<p>Unread</p>
		@endif
		@if($user->id === $m->From_user_id)
		<a class="btn btn-primary" href="messages/{{$m->To_user_id}}">View</a>
		@else
		<a class="btn btn-primary" href="messages/{{$m->From_user_id}}">View</a>
		@endif
	</div>
@endif
@endforeach

@include('Messages.create')

@endsection
