<template>
	<div>
		<p>
			<span v-if="typing">{{ user }} is typing</span>
			<span v-else>Away</span>
		</p>
		<input v-on:input="isTyping()" type="text" name="message">
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
				setTimeout(function(){
					context.typing = false;
				}, 1200);
				context.typing = true;
			}
		}
	}
</script>