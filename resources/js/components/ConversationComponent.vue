<template>
	<div>
		<div v-for="newMessage in feed">
			<div v-if="newMessage.From == user" style="margin-bottom: 40px;" class="alert alert-info receivedMessage">
				<p>{{newMessage.Message}}</p>
			</div>
			<div v-else style="margin-bottom: 40px;" class="alert alert-info sentMessage">
				<p>{{newMessage}}</p>
			</div>
		</div>
		<div class="py-3 typing-status" v-if="typing">You are typing</div>
		<div class="py-3 typing-status" v-else-if="present">{{ user }} is waiting</div>
		<div class="py-3 typing-status" v-else="typing">{{ user }} is away</div>
		<div class="d-flex fixed-input">
			<div class="form-group">
				<label for="message">Message:</label>
				<textarea v-on:input="isTyping()" name="Message" class="form-control" style="resize: none;"></textarea>
			</div>
			<input v-if="present" type="hidden" name="read" value="1">
			<input v-else type="hidden" name="read" value="0">
			<!-- If we need to debug form, turns ajax off -->
			<!-- <input action="/messages" type="submit" class="btn btn-primary" value="Send"> -->
			<input v-on:click="submitHandler()" type="button" class="btn btn-primary" value="Send">
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
			'userid',
			'currentuser',
			'url'
		],
		data(){
			return {
				typing: false,
				feed: {},
				newMessagesExist: false,
				present: false
			}
		},
        created() {
            this.joinConversation();
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
		    submitHandler(){
		     const context = this;
		     let message = $('.conversation-form textarea').val();
		     $.ajaxSetup({
		          headers: {
		              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		          }
		      });
		      $.ajax({
		        url: context.url + '/messages',
		        type: "POST",
		        data: $('.conversation-form').serialize()
		      }).done((data) => {
		      		console.log(this.feed)
                    this.feed[Object.keys(this.feed).length] = message;
                    this.$forceUpdate();
				    $('.conversation-form textarea').val("");
		      });
		      setTimeout(() => {
			    this.scrollBottom();
		      }, 1000);
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
                Echo.private('message.' + this.currentuser.id)
                    .listen('MessageSent', (e) => {
                        this.feed[Object.keys(this.feed).length] = e.message;
                        this.$forceUpdate();
                        if(e.message.From == this.user){
	                        this.newMessagesExist = true;
	                    }
                    });

            },
            joinConversation(){
            	const context = this;
                if(this.userid > this.currentuser.id){
                	var greater = this.userid;
                	var lesser = this.currentuser.id;
                } else {
                	var greater = this.currentuser.id;                	
                	var lesser = this.userid;
                }
                Echo.join('presence-user-present.' + greater + '-' + lesser)
	               .here((users) => {
	               		console.log(users)
		               	console.log('you Joined')
		               	if(users.length > 1){
		               		context.present = true;
		               	}
	                })
	               .joining((user) => {
	               		console.log(user.name, ' is joining')
		               		context.present = true;
	               })
	               .leaving((user) => {
	               		console.log(user.name, ' is leaving')
	               		context.present = false;
	               });

            }
		},
		mounted(){
			window.scrollTo(0, document.body.offsetHeight);
		}
	}
</script>