<?
use \Framework\Newnorth\Application;
use \Framework\Newnorth\DataManager;
use \Framework\Newnorth\DbSelectQuery;
use \Framework\Newnorth\DbAnd;
use \Framework\Newnorth\DbEqualTo;	

class TestTriggerDataManager extends DataManager {
	/* Magic methods */

	public function __construct() {
		$this->Connection = Application::GetDbConnection('Default');
	}

	/* Methods */

	public function FindById($Id) {
		$Query = new DbSelectQuery();

		$Query->AddSource('Test-Trigger');

		$Query->Conditions = new DbEqualTo('`Id`', (int)$Id);

		return $this->_Find($Query);
	}

	public function FindAllByTestId($TestId) {
		$Query = new DbSelectQuery();

		$Query->AddSource('Test-Trigger');

		$Query->Conditions = new DbAnd();

		$Query->Conditions->EqualTo('`TestId`', (int)$TestId);

		return $this->_FindAll($Query);
	}
}
?>