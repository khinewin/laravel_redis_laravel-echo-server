@extends('layouts.app')

@section('content')
    <div class="container" id="app">
        <h3 class=" text-center">Messaging</h3>
        <div class="messaging">
            <div class="inbox_msg">
                <div class="inbox_people">
                    <div class="headind_srch">
                        <div class="recent_heading">
                            <h4>Recent</h4>
                        </div>
                        <div class="srch_bar">
                            <div class="stylish-input-group">
                                <input type="text" class="search-bar"  placeholder="Search" >
                                <span class="input-group-addon">
                <button type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                </span> </div>
                        </div>
                    </div>
                    <div class="inbox_chat" >
                        <div v-for="user in users">
                            <div class="chat_list active_chat" @click="activeChat(user.id, user.name)" style="cursor: pointer">
                            <div class="chat_people" >
                                <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                                <div class="chat_ib">
                                    <h5>@{{ user.name }} <span class="chat_date">@{{ getUserTime(user.created_at) }}</span></h5>
                                    <p>Test, which is a new approach to have all solutions
                                        astrology under one roof.</p>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="mesgs">
                    <div v-if="msgShow">
                        <p style="margin-bottom: 10px; margin-top: 0; padding-top: 0; font-weight: bold; border-bottom: 1px solid black">@{{ receiver.name }}</p>
                    <div class="msg_history" v-chat-scroll>

                       <div v-for="msg in messages">
                        <div class="incoming_msg" v-if="msg.sender_id==receiver.id">
                            <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                            <div class="received_msg">
                                <div class="received_withd_msg">
                                    <p>@{{ msg.message }}</p>
                                    <span class="time_date"> @{{ getChatTime(msg.created_at) }}</span></div>
                            </div>
                        </div>
                        <div class="outgoing_msg" v-else>
                            <div class="sent_msg">
                                <p>@{{ msg.message }}</p>
                                <span class="time_date"> @{{ getChatTime(msg.created_at) }}</span> </div>
                        </div>


                    </div>
                    <div class="type_msg">
                        <form>
                        <div class="input_msg_write">
                            <input v-model="message" type="text" class="write_msg" placeholder="Type a message" />
                            <button class="msg_send_btn" type="submit" @click.prevent="sendMsg()"><i class="fas fa-arrow-circle-right"></i></button>
                        </div>
                        </form>
                    </div>
                    </div>
                    </div>
                </div>
            </div>


            <p class="text-center top_spac"> Developed by <a target="_blank" href="#">NTG</a></p>

        </div></div>
@endsection

@section('scripts')
    <script src="http://momentjs.com/downloads/moment.js"></script>
    <script>
        const app=new Vue({
            el :"#app",
            data: {
                users: {},
                sender: {!! Auth::check() ? Auth::User() : null !!},
                receiver : {
                    id: '',
                    name : ''
                },
                msgShow: false,
                message: '',
                messages: {}
            },
            created(){
                //console.log("Vue is working")
                this.getUsers();

            },
            methods: {
                getMessages(){
                  axios.get('/receiver/'+this.receiver.id+'/messages').then(doc=>{
                      this.messages=doc.data;
                  }).catch(err=>{
                      console.log(err)
                  });
                },
                getUsers(){
                    axios.get('/users').then(doc=>{
                       this.users=doc.data;
                    }).catch(err=>{
                        console.log(err)
                    });
                },
                getUserTime(data){
                    return moment(data).format("MMM Do YY");
                },
                activeChat(receiver_id, receiver_name){
                    this.receiver.id=receiver_id;
                    this.receiver.name=receiver_name;
                    this.msgShow=true;
                    this.getMessages();
                    this.listenMsg();
                },
                sendMsg(){
                    axios.post('/messages',{
                        receiver_id:this.receiver.id,
                        message :this.message
                    }).then(doc=>{
                       // console.log(doc)
                        this.messages.push(doc.data);
                        this.message='';
                    }).catch(err=>{
                        console.log(err)
                    })
                },
                listenMsg(){
                    console.log(+this.sender.id+'_'+this.receiver.id)
                    Echo.channel('message_'+this.sender.id+'_'+this.receiver.id).listen('ChatEvent', (msg)=>{
                        this.messages.push(msg.chat)
                    })
                },
                getChatTime(data){
                    return moment(data).fromNow();
                }
            }
        })
    </script>

    @stop
