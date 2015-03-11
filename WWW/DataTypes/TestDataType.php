<?
use \Framework\Newnorth\Application;
use \Framework\Newnorth\DataType;

class TestDataType extends DataType {
	/* Variables */

	public $Id;

	public $Title;

	public $TimeLastFailed;

	public $State;

	public $StateDescription;

	public $StatePriorityLevel;

	public $TimeLastExecuted;

	public $ExecutionInterval;

	public $IsExecuting;

	public $Url;

	/* Magic methods */

	public function __construct($Data) {
		if(isset($Data['Id'])) {
			$this->Id = (int)$Data['Id'];
		}

		if(isset($Data['Title'])) {
			$this->Title = $Data['Title'];
		}

		if(isset($Data['TimeLastFailed'])) {
			$this->TimeLastFailed = (int)$Data['TimeLastFailed'];
		}

		if(isset($Data['State'])) {
			$this->State = $Data['State'];
		}

		if(isset($Data['StateDescription'])) {
			$this->StateDescription = $Data['StateDescription'];
		}

		if(isset($Data['StatePriorityLevel'])) {
			$this->StatePriorityLevel = $Data['StatePriorityLevel'];
		}

		if(isset($Data['TimeLastExecuted'])) {
			$this->TimeLastExecuted = (int)$Data['TimeLastExecuted'];
		}

		if(isset($Data['ExecutionInterval'])) {
			$this->ExecutionInterval = (int)$Data['ExecutionInterval'];
		}

		if(isset($Data['IsExecuting'])) {
			$this->IsExecuting = $Data['IsExecuting'] === '1';
		}

		if(isset($Data['URL'])) {
			$this->Url = $Data['URL'];
		}
	}
}
?>