<script type="text/x-tmpl" id="room-tab">
<div id="roomTab_{%=o.room.ChatRoom.id%}" class="item">
	<span onclick="Chat.activateRoom({%=o.room.ChatRoom.id%})">
		<span class="badge badge-important">{%=o.msg_count%}</span>
		<img class="ava" src="{%=o.user.Avatar.url%}" alt="" />
	</span>
	<div class="remove"><a href="javascript: void(0)" class="glyphicons circle_remove" onclick="Chat.removeRoom({%=o.room.ChatRoom.id%})"></a></div>
	<div class="name" onclick="Chat.activateRoom({%=o.room.ChatRoom.id%})">{%=o.user.ChatUser.name%}</div>
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