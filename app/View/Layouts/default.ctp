<!DOCTYPE html>
<html>
<head>
	<title><?=$title_for_layout; ?></title>
	<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
<?php
	echo $this->Html->meta('icon');
	
	$css = array(
		'bootstrap.min', 
		'glyphicons', 
		'style', 
		'extra'
	);
	echo $this->Html->css($css);
	
	$aScripts = array(
		'vendor/jquery/jquery-1.10.2.min',
		'vendor/bootstrap.min',
		'vendor/jquery.nicescroll.min',
		'vendor/tmpl.min',
		'/core/js/json_handler',
		'chat'
	);
	echo $this->Html->script($aScripts);

	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');
?>
</head>
<body>
<script type="text/javascript">
var chatURL = {
	contactList: '<?=$this->Html->url(array('controller' => 'ChatAjax', 'action' => 'contactList'))?>',
	sendMsg: '<?=$this->Html->url(array('controller' => 'ChatAjax', 'action' => 'sendMsg'))?>.json',
	updateState: '<?=$this->Html->url(array('controller' => 'ChatAjax', 'action' => 'updateState'))?>.json',
	openRoom: '<?=$this->Html->url(array('controller' => 'ChatAjax', 'action' => 'openRoom'))?>.json',
	markRead: '<?=$this->Html->url(array('controller' => 'ChatAjax', 'action' => 'markRead'))?>.json',
}
var timer = null;
$(document).ready(function () {
	
	$(window).resize(function() {
		Chat.fixDialogHeight();
	});
	Chat.fixDialogHeight();
	
	$("#menuBarScroll").niceScroll({cursorwidth:"3px",cursorcolor:"#000",cursorborder:"none"});
	$(".dialog").niceScroll({cursorwidth:"5px",cursorcolor:"#999999",cursorborder:"none"});
	$(".sendForm textarea").niceScroll({cursorwidth:"5px",cursorcolor:"#999999",cursorborder:"none", autohidemode: "false"});
	
	Chat.scrollTop();
	
	$(".menuBar .glyphicons:not(.chat)").bind('click', function(event) {
		$(".menuBar div").removeClass("active");
		$(this).closest("div").addClass("active");
	});
	
	$(".menuBar .glyphicons.chat").bind('click', function(event) {
		Chat.toggleContactList();
	});
	
	$(".sendForm .icon_enter").bind('click', function() {
		Chat.sendMsg();
	});
	
	$(".sendForm textarea").bind('keypress', function(event) {
		if ( event.which == 13 ) {
			event.preventDefault();
			Chat.sendMsg();
		}
	});
	
	$(".menuBar .glyphicons.calendar").bind('click', function(event) {
		clearInterval(timer);
		Chat.updateState();
	});
	
	timer = setInterval(function(){
		Chat.updateState();
	}, 500);
});			
</script>

	<?=$this->element('panel')?>
	<div class="userMessages" style="display: none">
		<div class="searchBlock clearfix">
			<input type="text" value="Найти собеседника"  />
			<a href="javascript: void(0)" class="glyphicons search"></a>
		</div>
		<div id="allMessages">
			<?// $this->element('contact_list')?>
		</div>
	</div>
	<div class="usersInChat">
		<a class="icon icon_add" href="javascript: void(0)"></a>
		<a href="javascript: void(0)"><img alt="" src="img/temp/2.jpg"></a>
		<a href="javascript: void(0)"><img alt="" class="active" src="img/temp/3.jpg"></a>
		<a href="javascript: void(0)"><img alt="" src="img/temp/1.jpg"></a>
	</div>
	<div class="bottom">
		<div class="openChats clearfix">
			<?=$this->element('chat_rooms')?>
		</div>
		<?=$this->element('send_message')?>
	</div>
	<div class="dialog clearfix">
		<div class="innerDialog">
	    	<?=$this->fetch('content')?>
	    </div>
	</div>
	<?=$this->element('js_templates')?>
</body>
</html>