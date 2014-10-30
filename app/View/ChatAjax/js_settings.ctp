<?
	App::uses('ChatEvent', 'Model');
?>
var chatURL = {
	panel: '<?=$this->Html->url(array('controller' => 'ChatAjax', 'action' => 'panel'), true)?>',
	contactList: '<?=$this->Html->url(array('controller' => 'ChatAjax', 'action' => 'contactList'), true)?>',
	sendMsg: '<?=$this->Html->url(array('controller' => 'ChatAjax', 'action' => 'sendMsg'), true)?>.json',
	sendFile: '<?=$this->Html->url(array('controller' => 'ChatAjax', 'action' => 'sendFile'), true)?>.json',
	updateState: '<?=$this->Html->url(array('controller' => 'ChatAjax', 'action' => 'updateState'), true)?>.json',
	openRoom: '<?=$this->Html->url(array('controller' => 'ChatAjax', 'action' => 'openRoom'), true)?>.json',
	markRead: '<?=$this->Html->url(array('controller' => 'ChatAjax', 'action' => 'markRead'), true)?>.json',
}
chatUpdateTime = <?=Configure::read('chatUpdateTime')?>;
chatDef = {
	incomingMsg: <?=ChatEvent::INCOMING_MSG?>,
	fileDownloadAvail: <?=ChatEvent::FILE_DOWNLOAD_AVAIL?>
}
chatLocale = {
	Loading: '<?=__('Loading...')?>',
	fileReceived: '<?=__('You have received a file')?>',
	fileUploaded: '<?=__('File has been uploaded')?>'
}
var mediaURL = {
	upload: '<?=$this->Html->url(array('plugin' => 'media', 'controller' => 'ajax', 'action' => 'upload'))?>',
	move: '<?=$this->Html->url(array('plugin' => 'media', 'controller' => 'ajax', 'action' => 'move'))?>.json',
};