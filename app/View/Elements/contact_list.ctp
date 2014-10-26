<?
	foreach($aUsers as $user) {
?>
<div class="userItem clearfix" onclick="Chat.openRoom(<?=$user['ChatUser']['id']?>)">
	<a href="javascript: void(0)"><img class="ava" src="<?=$user['Avatar']['url']?>" alt="" /></a>
	<div class="topName">
		<span class="name"><?=$user['ChatUser']['name']?></span>
		<!--span class="time">21:19</span-->
	</div>
	<div class="topName">
		<!--
		<span class="message">Ребята подсказали</span>
		<span class="badge badge-important">3</span>
		-->
	</div>
</div>
<?
	}
?>