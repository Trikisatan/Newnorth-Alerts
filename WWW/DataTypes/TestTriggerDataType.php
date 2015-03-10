<?
use \Framework\Newnorth\Application;
use \Framework\Newnorth\DataType;

class TestTriggerDataType extends DataType {
	/* Variables */

	public $Id;

	public $TestId;

	public $Event;

	public $TargetId;

	/* Magic methods */

	public function __construct($Data) {
		if(isset($Data['Id'])) {
			$this->Id = (int)$Data['Id'];
		}

		if(isset($Data['TestId'])) {
			$this->TestId = (int)$Data['TestId'];
		}

		if(isset($Data['Event'])) {
			$this->Event = $Data['Event'];
		}

		if(isset($Data['TargetId'])) {
			$this->TargetId = (int)$Data['TargetId'];
		}
	}
}
?>