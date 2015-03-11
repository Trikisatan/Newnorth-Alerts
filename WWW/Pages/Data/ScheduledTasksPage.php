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
			$this->Data[] = [
				'Id' => $ScheduledTask->Id,
				'PriorityLevel' => $ScheduledTask->PriorityLevel,
				'Title' => $ScheduledTask->Title,
				'PlannedInterval' => $ScheduledTask->PlannedInterval,
				'MaxInterval' => $ScheduledTask->MaxInterval,
				'TimeExecuted' => $ScheduledTask->TimeExecuted,
			];
		}
	}

	public function Execute() {
		
	}
}
?>