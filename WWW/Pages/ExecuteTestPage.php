<?
class ExecuteTestPage extends \Framework\Newnorth\Page {
	/* Variables */

	public $_Renderer = '\Framework\Newnorth\JsonRenderer';

	public $Data = null;

	public $Test;

	/* Life cycle methods */

	public function Initialize() {
		
	}

	public function Load() {
		/*$TestDataManager = $GLOBALS['Application']->GetDataManager('Test');

		$TestDataManager->Lock();

		$this->Test = $TestDataManager->FindById($GLOBALS['Application']['TestId']);

		if($this->Test === null) {
			
		}
		else if($this->Test->IsExecuting || time() < $this->Test->TimeLastExecuted + $this->ExecutionInterval) {
			
		}

		$this->Test->SetIsExecuting(true);

		$this->Test->SetTimeLastExecuted(time());

		$TestDataManager->Unlock();*/
	}

	public function Execute() {
		// Execute test
	}
}
?>