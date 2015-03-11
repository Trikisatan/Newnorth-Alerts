<?
use \Framework\Newnorth\Application;
use \Framework\Newnorth\DataManager;
use \Framework\Newnorth\DbSelectQuery;
use \Framework\Newnorth\DbAnd;
use \Framework\Newnorth\DbEqualTo;	
use \Framework\Newnorth\DbGreaterThan;	

class ScheduledTaskDataManager extends DataManager {
	/* Magic methods */

	public function __construct() {
		$this->Connection = Application::GetDbConnection('Default');
	}

	/* Methods */

	public function FindById($Id) {
		$Query = new DbSelectQuery();

		$Query->AddSource('ScheduledTask');

		$Query->Conditions = new DbEqualTo('`Id`', (int)$Id);

		return $this->_Find($Query);
	}

	public function FindAll($SortColumn = null, $SortOrder = null) {
		$Query = new DbSelectQuery();

		$Query->AddSource('ScheduledTask');

		$Query->AddSort($SortColumn, $SortOrder);

		return $this->_FindAll($Query);
	}

	public function FindAllExecuted($SortColumn = '`PriorityLevel`', $SortOrder = DB_DESC) {
		$Query = new DbSelectQuery();

		$Query->AddSource('ScheduledTask');

		$Query->Conditions = new DbAnd();

		$Query->Conditions->GreaterThan('`TimeExecuted`', 0);

		$Query->AddSort($SortColumn, $SortOrder);

		return $this->_FindAll($Query);
	}

	public function FindAllToExecute($SortColumn = '`PriorityLevel`', $SortOrder = DB_DESC) {
		$Query = new DbSelectQuery();

		$Query->AddSource('ScheduledTask');

		$Query->Conditions = new DbAnd();

		$Query->Conditions->EqualTo('`TimeExecuted`', 0);

		$Query->AddSort($SortColumn, $SortOrder);

		return $this->_FindAll($Query);
	}
}
?>