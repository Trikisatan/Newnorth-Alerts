<?
use \Framework\Newnorth\Application;
use \Framework\Newnorth\DataType;

class MessageDataType extends DataType {
	/* Variables */

	public $Id;

	public $PriorityLevel;

	public $Key;

	public $Title;

	public $Text;

	public $TimeSolved;

	public $TimeCreated;

	/* Magic methods */

	public function __construct($Data) {
		if(isset($Data['Id'])) {
			$this->Id = (int)$Data['Id'];
		}

		if(isset($Data['PriorityLevel'])) {
			$this->PriorityLevel = (int)$Data['PriorityLevel'];
		}

		if(isset($Data['Key'])) {
			$this->Key = $Data['Key'];
		}

		if(isset($Data['Title'])) {
			$this->Title = $Data['Title'];
		}

		if(isset($Data['Text'])) {
			$this->Text = $Data['Text'];
		}

		if(isset($Data['TimeSolved'])) {
			$this->TimeSolved = (int)$Data['TimeSolved'];
		}

		if(isset($Data['TimeCreated'])) {
			$this->TimeCreated = (int)$Data['TimeCreated'];
		}
	}
}
?>