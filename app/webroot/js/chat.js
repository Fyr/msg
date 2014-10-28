var Chat = {
	
	INCOMING_MSG: 2,
	enableUpdate: true,
	timer: null,
	panel: null,
	
	zformat: function (n) {
		return (n < 10) ? '0' + n : n;
	},
	
	getCurrTime: function () {
		var date = new Date();
		return date.getHours() + ':' + Chat.zformat(date.getMinutes());
	},
	
	fixPanelHeight: function () {
		messagesHeight = $(window).height() - 82;
		$(".allMessages", Chat.panel).height(messagesHeight);
		$(".allMessages", Chat.panel).getNiceScroll().resize();
		
		var dialogHeight = $(window).height() - $(".bottom").height();
		$(".dialog").height(dialogHeight);
		$(".dialog").getNiceScroll().resize();
	},
	
	scrollTop: function () {
		$(".dialog").scrollTop($(".innerDialog").height() - $(".dialog").height() + 97);
	},
	
	initPanel: function (container) {
		Chat.panel = container;
		$(Chat.panel).load(chatURL.panel, null, function(){
			$(".allMessages", Chat.panel).niceScroll({cursorwidth:"5px",cursorcolor:"#999999",cursorborder:"none"});
			Chat.fixPanelHeight();
			Chat.timer = setInterval(function(){
				Chat.updateState();
			}, 5000);
		});
	},
	
	panelShow: function () {
		$(Chat.panel).show();
		$(".allMessages", Chat.panel).getNiceScroll().show();
		$(".menuBar div").removeClass("active");
		$(".menuBar .glyphicons.chat").parent().addClass("active");
	},
	
	panelHide: function () {
		$(Chat.panel).hide();
		$(".allMessages", Chat.panel).getNiceScroll().hide();
		$(".menuBar .glyphicons.chat").parent().removeClass("active");
	},
	
	panelToggle: function () {
		if ($(Chat.panel).is(':visible')) {
			Chat.panelHide();
		} else {
			$(".allMessages", Chat.panel).load(chatURL.contactList, null, function(){
				Chat.panelShow();
			});
		}
		
	},
	
	sendMsg: function () {
		var msg = $(".sendForm textarea").val();
		Chat.addMsg(msg);
		$(".sendForm textarea").val('');
		$.post(chatURL.sendMsg, {data: {msg: msg, roomID: Chat.getActiveRoom()}}, function(response){
			if (checkJson(response)) {
				
			}
		}, 'json');
	},
	
	renderMsg: function (msg, user, time) {
		return tmpl('chat-msg', {msg: msg, user: user, time: time});
	},
	
	addMsg: function (msg, user, time, roomID) {
		if (!time) {
			time = Chat.getCurrTime();
		}
		if (!roomID) {
			roomID = Chat.getActiveRoom();
		}
		$(".dialog .innerDialog #roomChat_" + roomID).append(Chat.renderMsg(msg, user, time));
		Chat.scrollTop();
	},
	
	openRoom: function (userID) {
		Chat.panelHide();
		Chat.enableUpdate = false;
		$.post(chatURL.openRoom, {data: {user_id: userID}}, function(response){
			Chat.enableUpdate = true;
			if (checkJson(response)) {
				roomID = response.data.room.ChatRoom.id;
				if (!$(".openChats #roomTab_" + roomID).length) { 
					Chat.createRoomTab(response.data);
				}
				if (!$(".dialog .innerDialog #roomChat_" + roomID).length) { 
					$(".dialog .innerDialog").append(Chat.renderRoomChat(roomID));
				}
				Chat.dispatchEvents(response.data);
				Chat.activateRoom(roomID);
			}
		}, 'json');
	},
	
	createRoomTab: function (data) {
		$(".openChats").append(Chat.renderRoomTab(data));
		Chat.clearUnreadEvents(data.room.ChatRoom.id);
	},
	
	removeRoom: function (roomID) {
		var $nextRoom, _roomID = 0;
		if (($nextRoom = $(".openChats #roomTab_" + roomID).next()) && $nextRoom.length) {
			_roomID = $nextRoom.prop('id').replace(/roomTab_/, '');
		} else if (($nextRoom = $(".openChats #roomTab_" + roomID).prev()) && $nextRoom.length) {
			_roomID = $nextRoom.prop('id').replace(/roomTab_/, '');
		}
		
		$(".openChats #roomTab_" + roomID).remove();
		if (_roomID) {
			Chat.activateRoom(_roomID);
		} else {
			$(".openChats .item").removeClass('active');
			$(".dialog .innerDialog .chatRoom").hide();
			$(".sendForm").hide();
		}
	},
	
	renderEvents: function (roomID, aEvents) {
		aID = new Array();
		for(var i = 0; i < aEvents.length; i++) {
			var event = aEvents[i];
			Chat.addMsg(event.msg, event.user, event.time, roomID);
			aID.push(event.id);
		}
		return aID;
	},
	
	activateRoom: function (roomID) {
		$(".openChats .item").removeClass('active');
		$(".openChats #roomTab_" + roomID).addClass('active');
		$(".sendForm").show();
		
		$(".dialog .innerDialog .chatRoom").hide();
		$(".dialog .innerDialog #roomChat_" + roomID).show();
		
		var aUnreadEvents = Chat.getUnreadEvents(roomID);
		if (aUnreadEvents.length) {
			Chat.clearUnreadEvents(roomID);
			Chat.showUnreadTab(roomID);
			Chat.showUnreadTotal(Chat.countUnreadTotal());
			var readIds = Chat.renderEvents(roomID, aUnreadEvents);
			Chat.enableUpdate = false;
			$.post(chatURL.markRead, {data: {ids: readIds}}, function(response){
				Chat.enableUpdate = true;
				if (checkJson(response)) {
				}
			}, 'json');
		}
		
		Chat.fixPanelHeight();
		// Chat.scrollTop();
	},
	
	renderRoomTab: function (data) {
		return tmpl('room-tab', data);
	},
	
	renderRoomChat: function (roomID) {
		return tmpl('room-chat', {room_id: roomID});
	},
	
	getActiveRoom: function () {
		if ($(".openChats .item.active").length) {
			return $(".openChats .item.active").prop('id').replace(/roomTab_/, '');
		}
		return false;
	},
	
	updateState: function () {
		if (Chat.enableUpdate) {
			Chat.enableUpdate = false;
			$.get(chatURL.updateState, null, function(response){
				Chat.enableUpdate = true;
				if (checkJson(response)) {
					Chat.dispatchEvents(response.data);
					if (roomID = Chat.getActiveRoom()) {
						Chat.activateRoom(roomID);
					}
				}
			}, 'json');
		}
	},

	dispatchEvents: function (data) {
		$(".openChats .item").each(function(){
			var roomID = this.id.replace(/roomTab_/, '');
			Chat.clearUnreadEvents(roomID);
		});
		
		for(var i = 0; i < data.events.length; i++) {
			var event = data.events[i].ChatEvent;
			$roomTab = $(".openChats #roomTab_" + event.room_id);
			
			if ($roomTab.length) {
				var msg = data.messages[event.msg_id];
				Chat.addUnreadEvent(event.room_id, {
					id: event.id,
					event_type: event.event_type,
					msg: msg.message,
					time: event.created,
					user: data.authors[msg.user_id]
				});
			}
		}
		$(".openChats .item").each(function(){
			var roomID = this.id.replace(/roomTab_/, '');
			Chat.showUnreadTab(roomID);
		});
		Chat.showUnreadTotal(data.events.length);
	},
	
	addUnreadEvent: function (roomID, event) {
		var $roomTab = $(".openChats #roomTab_" + roomID);
		var aEvents = $roomTab.data('unread');
		aEvents.push(event);
		$roomTab.data('unread', aEvents);
		return Chat.getUnreadEvents(roomID);
	},
	
	clearUnreadEvents: function (roomID) {
		var $roomTab = $(".openChats #roomTab_" + roomID);
		$roomTab.data('unread', new Array());
	},
	
	getUnreadEvents: function (roomID) {
		return $(".openChats #roomTab_" + roomID).data('unread');
	},
	
	showUnreadTab: function (roomID) {
		count = Chat.getUnreadEvents(roomID).length;
		if (count > 10) {
			count = '10+';
		} else if (!count) {
			count = '';
		}
		$(".openChats #roomTab_" + roomID + " span.badge").html(count);
	},
	
	countUnreadTotal: function () {
		var count = 0;
		$(".openChats .item").each(function(){
			count+= Chat.getUnreadEvents(this.id.replace(/roomTab_/, '')).length;
		});
		return count;
	},
	
	showUnreadTotal: function (count) {
		if (count > 10) {
			count = '10+';
		} else if (!count) {
			count = '';
		}
		$(".glyphicons.chat > span.badge").html(count);
	}
}