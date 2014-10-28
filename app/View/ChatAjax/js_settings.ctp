var chatURL = {
	panel: '<?=$this->Html->url(array('controller' => 'ChatAjax', 'action' => 'panel'), true)?>',
	contactList: '<?=$this->Html->url(array('controller' => 'ChatAjax', 'action' => 'contactList'), true)?>',
	sendMsg: '<?=$this->Html->url(array('controller' => 'ChatAjax', 'action' => 'sendMsg'), true)?>.json',
	updateState: '<?=$this->Html->url(array('controller' => 'ChatAjax', 'action' => 'updateState'), true)?>.json',
	openRoom: '<?=$this->Html->url(array('controller' => 'ChatAjax', 'action' => 'openRoom'), true)?>.json',
	markRead: '<?=$this->Html->url(array('controller' => 'ChatAjax', 'action' => 'markRead'), true)?>.json',
}