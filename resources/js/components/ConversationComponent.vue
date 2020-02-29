<template>
	<div class="form-group">
		<div v-if="typing">You are typing</div>
		<div v-else>{{ user }} is waiting</div>
		<label for="message">Message:</label>
		<textarea v-on:input="isTyping()" name="Message" class="form-control" ></textarea>
	</div>
</template>
<script>
	export default {
		data(){
			return {
				typing: false
			}
		},
		props: [
			'user'
		],
		methods: {
			isTyping: function(){
				let context = this;
				clearTimeout(context.inactive);
				context.inactive = setTimeout(function(){
					context.typing = false;
				}, 1200);
				context.typing = true;
			}
		}
	}
</script>