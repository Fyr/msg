<!DOCTYPE html>
<html>
<head>
	<title><?=$title_for_layout; ?></title>
	<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
<?php
	$baseURL = Configure::read('baseURL');
	
	echo $this->Html->meta('icon');
	
	$css = array(
		'bootstrap.min', 
		'glyphicons', 
		'style', 
		'msg_panel',
		$baseURL['ipad'].'css/struct_panel.css'
	);
	echo $this->Html->css($css);
	
	$aScripts = array(
		'vendor/jquery/jquery-1.10.2.min',
		'vendor/bootstrap.min',
		'vendor/jquery.nicescroll.min',
		'vendor/tmpl.min',
		'/core/js/json_handler',
		'chat',
		$this->Html->url(array('controller' => 'ChatAjax', 'action' => 'jsSettings'), true)
	);
	if (!TEST_ENV) {
		$aScripts[] =$baseURL['ipad'].'SiteAjax/jsSettings';
		$aScripts[] = $baseURL['ipad'].'/js/struct.js';
	}
	echo $this->Html->script($aScripts);

	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');
?>
</head>
<body>
<script type="text/javascript">
var timer = null;
$(document).ready(function () {
	
	$(window).resize(function() {
		$("#menuBarScroll").height($(window).height());
		$("#menuBarScroll").getNiceScroll().resize();
		
		Chat.fixPanelHeight();
		
		// handle resize for iPad panel
		Struct.fixPanelHeight();
	});
	
	
	$("#menuBarScroll").niceScroll({cursorwidth:"3px",cursorcolor:"#000",cursorborder:"none"});
	$("#menuBarScroll").height($(window).height());
	$("#menuBarScroll").getNiceScroll().resize();
		
	$(".dialog").niceScroll({cursorwidth:"5px",cursorcolor:"#999999",cursorborder:"none"});
	$(".sendForm textarea").niceScroll({cursorwidth:"5px",cursorcolor:"#999999",cursorborder:"none", autohidemode: "false"});
	
	Chat.initPanel($(".userMessages.chat").get(0), <?=$userID?>);
	
	$(".menuBar .glyphicons.chat").bind('click', function(event) {
		if ($(".userMessages.ipad").is(':visible')) {
			Struct.panelHide();
		}
		Chat.panelToggle();
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
	/*
	timer = setInterval(function(){
		Chat.updateState();
	}, 5000);
	*/
	
	// Init iPad panel
<?
	if (!TEST_ENV) {
?>
	Struct.initPanel($(".userMessages.struct").get(0));
	$(".menuBar .glyphicons.ipad").bind('click', function(event) {
		if ($(".userMessages.chat").is(':visible')) {
			Chat.panelHide();
		}
		Struct.panelToggle();
	});
<?
	}
?>
});			
</script>

	<div class="menuBar">
		<div id="menuBarScroll">
			<img class="userLogo" src="<?=$currUser['Avatar']['url']?>" alt="<?=$currUser['ChatUser']['name']?>" style="width: 90px;" />
			<div><a href="javascript: void(0)" class="glyphicons calendar"></a></div>
			<div><a href="javascript: void(0)" class="glyphicons notes"></a></div>
			<div><a href="javascript: void(0)" class="glyphicons chat"><span class="badge badge-important"></span></a></div>
			<div><a href="javascript: void(0)" class="glyphicons search"></a></div>
			<div><a href="javascript: void(0)" class="glyphicons credit_card"><span class="badge badge-important"></span></a></div>
			<div><a href="javascript: void(0)" class="glyphicons file"></a></div>
			<div><a href="javascript: void(0)" class="glyphicons cloud"></a></div>
			<div><a href="javascript: void(0)" class="glyphicons nameplate"></a></div>
			<div><a href="javascript: void(0)" class="glyphicons group"></a></div>
			<div><a href="javascript: void(0)" class="glyphicons briefcase"></a></div>
			<div><a href="javascript: void(0)" class="glyphicons ipad"></a></div>
			<div><a href="javascript: void(0)" class="glyphicons settings"></a></div>
			<div><a href="javascript: void(0)" class="glyphicons log_out"></a></div>
		</div>
	</div>

	<div class="userMessages chat" style="display: none"></div>
	<div class="userMessages struct" style="display:none"></div>
	
	<div class="usersInChat">
		<a class="icon icon_add" href="javascript: void(0)"></a>
		<!--
		<a href="javascript: void(0)"><img alt="" src="img/temp/2.jpg"></a>
		<a href="javascript: void(0)"><img alt="" class="active" src="img/temp/3.jpg"></a>
		<a href="javascript: void(0)"><img alt="" src="img/temp/1.jpg"></a>
		-->
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
	<?//$this->element('sql_dump')?>
</body>
</html>