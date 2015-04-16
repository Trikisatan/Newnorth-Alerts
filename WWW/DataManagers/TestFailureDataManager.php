<?
use \Framework\Newnorth\Application;
use \Framework\Newnorth\DataManager;
use \Framework\Newnorth\DbSelectQuery;
use \Framework\Newnorth\DbAnd;
use \Framework\Newnorth\DbEqualTo;

class TestFailureDataManager extends \Framework\Newnorth\DataManager {
	/* Magic methods */

	public function __construct() {
		$this->Connection = \Framework\Newnorth\Application::GetDbConnection('Default');
	}

	/* Methods */

	public function Insert($TestId) {
		$Query = new \Framework\Newnorth\DbInsertQuery();

		$Query->Source = 'TestFailure';

		$Query->AddColumn('`TestId`');
		$Query->AddColumn('`TimeFailed`');

		$Query->AddValue($TestId);
		$Query->AddValue(time());

		$Id = $this->_Insert($Query);

		if($Id === false) {
			return null;
		}

		return $this->FindById($Id);
	}

	public function FindById($Id) {
		$Query = new \Framework\Newnorth\DbSelectQuery();

		$Query->AddSource('TestFailure');

		$Query->Conditions = new \Framework\Newnorth\DbEqualTo('`Id`', (int)$Id);

		return $this->_Find($Query);
	}

	public function FindUnsolvedByTestId($TestId) {
		$Query = new \Framework\Newnorth\DbSelectQuery();

		$Query->AddSource('TestFailure');

		$Query->Conditions = new \Framework\Newnorth\DbAnd();

		$Query->Conditions->EqualTo('`TestId`', (int)$TestId);

		$Query->Conditions->EqualTo('`TimeSolved`', 0);

		return $this->_Find($Query);
	}

	public function FindAllByTestId($TestId) {
		$Query = new \Framework\Newnorth\DbSelectQuery();

		$Query->AddSource('TestFailure');

		$Query->Conditions = new \Framework\Newnorth\DbAnd();

		$Query->Conditions->EqualTo('`TestId`', (int)$TestId);

		return $this->_FindAll($Query);
	}

	public function FindAllActive($SortColumn = '`TimeFailed`', $SortOrder = DB_DESC) {
		$Query = new \Framework\Newnorth\DbSelectQuery();

		$Query->AddSource('TestFailure');

		$Query->Conditions = new \Framework\Newnorth\DbAnd();

		$Query->Conditions->EqualTo('`TimeSolved`', 0);

		$Query->AddSort($SortColumn, $SortOrder);

		return $this->_FindAll($Query);
	}
}
?>