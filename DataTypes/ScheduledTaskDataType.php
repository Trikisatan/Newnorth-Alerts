<?
use \Framework\Newnorth\Application;
use \Framework\Newnorth\DataType;

class ScheduledTaskDataType extends DataType {
	/* Variables */

	public $Id;

	public $PriorityLevel;

	public $Title;

	public $PlannedInterval;

	public $MaxInterval;

	public $TimeExecuted;

	/* Magic methods */

	public function __construct($Data) {
		if(isset($Data['Id'])) {
			$this->Id = (int)$Data['Id'];
		}

		if(isset($Data['PriorityLevel'])) {
			$this->PriorityLevel = (int)$Data['PriorityLevel'];
		}

		if(isset($Data['Title'])) {
			$this->Title = $Data['Title'];
		}

		if(isset($Data['PlannedInterval'])) {
			$this->PlannedInterval = (int)$Data['PlannedInterval'];
		}

		if(isset($Data['MaxInterval'])) {
			$this->MaxInterval = (int)$Data['MaxInterval'];
		}

		if(isset($Data['TimeExecuted'])) {
			$this->TimeExecuted = (int)$Data['TimeExecuted'];
		}
	}
}
?>