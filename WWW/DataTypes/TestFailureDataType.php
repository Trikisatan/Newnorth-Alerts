<?
use \Framework\Newnorth\Application;
use \Framework\Newnorth\DataType;

class TestFailureDataType extends DataType {
	/* Variables */

	public $Id;

	public $TestId;

	public $TimeFailed;

	public $TimeSolved;

	/* Magic methods */

	public function __construct($Data) {
		if(isset($Data['Id'])) {
			$this->Id = (int)$Data['Id'];
		}

		if(isset($Data['TestId'])) {
			$this->TestId = (int)$Data['TestId'];
		}

		if(isset($Data['TimeFailed'])) {
			$this->TimeFailed = (int)$Data['TimeFailed'];
		}

		if(isset($Data['TimeSolved'])) {
			$this->TimeSolved = (int)$Data['TimeSolved'];
		}
	}
}
?>