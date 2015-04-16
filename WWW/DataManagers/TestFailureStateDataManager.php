<?
class TestFailureStateDataManager extends \Framework\Newnorth\DataManager {
	/* Magic methods */

	public function __construct() {
		$this->Connection = \Framework\Newnorth\Application::GetDbConnection('Default');
	}

	/* Methods */

	public function Insert($TestFailureId, $TestId, $PriorityLevel, $Description) {
		$Query = new \Framework\Newnorth\DbInsertQuery();

		$Query->Source = 'TestFailure-State';

		$Query->AddColumn('`TestFailureId`');
		$Query->AddColumn('`TestId`');
		$Query->AddColumn('`PriorityLevel`');
		$Query->AddColumn('`Description`');
		$Query->AddColumn('`TimeCreated`');

		$Query->AddValue($TestFailureId);
		$Query->AddValue($TestId);
		$Query->AddValue('"'.$PriorityLevel.'"');
		$Query->AddValue('"'.$Description.'"');
		$Query->AddValue(time());

		$Id = $this->_Insert($Query);

		if($Id === false) {
			return null;
		}

		return $this->FindById($Id);
	}

	public function FindById($Id) {
		$Query = new \Framework\Newnorth\DbSelectQuery();

		$Query->AddSource('TestFailure-State');

		$Query->Conditions = new \Framework\Newnorth\DbEqualTo('`Id`', (int)$Id);

		return $this->_Find($Query);
	}
}
?>