@extends('layouts.inbox')

@section('messages')
	<div>
		<form action="/messages" method="POST">
			@csrf
			<label for="To">To:</label>
			<input type="text" name="To" />
			<label for="message">Message:</label>
			<textarea name="Message"></textarea>
			<input type="submit" value="Send">
		</form>
	</div>
@endsection