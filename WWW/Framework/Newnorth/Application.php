<?
namespace Framework\Newnorth;

class Application {
	/* Static variables */

	static private $Instance = null;

	static private $Url;

	static private $Config;

	static public $Files = [
		'DataManagers' => '',
		'DataTypes' => '',
		'EMailTemplates' => '',
		'Layouts' => '',
		'Pages' => '',
		'Translations' => '',
	];

	static public $ErrorHandling = [
		'RouteNotFound' => [
			'ThrowException' => true,
			'Report' => true,
		],
		'404' => [],
	];

	static public $Translation = [
		'ThrowException' => true,
		'Report' => true,
	];

	static private $DefaultLocale;

	static private $DisplayErrors;

	static private $DisplayErrorDetails;

	static private $LogErrors;

	static private $LogFile;

	static private $EMailErrors;

	static private $EMailFrom;

	static private $EMailTo;

	static private $ErrorPage;

	static private $Cache = null;

	static private $DbConnections = [];

	static private $DataManagers = [];

	static private $Routes = [];

	/* Static methods */

	static private function LoadConfig($FilePath) {
		$FilePath = $FilePath.'.ini';

		$Config = ParseIniFile($FilePath);

		Application::$Config = $Config;

		if(isset($Config['Files'])) {
			Application::LoadConfig_Files($Config['Files']);
		}

		if(isset($Config['ErrorHandling'])) {
			Application::LoadConfig_ErrorHandling($Config['ErrorHandling']);
		}

		Application::$DefaultLocale = isset($Config['DefaultLocale']) ? $Config['DefaultLocale'] : '';

		Application::$DisplayErrors = isset($Config['ErrorHandling']['DisplayErrors']) ? $Config['ErrorHandling']['DisplayErrors'] === '1' : true;

		Application::$DisplayErrorDetails = isset($Config['ErrorHandling']['DisplayErrorDetails']) ? $Config['ErrorHandling']['DisplayErrorDetails'] === '1' : true;

		Application::$LogErrors = isset($Config['ErrorHandling']['LogErrors']) ? $Config['ErrorHandling']['LogErrors'] === '1' : true;

		Application::$LogFile = isset($Config['ErrorHandling']['LogFile']) ? $Config['ErrorHandling']['LogFile'] : 'errors.log';

		Application::$EMailErrors = isset($Config['ErrorHandling']['EMailErrors']) ? $Config['ErrorHandling']['EMailErrors'] === '1' : false;

		Application::$EMailFrom = isset($Config['ErrorHandling']['EMailFrom']) ? $Config['ErrorHandling']['EMailFrom'] : '';

		Application::$EMailTo = isset($Config['ErrorHandling']['EMailTo']) ? $Config['ErrorHandling']['EMailTo'] : '';

		Application::$ErrorPage = isset($Config['ErrorHandling']['ErrorPage']) ? $Config['ErrorHandling']['ErrorPage'] : '';

		if(isset($Config['Translation'])) {
			Application::LoadConfig_Translation($Config['Translation']);
		}

		if(isset($Config['DbConnections'])) {
			Application::LoadConfig_DbConnections($Config['DbConnections']);
		}
	}

	static private function LoadConfig_Files($Section) {
		Application::$Files['DataManagers'] = isset($Section['DataManagers']) ? $Section['DataManagers'] : Application::$Files['DataManagers'];

		Application::$Files['DataTypes'] = isset($Section['DataTypes']) ? $Section['DataTypes'] : Application::$Files['DataTypes'];

		Application::$Files['EMailTemplates'] = isset($Section['EMailTemplates']) ? $Section['EMailTemplates'] : Application::$Files['EMailTemplates'];

		Application::$Files['Layouts'] = isset($Section['Layouts']) ? $Section['Layouts'] : Application::$Files['Layouts'];

		Application::$Files['Pages'] = isset($Section['Pages']) ? $Section['Pages'] : Application::$Files['Pages'];

		Application::$Files['Translations'] = isset($Section['Translations']) ? $Section['Translations'] : Application::$Files['Translations'];
	}

	static private function LoadConfig_ErrorHandling($Section) {
		Application::$ErrorHandling['RouteNotFound']['ThrowException'] = isset($Section['RouteNotFound']['ThrowException']) ? $Section['RouteNotFound']['ThrowException'] : Application::$ErrorHandling['RouteNotFound']['ThrowException'];

		Application::$ErrorHandling['RouteNotFound']['Report'] = isset($Section['RouteNotFound']['Report']) ? $Section['RouteNotFound']['Report'] : Application::$ErrorHandling['RouteNotFound']['Report'];

		Application::$ErrorHandling['404'] = isset($Section['404']) ? $Section['404'] : Application::$ErrorHandling['404'];
	}

	static private function LoadConfig_Translation($Section) {
		Application::$Translation['ThrowException'] = isset($Section['ThrowException']) ? ($Section['ThrowException'] === '1') : Application::$Translation['ThrowException'];

		Application::$Translation['Report'] = isset($Section['Report']) ? ($Section['Report'] === '1') : Application::$Translation['Report'];
	}

	static private function LoadConfig_DbConnections($DbConnections) {
		foreach($DbConnections as $Name => $Parameters) {
			if(!isset($Parameters['Type'])) {
				throw new ConfigException(
					'Type not set.',
					[
						'DbConnection' => $Name,
					]
				);
			}

			if(!class_exists($Parameters['Type'], false)) {
				throw new ConfigException(
					'Type not found.',
					[
						'DbConnection' => $Name,
						'Type' => $Parameters['Type'],
					]
				);
			}

			Application::$DbConnections[$Name] = new $Parameters['Type']($Parameters);
		}
	}

	static public function HasConfig($Section) {
		return isset(Application::$Config[$Section]);
	}

	static public function GetConfig($Section) {
		return Application::$Config[$Section];
	}

	static public function GetDbConnection($Name) {
		$DbConnection = Application::$DbConnections[$Name];

		if(!$DbConnection->IsConnected) {
			$DbConnection->Connect();
		}

		return $DbConnection;
	}

	static public function GetDataManager($Name) {
		if(isset(Application::$DataManagers[$Name])) {
			return Application::$DataManagers[$Name];
		}

		$DataManager = '\\'.str_replace('/', '\\', $Name).'DataManager';

		if(!class_exists($DataManager, false)) {
			include(Application::$Files['DataManagers'].$Name.'DataManager.php');

			if(!class_exists($DataManager, false)) {
				throw new \exception('Unable to get the data manager "'.$DataManager.'", it does not exist.');
			}
		}

		$DataManager = new $DataManager();

		$DataManager->DataType = '\\'.str_replace('/', '\\', $Name).'DataType';

		if(!class_exists($DataManager->DataType, false)) {
			include(Application::$Files['DataTypes'].$Name.'DataType.php');

			if(!class_exists($DataManager->DataType, false)) {
				throw new \exception('Unable to get the data type "'.$DataManager->DataType.'", it does not exist.');
			}
		}

		return Application::$DataManagers[$Name] = $DataManager;
	}

	static public function HandleError($Type, $Message, $File, $Line, $Data, $StackTrace) {
		ob_clean();

		Application::FormatStackTrace($StackTrace);

		$Data = [
			'Url' => Application::$Url,
			'Referrer' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
			'File' => $File,
			'Line' => $Line,
			'Data' => $Data,
			'StackTrace' => $StackTrace,
		];

		if(Application::$LogErrors) {
			Application::LogError($Type, $Message, $Data);
		}

		if(Application::$EMailErrors) {
			Application::EMailError($Type, $Message, $Data);
		}

		if(isset(Application::$ErrorPage[0])) {
			Application::ShowErrorPage($Type, $Message, $Data);
		}
		else if(Application::$DisplayErrors) {
			Application::PrintError($Type, $Message, $Data);
		}

		exit();
	}

	static private function FormatStackTrace(&$StackTrace) {
		for($I = 0; $I < count($StackTrace); ++$I) {
			$Row = $StackTrace[$I];
			$String = '';

			if(isset($Row['class'])) {
				$String .= $Row['class'].$Row['type'];
			}

			$String .= $Row['function'].'(...)';

			if(isset($Row['file'], $Row['line'])) {
				$String .= ' in '.$Row['file'].' on line '.$Row['line'];
			}

			$StackTrace[$I] = $String;
		}
	}

	static public function LoadCache($Key, $TimeToLive, &$Data) {
		$Path = 'Cache/'.md5($Key);

		if(!file_exists($Path)) {
			return false;
		}

		$Data = @file_get_contents($Path, $Data);

		if($Data === false) {
			return false;
		}
		
		$Newline = strpos($Data, "\n");
		$Time = (int)substr($Data, 0, $Newline);
		$Data = substr($Data, $Newline + 1);

		if($Time < time() - $TimeToLive) {
			unlink($Path);
			return false;
		}

		return true;
	}

	static private function LoadPageCache() {
		if(!isset(Application::$Config['Cache'][Application::$Url])) {
			return false;
		}

		if(!Application::LoadCache(Application::$Url, Application::$Config['Cache'][Application::$Url], $Contents)) {
			return false;
		}

		Application::$Cache = $Contents;
		return true;
	}

	static public function SaveCache($Key, $Data) {
		file_put_contents('Cache/'.md5($Key), time()."\n".$Data);
	}

	static public function SavePageCache() {
		if(!isset(Application::$Config['Cache'][Application::$Url])) {
			return false;
		}

		Application::SaveCache(Application::$Url, ob_get_contents());
	}

	static private function LogError($Type, $Message, $Data) {
		$Data = array_merge(
			array(
				'Type' => $Type,
				'Message' => $Message,
			),
			$Data
		);

		try {
			file_put_contents(
				Application::$LogFile,
				json_encode($Data)."\n",
				FILE_APPEND
			);
		}
		catch(Exception $Exception) {
			
		}
	}

	static private function EMailError($Type, $Message, $Data) {
		if(isset(Application::$EMailTo[0])) {
			$EMail = new EMail();

			if(isset(Application::$EMailFrom[0])) {
				$EMail->SetFrom(Application::$EMailFrom);
			}

			$EMail->SetSubject($Type.': '.$Message);
			$EMail->SetHtml(
				'<b>'.$Type.'</b><br />'.
				htmlspecialchars($Message).
				Application::CreateErrorMessage(null, $Data)
			);

			try {
				$EMail->Send(Application::$EMailTo);
			}
			catch(Exception $Exception) { }
		}
	}

	static private function ShowErrorPage($Type, $Message, $Data) {
		try {
			require(Application::$ErrorPage);
		}
		catch(Exception $Exception) { }
	}

	static private function PrintError($Type, $Message, $Data) {
		if(Application::$DisplayErrorDetails) {
			echo '<b>', $Type, '</b><br />', htmlspecialchars($Message), Application::CreateErrorMessage(null, $Data);
		}
		else {
			echo '<b>', $Type, '</b><br />', htmlspecialchars($Message);
		}
	}

	static private function CreateErrorMessage($Section, $Data) {
		if($Section === null) {
			$Message = '';

			foreach($Data as $Section => $SubData) {
				$SubMessage = Application::CreateErrorMessage($Section, $SubData);

				if(isset($SubMessage[0])) {
					if(is_int($Section)) {
						$Message .= $SubMessage;
					}
					else if(is_array($SubData)) {
						$Message .= '<br />'.$SubMessage;
					}
					else {
						$Message .= $SubMessage;
					}
				}
			}

			return $Message;
		}

		if(is_int($Section)) {
			return '<br />'.htmlspecialchars($Data);
		}

		if(is_array($Data)) {
			if(count($Data) === 0) {
				return '';
			}

			$Message = '<br /><b>'.$Section.'</b>';

			foreach($Data as $SubSection => $SubData) {
				$Message .= Application::CreateErrorMessage($SubSection, $SubData);
			}

			return $Message;
		}

		return '<br /><b>'.$Section.':</b> '.htmlspecialchars($Data);
	}

	/* Variables */

	public $Translations;

	/* Magic methods */

	public function __construct($ConfigFilePath = 'Config', $RoutesFilePath = 'Routes') {
		if(Application::$Instance !== null) {
			throw new ConfigException('Application has already been initialized.');
		}

		if(!isset($_SESSION['Token'])) {
			$_SESSION['Token'] = md5(rand());
		}

		Application::$Instance = $this;

		Application::$Url = isset($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : '/';

		Application::LoadConfig($ConfigFilePath);

		if(!Application::LoadPageCache()) {
			$this->LoadRoutes($RoutesFilePath);

			if(!$this->ParseUrl()) {
				if(Application::$ErrorHandling['RouteNotFound']['ThrowException']) {
					throw new ConfigException(
						'Route not found.',
						[]
					);
				}
				else if(Application::$ErrorHandling['RouteNotFound']['Report']) {
					Application::LogError(
						'Route not found',
						'A route is missing.',
						[
							'Url' => Application::$Url,
						]
					);
				}

				$this->PageNotFound();
			}

			$this->Translations = new Translations($this, '');
		}
	}

	public function __toString() {
		return '';
	}

	/* Methods */

	private function LoadRoutes($FilePath) {
		$FilePath = $FilePath.'.ini';

		$Routes = ParseIniFile($FilePath);

		foreach($Routes as $Name => $Data) {
			if(!isset($Data['Pattern'])) {
				throw new ConfigException(
					'Pattern not set.',
					[
						'File' => $FilePath,
						'Route' => $Name,
					]
				);
			}

			Application::$Routes[$Name] = new \Framework\Newnorth\Route(
				$Name,
				$Data['Pattern'],
				isset($Data['Requirements']) ? $Data['Requirements'] : array(),
				isset($Data['Translations']) ? $Data['Translations'] : array(),
				isset($Data['Defaults']) ? $Data['Defaults'] : array()
			);
		}
	}

	private function ParseUrl() {
		foreach(Application::$Routes as $RouteName => $Route) {
			if($Route->Match(Application::$Url, $Parameters)) {
				if(isset($Parameters['Locale'][0])) {
					$Locale = $Parameters['Locale'];
				}
				else if(isset($_SESSION['Locale'][0])) {
					$Locale = $_SESSION['Locale'];
				}
				else {
					$Locale = Application::$DefaultLocale;
				}

				if($Route->Translate($Parameters, $Locale)) {
					$GLOBALS['Parameters'] = $Parameters;

					$GLOBALS['Parameters']['Locale'] = $Locale;

					return true;
				}
			}
		}

		return false;
	}

	public function GenerateUrl(array $Parameters) {
		// Typecast all parameters to a string to avoid comparison problems.
		foreach($Parameters as $Key => $Value) {
			$Parameters[$Key] = (string)$Value;
		}

		if(!isset($Parameters['Page'])) {
			if(isset($GLOBALS['Parameters']['Page'])) {
				$Parameters['Page'] = $GLOBALS['Parameters']['Page'];
			}
		}

		if(!isset($Parameters['Locale'])) {
			if(isset($GLOBALS['Parameters']['Locale'])) {
				$Parameters['Locale'] = $GLOBALS['Parameters']['Locale'];
			}
			else {
				$Parameters['Locale'] = '';
			}
		}

		if(isset($Parameters['Route'][0])) {
			$Route = Application::$Routes[$Route];
			$RouteParameters = array_slice($Parameters, 0);

			$Route->SetDefaults($RouteParameters);

			foreach($GLOBALS['Parameters'] as $Key => $Value) {
				if(is_int($Key)) {
					continue;
				}

				if(isset($RouteParameters[$Key])) {
					continue;
				}

				$RouteParameters[$Key.'?'] = $Value;
			}

			$Route->ReversedTranslate($RouteParameters, $Parameters['Locale']);

			if($Route->ReversedMatch($RouteParameters, $Parameters['Locale'], $Url)) {
				return $Url;
			}
		}
		else {
			foreach(Application::$Routes as $RouteName => $Route) {
				$RouteParameters = array_slice($Parameters, 0);

				$Route->SetDefaults($RouteParameters);

				foreach($GLOBALS['Parameters'] as $Key => $Value) {
					if(is_int($Key)) {
						continue;
					}

					if(isset($RouteParameters[$Key])) {
						continue;
					}

					$RouteParameters[$Key.'?'] = $Value;
				}

				$Route->ReversedTranslate($RouteParameters, $Parameters['Locale']);

				if($Route->ReversedMatch($RouteParameters, $Parameters['Locale'], $Url)) {
					return $Url;
				}
			}
		}

		throw new ConfigException(
			'Unable to generate URL.',
			array(
				'Parameters' => $Parameters,
			)
		);
	}

	private function LoadLayout() {
		$FullName = $GLOBALS['Parameters']['Layout'].'Layout';

		$ClassName = str_replace('/', '\\', $FullName);

		if(!class_exists($ClassName, false)) {
			$FilePath = Application::$Files['Layouts'].$FullName.'.php';

			@include($FilePath);

			if(!class_exists($ClassName, false)) {
				throw new ConfigException(
					'Layout not found.',
					[
						'File' => $FilePath,
						'Class' => $ClassName,
					]
				);
			}
		}

		$Directory = strrpos($FullName, '/');

		if($Directory === false) {
			$GLOBALS['Layout'] = new $ClassName(
				'',
				$FullName
			);
		}
		else {
			$GLOBALS['Layout'] = new $ClassName(
				substr($FullName, 0, $Directory + 1),
				substr($FullName, $Directory + 1)
			);
		}
	}

	private function LoadPage() {
		$FullName = $GLOBALS['Parameters']['Page'].'Page';

		$ClassName = str_replace('/', '\\', $FullName);

		if(!class_exists($ClassName, false)) {
			$FilePath = Application::$Files['Pages'].$FullName.'.php';

			@include($FilePath);

			if(!class_exists($ClassName, false)) {
				throw new ConfigException(
					'Page not found.',
					[
						'File' => $FilePath,
						'Class' => $ClassName,
					]
				);
			}
		}

		$Directory = strrpos($FullName, '/');

		if($Directory === false) {
			$GLOBALS['Page'] = new $ClassName(
				'',
				$FullName
			);
		}
		else {
			$GLOBALS['Page'] = new $ClassName(
				substr($FullName, 0, $Directory + 1),
				substr($FullName, $Directory + 1)
			);
		}
	}

	public function Run() {
		global $InitializationTime, $LoadTime, $ExecutionTime, $RenderTime;
		
		if(Application::$Cache === null) {
			if(!isset($GLOBALS['Parameters']['Page'][0])) {
				throw new ConfigException(
					'Page not set.',
					[]
				);
			}
			else if(!isset($GLOBALS['Parameters']['Layout'][0])) {
				$this->LoadPage();

				$start = microtime(true);
				$GLOBALS['Page']->PreInitialize();
				$GLOBALS['Page']->Initialize();
				$GLOBALS['Page']->PostInitialize();
				$InitializationTime = microtime(true) - $start;

				$start = microtime(true);
				$GLOBALS['Page']->PreLoad();
				$GLOBALS['Page']->Load();
				$GLOBALS['Page']->PostLoad();
				$LoadTime = microtime(true) - $start;

				$start = microtime(true);
				$GLOBALS['Page']->PreExecute();
				$GLOBALS['Page']->Execute();
				$GLOBALS['Page']->PostExecute();
				$ExecutionTime = microtime(true) - $start;

				$this->Run_Render();
			}
			else {
				$this->LoadLayout();

				$this->LoadPage();

				$start = microtime(true);
				$GLOBALS['Layout']->PreInitialize();
				$GLOBALS['Page']->PreInitialize();
				$GLOBALS['Layout']->Initialize();
				$GLOBALS['Page']->Initialize();
				$GLOBALS['Layout']->PostInitialize();
				$GLOBALS['Page']->PostInitialize();
				$InitializationTime = microtime(true) - $start;

				$start = microtime(true);
				$GLOBALS['Layout']->PreLoad();
				$GLOBALS['Page']->PreLoad();
				$GLOBALS['Layout']->Load();
				$GLOBALS['Page']->Load();
				$GLOBALS['Layout']->PostLoad();
				$GLOBALS['Page']->PostLoad();
				$LoadTime = microtime(true) - $start;

				$start = microtime(true);
				$GLOBALS['Layout']->PreExecute();
				$GLOBALS['Page']->PreExecute();
				$GLOBALS['Layout']->Execute();
				$GLOBALS['Page']->Execute();
				$GLOBALS['Layout']->PostExecute();
				$GLOBALS['Page']->PostExecute();
				$ExecutionTime = microtime(true) - $start;

				$this->Run_Render();
			}

			Application::SavePageCache();
		}
		else
		{
			$InitializationTime = 0;
			$LoadTime = 0;
			$ExecutionTime = 0;

			$start = microtime(true);
			echo Application::$Cache;
			$RenderTime = microtime(true) - $start;
		}
	}

	private function Run_Render()
	{
		if($GLOBALS['Layout'] !== null) {
			$GLOBALS['Layout']->Render();
		}
		else {
			$GLOBALS['Page']->Render();
		}

		$Output = ob_get_contents();ob_clean();

		$this->Translations->Translate($Output);

		if(Application::$Translation['ThrowException']) {
			if(!$this->Translations->IsTranslated($Output, $Translations)) {
				throw new ConfigException(
					'Translation(s) missing.',
					[
						'Translations' => $Translations,
					]
				);
			}
		}
		else if(Application::$Translation['Report']) {
			if(!$this->Translations->IsTranslated($Output, $Translations)) {
				foreach($Translations as $Translation) {
					Application::LogError(
						'Translation not found',
						'A translation is missing.',
						[
							'Url' => Application::$Url,
							'Translation' => $Translation,
						]
					);
				}
			}
		}

		echo $Output;
	}

	public function PageNotFound() {
		header('HTTP/1.0 404 Not Found');

		$GLOBALS['Parameters'] = Application::$ErrorHandling['404'];
	}

	public function GetTranslation($Key, $DefaultValue = null) {
		return isset($this->Translations[$Key]) ? $this->Translations[$Key] : $DefaultValue;
	}

	public function SetTranslation($Key, $Value) {
		$this->Translations[$Key] = $Value;
	}

	public function GetValidatorMethod($ActionName, $MethodName, &$MethodObject) {
		if(method_exists($this, $MethodName)) {
			$MethodObject = $this;
			return true;
		}

		return false;
	}

	public function GetValidatorRenderMethod($MethodName, &$MethodObject) {
		if(method_exists($this, $MethodName)) {
			$MethodObject = $this;
			return true;
		}

		return false;
	}

	/* Validator methods */

	public function TokenValidator($Control) {
		if($Control === null) {
			throw new ConfigException(
				'Validator requires a control.',
				[
					'Validator' => 'TokenValidator',
				]
			);
		}

		return $_POST[$Control->_Parameters['Name']] === $_SESSION['Token'];
	}

	public function DropDownListValidator($Control) {
		if($Control === null) {
			throw new ConfigException(
				'Validator requires a control.',
				[
					'Validator' => 'DropDownListValidator',
				]
			);
		}

		$Value = $_POST[$Control->_Parameters['Name']];

		return isset($Control->_Parameters['Options'][$Value]);
	}

	public function EMailAddressFormatValidator($Control) {
		if($Control === null) {
			throw new ConfigException(
				'Validator requires a control.',
				[
					'Validator' => 'EMailAddressFormatValidator',
				]
			);
		}

		return 0 < preg_match('/^([a-zA-Z0-9_.-]+@[a-zA-Z0-9.-]+.[a-zA-Z]+)?$/', $_POST[$Control->_Parameters['Name']]);
	}

	public function FileUploadedValidator($Control) {
		if($Control === null) {
			throw new ConfigException(
				'Validator requires a control.',
				[
					'Validator' => 'FileUploadedValidator',
				]
			);
		}

		return 0 < $_FILES[$Control->_Parameters['Name']]['size'];
	}

	public function IsBetweenValidator($Control) {
		if($Control === null) {
			throw new ConfigException(
				'This validator requires a control.',
				[
					'Validator' => 'IsBetweenValidator',
				]
			);
		}

		$Value = (int)$_POST[$Control->_Parameters['Name']];

		return $Control->_Parameters['MinValue'] <= $Value && $Value <= $Control->_Parameters['MaxValue'];
	}

	public function IsDigitsValidator($Control) {
		if($Control === null) {
			throw new ConfigException(
				'This validator requires a control.',
				[
					'Validator' => 'IsDigitsValidator',
				]
			);
		}

		return ctype_digit($_POST[$Control->_Parameters['Name']]);
	}

	public function IsNumericValidator($Control) {
		if($Control === null) {
			throw new ConfigException(
				'This validator requires a control.',
				[
					'Validator' => 'IsNumericValidator',
				]
			);
		}

		return is_numeric($_POST[$Control->_Parameters['Name']]);
	}

	public function MaxLengthValidator($Control) {
		if($Control === null) {
			throw new ConfigException(
				'This validator requires a control.',
				[
					'Validator' => 'MaxLengthValidator',
				]
			);
		}

		return !isset($_POST[$Control->_Parameters['Name']][$Control->_Parameters['MaxLength']]);
	}

	public function MinLengthValidator($Control) {
		if($Control === null) {
			throw new ConfigException(
				'This validator requires a control.',
				[
					'Validator' => 'MinLengthValidator',
				]
			);
		}

		return isset($_POST[$Control->_Parameters['Name']][$Control->_Parameters['MinLength']]);
	}

	public function ValueNotEmptyValidator($Control) {
		if($Control === null) {
			throw new ConfigException(
				'This validator requires a control.',
				[
					'Validator' => 'ValueNotEmptyValidator',
				]
			);
		}

		return isset($_POST[$Control->_Parameters['Name']][0]);
	}

	/* Validator render methods */

	public function RenderValueNotEmptyValidator($Control, $Parameters) {
		return 'return 0<this.value.length';
	}

	public function RenderValueRegExpValidator($Control, $Parameters) {
		return 'return -1<this.value.search('.$Parameters['RegExp'].')';
	}

	public function RenderEmailAddressFormatValidator($Control, $Parameters) {
		return 'return -1<this.value.search(/^([a-zA-Z0-9_.-]+@[a-zA-Z0-9.-]+.[a-zA-Z]+)?$/)';
	}
}
?>