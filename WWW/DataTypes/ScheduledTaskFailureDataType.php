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

	/* Methods */

 	public function SetTimeSolved($Value) {
		$Value = (int)$Value;

		if($this->TimeSolved !== $Value) {
			$Query = new \Framework\Newnorth\DbUpdateQuery();

			$Query->AddSource('ScheduledTaskId');

			$Query->AddChange('`TimeSolved`', $Value);

			$Query->Conditions = new \Framework\Newnorth\DbEqualTo('`Id`', $this->Id);

			$Connection = \Framework\Newnorth\Application::GetDbConnection('Default');

			$Connection->Update($Query);

			$this->TimeSolved = $Value;
		}
	}
}
?>