<?
use \Framework\Newnorth\Application;
use \Framework\Newnorth\DataType;

class ScheduledTaskDataType extends DataType {
	/* Variables */

	public $Id;

	public $PriorityLevel;

	public $Title;

	public $IsExecuting;

	public $PlannedExecutionInterval;

	public $MaxExecutionInterval;

	public $TimeLastExecuted;

	public $IsDisabled;

	/* Magic methods */

	public function __construct($Data) {
		if(isset($Data['Id'])) {
			$this->Id = (int)$Data['Id'];
		}

		if(isset($Data['PriorityLevel'])) {
			$this->PriorityLevel = $Data['PriorityLevel'];
		}

		if(isset($Data['Title'])) {
			$this->Title = $Data['Title'];
		}

		if(isset($Data['IsExecuting'])) {
			$this->IsExecuting = $Data['IsExecuting'] === '1';
		}

		if(isset($Data['TimeFirstExecuted'])) {
			$this->TimeFirstExecuted = (int)$Data['TimeFirstExecuted'];
		}

		if(isset($Data['ExecutionInterval'])) {
			$this->ExecutionInterval = (int)$Data['ExecutionInterval'];
		}

		if(isset($Data['TimeLastExecuted'])) {
			$this->TimeLastExecuted = (int)$Data['TimeLastExecuted'];
		}

		if(isset($Data['IsDisabled'])) {
			$this->IsDisabled = $Data['IsDisabled'] === '1';
		}
	}
}
?>