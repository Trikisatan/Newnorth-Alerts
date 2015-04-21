<?
class ScheduledTaskFailureDataType extends \Framework\Newnorth\DataType {
	/* Variables */

	public $Id;

	public $ScheduledTaskId;

	public $TimeFailed;

	public $TimeSolved;

	/* Magic methods */

	public function __construct($Data) {
		if(isset($Data['Id'])) {
			$this->Id = (int)$Data['Id'];
		}

		if(isset($Data['ScheduledTaskId'])) {
			$this->ScheduledTaskId = (int)$Data['ScheduledTaskId'];
		}

		if(isset($Data['TimeFailed'])) {
			$this->TimeFailed = (int)$Data['TimeFailed'];
		}

		if(isset($Data['TimeSolved'])) {
			$this->TimeSolved = (int)$Data['TimeSolved'];
		}
	}
}
?>