<?
	foreach($aUsers as $user) {
		$name = Hash::get($user, 'ChatUser.name');
		
		$time = Hash::get($user, 'ChatEvent.created');
		if ($time) {
			$time = date('H:i', strtotime($time));
		}
		
		$count = Hash::get($user, 'ChatEvent.count');
		if ($count > 10) {
			$count = '10+';
		}
		if ($this->request->data('type') == 'external') {
			$onclick = "window.location.href='".$this->Html->url(array('controller' => 'Chat', 'action' => 'index', $user['ChatUser']['id']), true)."'";
		} else {
			$onclick = "Chat.openRoom(".$user['ChatUser']['id'].")";
		}
?>
<div class="userItem clearfix" onclick="<?=$onclick?>">
	<a href="javascript: void(0)"><img class="ava" src="<?=$user['Avatar']['url']?>" alt="<?=$name?>" /></a>
	<div class="topName">
		<span class="name"><?=$name?></span>
		<span class="time"><?=$time?></span>
	</div>
	<div class="topName">
		<span class="message"><?=Hash::get($user, 'ChatMessage.message')?></span>
		<span class="badge badge-important"><?=$count?></span>
	</div>
</div>
<?
	}
?>