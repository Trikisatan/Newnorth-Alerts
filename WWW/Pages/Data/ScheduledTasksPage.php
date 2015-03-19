<?
namespace Data;

class ScheduledTasksPage extends \Framework\Newnorth\Page {
	/* Variables */

	public $_Renderer = '\Framework\Newnorth\JsonRenderer';

	public $Data = [];

	/* Life cycle methods */

	public function Initialize() {

	}

	public function Load() {
		$ScheduledTaskDataManager = $GLOBALS['Application']->GetDataManager('ScheduledTask');

		$ScheduledTasks = $ScheduledTaskDataManager->FindAll();

		foreach($ScheduledTasks as $ScheduledTask) {
			$TimeNextExecution = $ScheduledTask->TimeFirstExecuted + floor(($ScheduledTask->TimeLastExecuted - $ScheduledTask->TimeFirstExecuted) / $ScheduledTask->ExecutionInterval + 1) * $ScheduledTask->ExecutionInterval;

			$TimeUntilNextExecution = $TimeNextExecution - time();

			$this->Data[] = [
				'Id' => $ScheduledTask->Id,
				'PriorityLevel' => $ScheduledTask->PriorityLevel,
				'Title' => $ScheduledTask->Title,
				'IsExecuting' => $ScheduledTask->IsExecuting,
				'TimeFirstExecuted' => $ScheduledTask->TimeFirstExecuted,
				'ExecutionInterval' => $ScheduledTask->ExecutionInterval,
				'TimeLastExecuted' => $ScheduledTask->TimeLastExecuted,
				'TimeNextExecution' => $TimeNextExecution,
				'TimeUntilNextExecution' => $TimeUntilNextExecution,
				'IsDisabled' => $ScheduledTask->IsDisabled,
			];
		}
	}

	public function Execute() {

	}
}
?>