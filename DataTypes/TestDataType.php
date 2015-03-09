<?
use \Framework\Newnorth\Application;
use \Framework\Newnorth\DataType;

class TestDataType extends DataType {
	/* Variables */

	public $Id;

	public $Title;

	public $FailureCount;

	public $TimeFailed;

	public $State;

	/* Magic methods */

	public function __construct($Data) {
		if(isset($Data['Id'])) {
			$this->Id = (int)$Data['Id'];
		}

		if(isset($Data['Title'])) {
			$this->Title = $Data['Title'];
		}

		if(isset($Data['FailureCount'])) {
			$this->FailureCount = (int)$Data['FailureCount'];
		}

		if(isset($Data['TimeFailed'])) {
			$this->TimeFailed = (int)$Data['TimeFailed'];
		}

		if(isset($Data['State'])) {
			$this->State = $Data['State'];
		}
	}
}
?>