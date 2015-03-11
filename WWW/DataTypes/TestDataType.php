<?
use \Framework\Newnorth\Application;
use \Framework\Newnorth\DataType;
use \Framework\Newnorth\DbUpdateQuery;
use \Framework\Newnorth\DbAnd;
use \Framework\Newnorth\DbEqualTo;

class TestDataType extends DataType {
	/* Variables */

	public $Id;

	public $Title;

	public $State;

	public $StateDescription;

	public $StatePriorityLevel;

	public $TimeLastFailed;

	public $IsExecuting;

	public $ExecutionInterval;

	public $TimeLastExecuted;

	public $Url;

	/* Magic methods */

	public function __construct($Data) {
		if(isset($Data['Id'])) {
			$this->Id = (int)$Data['Id'];
		}

		if(isset($Data['Title'])) {
			$this->Title = $Data['Title'];
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

		if(isset($Data['TimeLastFailed'])) {
			$this->TimeLastFailed = (int)$Data['TimeLastFailed'];
		}

		if(isset($Data['IsExecuting'])) {
			$this->IsExecuting = $Data['IsExecuting'] === '1';
		}

		if(isset($Data['ExecutionInterval'])) {
			$this->ExecutionInterval = (int)$Data['ExecutionInterval'];
		}

		if(isset($Data['TimeLastExecuted'])) {
			$this->TimeLastExecuted = (int)$Data['TimeLastExecuted'];
		}

		if(isset($Data['URL'])) {
			$this->Url = $Data['URL'];
		}
	}

	/* Methods */

 	public function SetStateDescription($Value) {
		$Value = (string)$Value;

		$Query = new DbUpdateQuery();

		$Query->AddSource('Test');

		$Query->AddChange('`StateDescription`', '"'.$Value.'"');

		$Query->Conditions = new DbAnd();

		$Query->Conditions->EqualTo('`Id`', $this->Id);

		$Connection = Application::GetDbConnection('Default');

		$Connection->Update($Query);

		$this->StateDescription = $Value;
	}

 	public function SetStatePriorityLevel($Value) {
		$Value = (string)$Value;

		$Query = new DbUpdateQuery();

		$Query->AddSource('Test');

		$Query->AddChange('`StatePriorityLevel`', '"'.$Value.'"');

		$Query->Conditions = new DbAnd();

		$Query->Conditions->EqualTo('`Id`', $this->Id);

		$Connection = Application::GetDbConnection('Default');

		$Connection->Update($Query);

		$this->StatePriorityLevel = $Value;
	}

 	public function SetIsExecuting($Value) {
		$Value = (bool)$Value;

		$Query = new DbUpdateQuery();

		$Query->AddSource('Test');

		$Query->AddChange('`IsExecuting`', $Value);

		$Query->Conditions = new DbAnd();

		$Query->Conditions->EqualTo('`Id`', $this->Id);

		$Connection = Application::GetDbConnection('Default');

		$Connection->Update($Query);

		$this->IsExecuting = $Value;
	}

 	public function SetTimeLastExecuted($Value) {
		$Value = (int)$Value;

		$Query = new DbUpdateQuery();

		$Query->AddSource('Test');

		$Query->AddChange('`TimeLastExecuted`', $Value);

		$Query->Conditions = new DbAnd();

		$Query->Conditions->EqualTo('`Id`', $this->Id);

		$Connection = Application::GetDbConnection('Default');

		$Connection->Update($Query);

		$this->TimeLastExecuted = $Value;
	}
}
?>