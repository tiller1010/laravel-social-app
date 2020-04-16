<div class="position-fixed fixed-bottom">
	<div class="container">
		<button type="button" class="btn btn-primary container" data-toggle="modal" data-target=".bd-example-modal-xl">Create new message</button>
		<div class="modal fade bd-example-modal-xl">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
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
								<textarea name="Message" class="form-control" style="resize: none;"></textarea>
							</div>
							<input type="submit" class="btn btn-primary" value="Send">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>