<?
class ScheduledTaskFailureStateDataType extends \Framework\Newnorth\DataType {
	/* Variables */

	public $Id;

	public $ScheduledTaskFailureId;

	public $ScheduledTaskId;

	public $PriorityLevel;

	public $Description;

	public $TimeUpdated;

	public $TimeCreated;

	/* Magic methods */

	public function __construct($Data) {
		if(isset($Data['Id'])) {
			$this->Id = (int)$Data['Id'];
		}

		if(isset($Data['ScheduledTaskFailureId'])) {
			$this->ScheduledTaskFailureId = (int)$Data['ScheduledTaskFailureId'];
		}

		if(isset($Data['ScheduledTaskId'])) {
			$this->ScheduledTaskId = (int)$Data['ScheduledTaskId'];
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