<script type="text/x-tmpl" id="room-tab">
<div id="roomTab_{%=o.room.ChatRoom.id%}" class="item" onclick="var e = arguments[0] || window.event; if ($(e.target).hasClass('circle_remove') ) { Chat.removeRoom({%=o.room.ChatRoom.id%}); } else { Chat.activateRoom({%=o.room.ChatRoom.id%}); }">
	<span class="badge badge-important">{%=o.msg_count%}</span>
	<img class="ava" src="{%=o.user.Avatar.url%}" alt="{%=o.user.ChatUser.name%}" />
	<div class="remove"><a class="glyphicons circle_remove" href="javascript: void(0)"></a></div>
	<div class="name">{%=o.user.ChatUser.name%}</div>
</div>
</script>

<script type="text/x-tmpl" id="room-chat">
<div id="roomChat_{%=o.room_id%}" class="chatRoom"></div>
</script>

<script type="text/x-tmpl" id="chat-msg">
<div class="{%=((o.user) ? 'leftMessage' : 'rightMessage')%} clearfix">
{% if (o.user) { %}
	<img class="ava" src="{%=o.user.Avatar.url%}" alt="" />
{% } %}
	<div class="time">{%=o.time%}</div>
	<div class="text">{%=o.msg%}</div>
</div>
<div class="clearfix"></div>
</script>

<!-- script type="text/x-tmpl" id="panel-item">
<div class="userItem clearfix" onclick="Chat.openRoom({%=o.ChatUser.id%})">
	<a href="javascript: void(0)"><img class="ava" src="{%=o.Avatar.url%}" alt="{%=o.ChatUser.name%}" /></a>
	<div class="topName">
		<span class="name">{%=o.ChatUser.name%}</span>
		<span class="time">{%=o.ChatEvent.name%}</span>
	</div>
	<div class="topName">
		<span class="message">{%=o.ChatMessage.message%}</span>
		<span class="badge badge-important">{%=o.ChatMessage.count%}</span>
	</div>
</div>
</script-->