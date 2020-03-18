@extends('layouts.app')

@section('content')
	<div>
		<form action="/messages" method="POST">
			@csrf
			<div class="form-group">
				<label for="To">To:</label>
				<select name="To" class="form-control">
					<option>Select a user</option>
				@foreach(\App\User::get()->pluck('name') as $name)					
					<option>{{ $name }}</option>
				@endforeach
				</select>
			</div>
			<div class="form-group">
				<label for="message">Message:</label>
				<textarea name="Message" class="form-control" ></textarea>
			</div>
			<input type="submit" value="Send">
		</form>
	</div>
@endsection