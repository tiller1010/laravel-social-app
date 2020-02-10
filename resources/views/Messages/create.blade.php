@extends('layouts.inbox')

@section('messages')
	<div>
		<form action="/messages" method="POST">
			@csrf
			<div class="form-group">
				<label for="To">To:</label>
				<input type="text" name="To" class="form-control" />
			</div>
			<div class="form-group">
				<label for="message">Message:</label>
				<textarea name="Message" class="form-control" ></textarea>
			</div>
			<input type="submit" value="Send">
		</form>
	</div>
@endsection