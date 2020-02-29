@extends('layouts.app')

@section('content')
	<div>
		{{$message->From}}
		<form action="/messages/{{$message->id}}" method="POST">
			@method('PATCH')
			@csrf
			<label for="read">Read?</label>
			<input type="checkbox" name="read" {{$message->Read ? 'checked' : ''}} value="1">
			<input type="submit" value="Update">
		</form>
	</div>
@endsection