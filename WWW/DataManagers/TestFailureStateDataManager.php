<?
class TestFailureStateDataManager extends \Framework\Newnorth\DataManager {
	/* Magic methods */

	public function __construct() {
		$this->Connection = \Framework\Newnorth\Application::GetDbConnection('Default');
	}

	/* Methods */

	public function FindById($Id) {
		$Query = new \Framework\Newnorth\DbSelectQuery();

		$Query->AddSource('TestFailure-State');

		$Query->Conditions = new \Framework\Newnorth\DbEqualTo('`Id`', (int)$Id);

		return $this->_Find($Query);
	}
}
?>