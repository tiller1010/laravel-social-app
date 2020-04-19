<template>
	<div>
		<div v-for="newMessage in feed">
			<div v-if="newMessage.From == user" style="margin-bottom: 40px;" class="alert alert-info receivedMessage">
				<p>{{newMessage.Message}}</p>
			</div>
		</div>
		<div class="py-3" style="color: #fff;" v-if="typing">You are typing</div>
		<div class="py-3" style="color: #fff;" v-else>{{ user }} is waiting</div>
		<div class="d-flex fixed-input">
			<div class="form-group">
				<label for="message">Message:</label>
				<textarea v-on:input="isTyping()" name="Message" class="form-control" style="resize: none;"></textarea>
			</div>
			<input type="submit" class="btn btn-primary" value="Send">
		</div>
		<div v-on:scroll="checkScroll()" v-if="newMessagesExist" class="newMessagesButton">
			<div class="btn btn-success" v-on:click="scrollBottom()">New Messages</div>
		</div>
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
				feed: {},
				newMessagesExist: false
			}
		},
        created() {
            this.getFeed();
            this.listenForActivity();
            window.addEventListener('scroll', this.checkScroll);
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
                // let context = this;
                // return axios.get('/api/activities?api_token=bec3540e3e7e40469c36', {})
                // .then(function(response) {
                // 	// console.log('getFeed response', response)
                //     context.feed = response.data.data;
                // });
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
            scrollBottom(){
				window.scrollTo(0, document.body.offsetHeight);
				this.newMessagesExist = false;
            },
            checkScroll(){
            	if(window.scrollY + 670 >= document.body.offsetHeight){
					this.newMessagesExist = false;
				}
            },
            listenForActivity() {
                // Echo.private('activity.' + this.currentuser.id)
                //     .listen('ActivityLogged', (e) => {
                //         this.feed[Object.keys(this.feed).length] = e.data;
                //     });
                Echo.private('message.' + this.currentuser.id)
                    .listen('MessageSent', (e) => {
                        this.feed[Object.keys(this.feed).length] = e.message;
                        this.$forceUpdate();
                        if(e.message.From == this.user){
	                        this.newMessagesExist = true;
	                    }
                    });
            }
		},
		mounted(){
			window.scrollTo(0, document.body.offsetHeight);
		}
	}
</script>