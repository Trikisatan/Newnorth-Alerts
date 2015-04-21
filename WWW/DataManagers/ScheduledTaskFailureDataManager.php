<?
class ScheduledTaskFailureDataManager extends \Framework\Newnorth\DataManager {
	/* Magic methods */

	public function __construct() {
		$this->Connection = \Framework\Newnorth\Application::GetDbConnection('Default');
	}

	/* Methods */

	public function FindById($Id) {
		$Query = new \Framework\Newnorth\DbSelectQuery();

		$Query->AddSource('ScheduledTaskFailure');

		$Query->Conditions = new \Framework\Newnorth\DbEqualTo('`Id`', (int)$Id);

		return $this->_Find($Query);
	}
}
?>