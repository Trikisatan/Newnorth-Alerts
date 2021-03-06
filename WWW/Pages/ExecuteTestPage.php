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
		$TestDataManager = $GLOBALS['Application']->GetDataManager('Test');

		$TestDataManager->Lock();

		$this->Test = $TestDataManager->FindById($GLOBALS['Parameters']['TestId']);

		if($this->Test === null) {
			$this->Data = false;
		}
		else if($this->Test->IsExecuting && time() < $this->Test->TimeLastExecuted + $this->Test->ExecutionTimeout) {
			$this->Data = false;

			$this->Test = null;
		}
		else if(time() < $this->Test->TimeLastExecuted + $this->Test->ExecutionInterval && !isset($_GET['force'])) {
			$this->Data = false;

			$this->Test = null;
		}
		else {
			$this->Test->SetIsExecuting(true);

			$this->Test->SetTimeLastExecuted(time());
		}

		$TestDataManager->Unlock();
	}

	public function Execute() {
		if($this->Test !== null) {
			$Url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$this->Test->Url;

			$Result = @file_get_contents($Url);

			if($Result === false) {
				$Failed = true;

				$StatePriorityLevel = 'High';

				$StateDescription = 'Unable to execute test.';
			}
			else {
				$Result = @json_decode($Result);

				if(!isset($Result->Status)) {
					$Failed = true;

					$StatePriorityLevel = 'High';

					$StateDescription = 'Unable to execute test.';
				}
				else if($Result->Status === 'OK') {
					$Failed = false;
				}
				else {
					$Failed = true;

					$StatePriorityLevel = isset($Result->PriorityLevel) ? $Result->PriorityLevel : 'Unknown';

					$StateDescription = isset($Result->Description) ? $Result->Description : 'No description available.';
				}
			}

			if($Failed) {
				if($this->Test->State === 'FAILED') {
					$this->Test->SetStatePriorityLevel($StatePriorityLevel);

					$this->Test->SetStateDescription($StateDescription);
				}
				else {
					$this->Test->SetState('FAILED');

					$this->Test->SetStatePriorityLevel($StatePriorityLevel);

					$this->Test->SetStateDescription($StateDescription);

					$this->Test->SetTimeLastFailed(time());
				}

				$TestFailureDataManager = $GLOBALS['Application']->GetDataManager('TestFailure');

				$TestFailure = $TestFailureDataManager->Insert($GLOBALS['Parameters']['TestId']);

				$TestFailureStateDataManager = $GLOBALS['Application']->GetDataManager('TestFailureState');

				$TestFailureStateDataManager->Insert($TestFailure->Id, $GLOBALS['Parameters']['TestId'], $this->Test->StatePriorityLevel, $this->Test->StateDescription);
			}
			else {
				$this->Test->SetState('OK');

				$TestFailureDataManager = $GLOBALS['Application']->GetDataManager('TestFailure');

				$TestFailure = $TestFailureDataManager->FindUnsolvedByTestId($GLOBALS['Parameters']['TestId']);

				$TestFailure->SetTimeSolved(time());
			}

			$this->Test->SetIsExecuting(false);

			$this->Data = [
				'State' => $this->Test->State,
				'StatePriorityLevel' => $this->Test->StatePriorityLevel,
				'StateDescription' => $this->Test->StateDescription,
				'TimeLastFailed' => $this->Test->TimeLastFailed,
				'IsExecuting' => $this->Test->IsExecuting,
				'TimeLastExecuted' => $this->Test->TimeLastExecuted,
			];
		}
	}
}
?>