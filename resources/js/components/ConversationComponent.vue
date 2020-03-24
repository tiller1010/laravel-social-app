<template>
	<div class="form-group">
		<div v-if="typing">You are typing</div>
		<div v-else>{{ user }} is waiting</div>
		<label for="message">Message:</label>
		<textarea v-on:input="isTyping()" name="Message" class="form-control" ></textarea>

		<ul class="list-group">
            <li class="list-group-item" v-for="item in feed">
                {{ item }}  
            </li>
        </ul>
	</div>
</template>
<script>
	export default {
		props: [
			'user',
			'currentuser'
		],
		data(){
			return {
				typing: false,
				feed: {}
			}
		},
        created() {
            this.getFeed();
            this.listenForActivity();
        },
		methods: {
			isTyping: function(){
				let context = this;
				clearTimeout(context.inactive);
				context.inactive = setTimeout(function(){
					context.typing = false;
				}, 1200);
				context.typing = true;
			},
			getFeed() {
                let context = this;
                return axios.get('/api/activities?api_token=bec3540e3e7e40469c36', {})
                .then(function(response) {
                	// console.log('getFeed response', response)
                    context.feed = response.data.data;
                });
            },
            updateFeed(messageID) {
				// axios.post('/api/messages/'+ messageID +'/comments/store?api_token=bec3540e3e7e40469c36', {
				//     message: this.message
				// }).
				//  then((message) => {
    //                 console.log(message);
    //                 this.messages.push(message.data);
    //                 this.message = '';
    //             });
            },
            listenForActivity() {
                Echo.private('activity.' + this.currentuser.id)
                    .listen('ActivityLogged', (e) => {
                        this.feed[Object.keys(this.feed).length] = e.data;
                    });
            }
		},
		mounted(){
			window.scrollTo(0, document.body.offsetHeight);
		}
	}
</script>