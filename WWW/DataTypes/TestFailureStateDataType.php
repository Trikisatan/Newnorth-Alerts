<?
class TestFailureStateDataType extends \Framework\Newnorth\DataType {
	/* Variables */

	public $Id;

	public $TestFailureId;

	public $TestId;

	public $TimeFailed;

	public $PriorityLevel;

	public $Description;

	public $TimeUpdated;

	public $TimeCreated;

	/* Magic methods */

	public function __construct($Data) {
		if(isset($Data['Id'])) {
			$this->Id = (int)$Data['Id'];
		}

		if(isset($Data['TestId'])) {
			$this->TestId = (int)$Data['TestId'];
		}

		if(isset($Data['TestFailureId'])) {
			$this->TestFailureId = (int)$Data['TestFailureId'];
		}

		if(isset($Data['PriorityLevel'])) {
			$this->PriorityLevel = $Data['PriorityLevel'];
		}

		if(isset($Data['Description'])) {
			$this->Description = $Data['Description'];
		}

		if(isset($Data['TimeUpdated'])) {
			$this->TimeUpdated = (int)$Data['TimeUpdated'];
		}

		if(isset($Data['TimeCreated'])) {
			$this->TimeCreated = (int)$Data['TimeCreated'];
		}
	}
}
?>