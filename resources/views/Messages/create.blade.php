@extends('layouts.inbox')

@section('messages')
	<div>
		<form action="/messages" method="POST">
			@csrf
			<label for="message">Message:</label>
			<textarea name="Message"></textarea>
			<input type="submit" value="Update">
		</form>
	</div>
@endsection