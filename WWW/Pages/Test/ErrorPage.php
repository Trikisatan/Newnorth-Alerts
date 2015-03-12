<?
namespace Test;

class ErrorPage extends \Framework\Newnorth\Page {
	/* Variables */

	public $_Renderer = '\Framework\Newnorth\JsonRenderer';

	public $Data = null;

	/* Life cycle methods */

	public function Initialize() {

	}

	public function Load() {

	}

	public function Execute() {
		$this->Data = [
			'Status' => 'Failed',
			'PriorityLevel' => $GLOBALS['Parameters']['PriorityLevel'],
			'Description' => 'This is an example of a failed test.',
		];
	}
}
?>