<?
class MessageHandledPage extends \Framework\Newnorth\Page {
	/* Variables */

	public $_Renderer = '\Framework\Newnorth\JsonRenderer';

	public $Data = null;

	/* Life cycle methods */

	public function Initialize() {
		
	}

	public function Load() {
 		$MessageDataManager = $GLOBALS['Application']->GetDataManager('Message');

		$HandledMessages = $MessageDataManager->FindAllHandled();

		foreach($HandledMessages as $HandledMessage) {
			$this->Data[] = [
				'Id' => $HandledMessage->Id,
				'PriorityLevel' => $HandledMessage->PriorityLevel,
				'Key' => $HandledMessage->Key,
				'Title' => $HandledMessage->Title,
				'Text' => $HandledMessage->Text,
				'TimeSolved' => $HandledMessage->TimeSolved,
				'TimeCreated' => $HandledMessage->TimeCreated,
			];
		}
	}

	public function Execute() {
		
	}
}
?>