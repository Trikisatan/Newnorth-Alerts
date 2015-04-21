<?
class ScheduledTaskFailureDataManager extends \Framework\Newnorth\DataManager {
	/* Magic methods */

	public function __construct() {
		$this->Connection = \Framework\Newnorth\Application::GetDbConnection('Default');
	}

	/* Methods */

	public function Insert($ScheduledTaskId) {
		$Query = new \Framework\Newnorth\DbInsertQuery();

		$Query->Source = 'ScheduledTaskFailure';

		$Query->AddColumn('`ScheduledTaskId`');
		$Query->AddColumn('`TimeFailed`');

		$Query->AddValue($ScheduledTaskId);
		$Query->AddValue(time());

		$Id = $this->_Insert($Query);

		if($Id === false) {
			return null;
		}

		return $this->FindById($Id);
	}

	public function FindById($Id) {
		$Query = new \Framework\Newnorth\DbSelectQuery();

		$Query->AddSource('ScheduledTaskFailure');

		$Query->Conditions = new \Framework\Newnorth\DbEqualTo('`Id`', (int)$Id);

		return $this->_Find($Query);
	}

	public function FindUnsolvedByScheduledTaskId($ScheduledTaskId) {
		$Query = new \Framework\Newnorth\DbSelectQuery();

		$Query->AddSource('ScheduledTaskFailure');

		$Query->Conditions = new \Framework\Newnorth\DbAnd();

		$Query->Conditions->EqualTo('`ScheduledTaskId`', (int)$ScheduledTaskId);

		$Query->Conditions->EqualTo('`TimeSolved`', 0);

		return $this->_Find($Query);
	}
}
?>