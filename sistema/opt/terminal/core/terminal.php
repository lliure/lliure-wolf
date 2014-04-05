<?php
class Terminal{
	const DEFAULT_PART = null;
	const NO_COMMAND = null;
	
	const INPUT_PASSWORD = 'password';
	const INPUT_TEXT = 'text';
	
	public static $onBeforeCommand = '';
	public static $onAfterCommand = '';


	private static $currentCom;
	private static $commandPart;
	private static $commandParameters;
    
    public static function commandExists(){
        return  file_exists(TCMD_PATH.'/'.self::$currentCom.'.php') 
                || file_exists(TCMD_PATH.'/'.self::$currentCom.'/'.self::$currentCom.'.php');
    }
	
	public static function runCommand($runParam) {
		
		//First run
		if(self::$commandPart == self::DEFAULT_PART){
			
			$runParam = self::sliceCommand($runParam);
			self::setCurrentCommand($runParam['command']);			
			self::setCommandParameters($runParam['parameters']);
			
		}else{
			self::$commandParameters = $runParam;
		}
		
		if(!self::loadCommand()){
			self::showMessage('Command not found');
		}
		
		//Event
		if(is_callable(self::$onBeforeCommand))
			call_user_func (self::$onBeforeCommand, self::$currentCom, self::$commandPart);
		
		//Run
		if(function_exists(self::$currentCom))
			call_user_func(self::$currentCom, self::$commandParameters, self::$commandPart);
		
		//Event
		if(is_callable(self::$onAfterCommand))
			call_user_func (self::$onAfterCommand, self::$currentCom, self::$onAfterCommand);
		
		if(self::$commandPart == self:: DEFAULT_PART)
			self::endCommand();
	}
	
	private static function loadCommand(){
		$file = false;
		if(file_exists(TCMD_PATH.'/'.self::$currentCom.'.php'))
			$file = TCMD_PATH.'/'.self::$currentCom.'.php';
		
        if(file_exists(TCMD_PATH.'/'.self::$currentCom.'/'.self::$currentCom.'.php'))
			$file = TCMD_PATH.'/'.self::$currentCom.'/'.self::$currentCom.'.php';
		
		if($file)
			require_once($file);
		
		return $file;
	}
	
	public static function endCommand(){
		self::setDefaultValues();
		self::setInputType(self::INPUT_TEXT);
	}
	
	public static function showMessage($message){
		echo '<div>',$message,'</div>';
	}
	
	private static function setDefaultValues(){
		self::setCurrentCommand(self::NO_COMMAND);
		self::setCommandPart(self::DEFAULT_PART);
		self::$commandParameters = array();
	}
	
	public static function setCommandPart($part){
		self::$commandPart = $part;
		echo '<script> commandPart=\''.$part.'\'; </script>';
	}
	
	public static function setCurrentCommand($c){
		self::$currentCom = $c;
		echo '<script> commandName=\''.$c.'\'; </script>';
	}
	
	public static function setCommandParameters($c){
		self::$commandParameters = $c;
	}
	
	public function __construct(){
		self::setDefaultValues();
	}
	
	public static function setInputType($type){
		echo '<script> $("#command").attr("type", "'.$type.'"); </script>';
	}
	
	public static function setArrow($arrow){
		echo '<script> $(".command-box.incomming .cmd-arrow").html(\''.$arrow.'\').css("width", textWidth(\''.$arrow.'\')); </script>';
	}
	
	public static function ask($question, $return_part, $is_password = false){
		self::setArrow($question);
		self::setCommandPart($return_part);
		self::setInputType(($is_password? self::INPUT_PASSWORD: self::INPUT_TEXT));
		die();
	}
	
	public static function sliceCommand($full_command_string){
		$ret = array(
			'command' => null,
			'parameters' => null
		);
		$command = explode(' ', $full_command_string);
		$ret['command'] = array_shift($command);
		$parameters = $command;
		unset($command);

		$tParam = array();
		$tLastKey = null;
		foreach($parameters AS $par){
			if(substr($par,0,1)=='-'){

				$tLastKey = substr($par, 1);
				$tParam[$tLastKey] = true;

			}else{

				if($tLastKey == null){
					$tParam[] = $par;
				}else{
					$tParam[$tLastKey] = $par;
					$tLastKey = null;
				}

			}
		}
		$ret['parameters'] = $tParam;
		
		return $ret;
	}
}
?>
