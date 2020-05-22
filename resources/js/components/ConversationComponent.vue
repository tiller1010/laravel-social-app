<template>
	<div>
		<div v-for="newMessage in feed">
			<div v-if="newMessage.From == user" style="margin-bottom: 40px;" class="alert alert-info receivedMessage">
				<p>{{newMessage.Message}}</p>
			</div>
			<div v-else style="margin-bottom: 40px;" class="alert alert-dark sentMessage">
				<p>{{newMessage}}</p>
			</div>
		</div>
		<div class="py-3 typing-status" v-if="otherTyping">{{ user }} is typing</div>
		<div class="py-3 typing-status" v-else-if="typing">You are typing</div>
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
			<div class="btn btn-success" v-on:click="scrollBottom()">New Messages <svg class="bi bi-arrow-bar-down" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
				<path fill-rule="evenodd" d="M11.354 10.146a.5.5 0 010 .708l-3 3a.5.5 0 01-.708 0l-3-3a.5.5 0 01.708-.708L8 12.793l2.646-2.647a.5.5 0 01.708 0z" clip-rule="evenodd"/>
				<path fill-rule="evenodd" d="M8 6a.5.5 0 01.5.5V13a.5.5 0 01-1 0V6.5A.5.5 0 018 6zM2 3.5a.5.5 0 01.5-.5h11a.5.5 0 010 1h-11a.5.5 0 01-.5-.5z" clip-rule="evenodd"/>
				</svg>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		props: [
			//Name of other user
			'user',
			//That user's id
			'userid',
			//Your user object
			'currentuser',
			'url'
		],
		data(){
			return {
				typing: false,
				otherTyping: false,
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
				}, 600);
				context.typing = true;

			    $.ajax({
			      		url: context.url + "/api/ping-user",
			      		type: "POST",
			      		data: {
		      			pingedUserId: this.userid 
		      		}
		      	});
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
					if(this.newMessagesExist){
						this.newMessagesExist = false;
					}
				}
            },
            listenForActivity() {
            	var context = this;
                Echo.private('message.' + this.currentuser.id)
                    .listen('MessageSent', (e) => {
                        this.feed[Object.keys(this.feed).length] = e.message;
                        this.$forceUpdate();
                        if(e.message.From == this.user){
	                        this.newMessagesExist = true;
			            	if(window.scrollY + 670 >= document.body.offsetHeight){
						      setTimeout(() => {
							    this.scrollBottom();
						      }, 1);
	                        }
	                    }
                    });
				Echo.private('ping-user.' + this.currentuser.id)
					.listen('PingUser', (e) => {
						context.otherTyping = true;
						clearTimeout(context.otherInactive);
						context.otherInactive = setTimeout(function(){
							context.otherTyping = false;
						}, 600);
						context.otherTyping = true;
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