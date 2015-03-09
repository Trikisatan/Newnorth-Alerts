<?
use \Framework\Newnorth\Application;
use \Framework\Newnorth\DataManager;
use \Framework\Newnorth\DbSelectQuery;
use \Framework\Newnorth\DbAnd;
use \Framework\Newnorth\DbEqualTo;	

class TestFailureDataManager extends DataManager {
	/* Magic methods */

	public function __construct() {
		$this->Connection = Application::GetDbConnection('Default');
	}

	/* Methods */

	public function FindById($Id) {
		$Query = new DbSelectQuery();

		$Query->AddSource('TestFailure');

		$Query->Conditions = new DbEqualTo('`Id`', (int)$Id);

		return $this->_Find($Query);
	}

	public function FindAllActive($SortColumn = '`TimeFailed`', $SortOrder = DB_DESC) {
		$Query = new DbSelectQuery();

		$Query->AddSource('TestFailure');

		$Query->Conditions = new DbAnd();

		$Query->Conditions->EqualTo('`TimeSolved`', 0);

		$Query->AddSort($SortColumn, $SortOrder);

		return $this->_FindAll($Query);
	}
}
?>