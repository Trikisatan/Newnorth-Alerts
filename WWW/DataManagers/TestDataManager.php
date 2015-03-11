<?
use \Framework\Newnorth\Application;
use \Framework\Newnorth\DataManager;
use \Framework\Newnorth\DbSelectQuery;
use \Framework\Newnorth\DbAnd;
use \Framework\Newnorth\DbEqualTo;	

class TestDataManager extends DataManager {
	/* Magic methods */

	public function __construct() {
		$this->Connection = Application::GetDbConnection('Default');
	}

	/* Methods */

	public function Lock() {
		$this->Connection->Lock(['Test' => 'WRITE']);
	}

	public function Unlock() {
		$this->Connection->Unlock();
	}

	public function FindById($Id) {
		$Query = new DbSelectQuery();

		$Query->AddSource('Test');

		$Query->Conditions = new DbEqualTo('`Id`', (int)$Id);

		return $this->_Find($Query);
	}

	public function FindAll($SortColumn = null, $SortOrder = null) {
		$Query = new DbSelectQuery();

		$Query->AddSource('Test');

		$Query->AddSort($SortColumn, $SortOrder);

		return $this->_FindAll($Query);
	}

	public function FindAllByState($State, $SortColumn = '`TimeLastFailed`', $SortOrder = DB_DESC) {
		$Query = new DbSelectQuery();

		$Query->AddSource('Test');

		$Query->Conditions = new DbAnd();

		$Query->Conditions->EqualTo('`State`', '"'.$State.'"');

		$Query->AddSort($SortColumn, $SortOrder);

		return $this->_FindAll($Query);
	}
}
?>