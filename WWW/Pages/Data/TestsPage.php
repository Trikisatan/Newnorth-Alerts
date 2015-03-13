<?
namespace Data;

class TestsPage extends \Framework\Newnorth\Page {
	/* Variables */

	public $_Renderer = '\Framework\Newnorth\JsonRenderer';

	public $Data = [];

	/* Life cycle methods */

	public function Initialize() {

	}

	public function Load() {
		$TestDataManager = $GLOBALS['Application']->GetDataManager('Test');

		$Tests = $TestDataManager->FindAll();

		foreach($Tests as $Test) {
			$this->Data[] = [
				'Id' => $Test->Id,
				'Title' => $Test->Title,
				'Url' => $Test->Url,
				'State' => $Test->State,
				'StatePriorityLevel' => $Test->StatePriorityLevel,
				'StateDescription' => $Test->StateDescription,
				'TimeLastFailed' => $Test->TimeLastFailed,
				'IsExecuting' => $Test->IsExecuting,
				'ExecutionInterval' => $Test->ExecutionInterval,
				'TimeLastExecuted' => $Test->TimeLastExecuted,
				'ExecutionTimeout' => $Test->ExecutionTimeout,
				'IsDisabled' => $Test->IsDisabled,
			];
		}
	}

	public function Execute() {

	}
}
?>