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
/*
	public function FindUnsolvedByTestId($TestId) {
		$Query = new \Framework\Newnorth\DbSelectQuery();

		$Query->AddSource('TestFailure');

		$Query->Conditions = new \Framework\Newnorth\DbAnd();

		$Query->Conditions->EqualTo('`TestId`', (int)$TestId);

		$Query->Conditions->EqualTo('`TimeSolved`', 0);

		return $this->_Find($Query);
	} */

	public function FindAllByTestFailureId($TestFailureId, $SortColumn = '`PriorityLevel`', $SortOrder = DB_DESC) {
		$Query = new \Framework\Newnorth\DbSelectQuery();

		$Query->AddSource('TestFailure-State');

		$Query->Conditions = new \Framework\Newnorth\DbAnd();

		$Query->Conditions->EqualTo('`TestFailureId`', (int)$TestFailureId);

		$Query->AddSort($SortColumn, $SortOrder);

		return $this->_FindAll($Query);
	}

	public function FindAllByTestId($TestId, $SortColumn = '`PriorityLevel`', $SortOrder = DB_DESC) {
		$Query = new \Framework\Newnorth\DbSelectQuery();

		$Query->AddSource('TestFailure-State');

		$Query->Conditions = new \Framework\Newnorth\DbAnd();

		$Query->Conditions->EqualTo('`TestId`', (int)$TestId);

		$Query->AddSort($SortColumn, $SortOrder);

		return $this->_FindAll($Query);
	}

	public function FindAll($SortColumn = '`PriorityLevel`', $SortOrder = DB_DESC) {
		$Query = new \Framework\Newnorth\DbSelectQuery();

		$Query->AddSource('TestFailure-State');

		$Query->Conditions = new \Framework\Newnorth\DbAnd();

		$Query->AddSort($SortColumn, $SortOrder);

		return $this->_FindAll($Query);
	}
}
?>