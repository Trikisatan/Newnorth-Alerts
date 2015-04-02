<?
namespace Data;

class UnhandledMessagesPage extends \Framework\Newnorth\Page {
	/* Variables */

	public $_Renderer = '\Framework\Newnorth\JsonRenderer';

	public $Data = [];

	/* Life cycle methods */

	public function Initialize() {

	}

	public function Load() {
		$MessageDataManager = $GLOBALS['Application']->GetDataManager('Message');

		$Messages = $MessageDataManager->FindAllActive();

		foreach($Messages as $Message) {
			$this->Data[] = [
				'Id' => $Message->Id,
				'PriorityLevel' => $Message->PriorityLevel,
				'Key' => $Message->Key,
				'Title' => $Message->Title,
				'Text' => $Message->Text,
				'TimeSolved' => $Message->TimeSolved,
				'TimeCreated' => $Message->TimeCreated,
			];
		}
	}

	public function Execute() {

	}
}
?>