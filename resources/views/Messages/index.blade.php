@extends('layouts.app')

@section('content')

@if($mostRecentConversationMessages->count() > 0)
	<div class="position-sticky py-1 message-heading">
		<h2>All Messages:</h2>
	</div>
	@foreach($mostRecentConversationMessages as $m)
	@if($user->can('view', $m))
		@if($user->id === $m->From_user_id)
		<a href="messages/{{$m->To_user_id}}" class="index-message alert alert-dark">
			<h4>{{$m->To}}</h4>
		@else
		<a href="messages/{{$m->From_user_id}}" class="index-message alert alert-info">
			<h4>{{$m->From}}</h4>
		@endif
			<p>{{$m->Message}}</p>
			@if($m->Read)
				<p>Read</p>
			@else
				<p>Unread</p>
			@endif
		</a>
	@endif
	@endforeach
@endif

@include('Messages.create')

@endsection
