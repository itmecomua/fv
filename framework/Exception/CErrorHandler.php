<?php
class CErrorHandler
{
	public $maxSourceLines=25;
	public $maxTraceSourceLines = 10;
	public $discardOutput=true;
	public $errorAction;
	private $_error;




	public function handleError($event)
	{
        $trace=debug_backtrace();
        
        echo "<pre>";
//        print_r($trace);
        print_r($trace[0]);
        echo "</pre>";
        die();
        
        
        /*
        if(count($trace)>3)
			$trace=array_slice($trace,3);
        */            
		$traceString='';
		foreach($trace as $i=>$t)
		{
			if(!isset($t['file'])){$trace[$i]['file']='unknown';}
			if(!isset($t['line'])){$trace[$i]['line']=0;}
			if(!isset($t['function'])){$trace[$i]['function']='unknown';}				

			$traceString.="#$i {$trace[$i]['file']}({$trace[$i]['line']}): ";
			if(isset($t['object']) && is_object($t['object']))
				$traceString.=get_class($t['object']).'->';
			$traceString.="{$trace[$i]['function']}()\n";

			unset($trace[$i]['object']);
		}

        switch($event->getCode())
			{
				case E_WARNING:
					$type = 'PHP warning';
					break;
				case E_NOTICE:
					$type = 'PHP notice';
					break;
				case E_USER_ERROR:
					$type = 'User error';
					break;
				case E_USER_WARNING:
					$type = 'User warning';
					break;
				case E_USER_NOTICE:
					$type = 'User notice';
					break;
				case E_RECOVERABLE_ERROR:
					$type = 'Recoverable error';
					break;
				default:
					$type = 'PHP error';
			}
			$data=array(
				'code'=>500,
				'type'=>$type,
				'message'=>$event->getMessage(),
				'file'=>$event->getFile(),
				'line'=>$event->getLine(),
				'trace'=>$traceString,
				'traces'=>$trace,
			);
			if(!headers_sent()){header("HTTP/1.0 500 PHP Error");}				
			if($this->isAjaxRequest()){
                $this->displayError($event->getCode(),$event->getMessage(),$event->getFile(),$event->getLine());
            }				
            
            $this->render($data);
	}

	protected function isAjaxRequest()
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest';
	}

	protected function getExactTrace($exception)
	{
		$traces=$exception->getTrace();

		foreach($traces as $trace)
		{
			// property access exception
			if(isset($trace['function']) && ($trace['function']==='__get' || $trace['function']==='__set'))
				return $trace;
		}
		return null;
	}

	protected function render( $data )
	{
		$data['time']=time();
        include(FV_ROOT . "/framework/Exception/exception.php");
	}

	protected function argumentsToString($args)
	{
		$count=0;
		foreach($args as $key => $value)
		{
			if(is_object($value)){$args[$key] = get_class($value);}				
			else if(is_bool($value)){$args[$key] = $value ? 'true' : 'false';}				
			else if(is_string($value)){
				if(strlen($value)>10)
					$args[$key] = '"'.substr($value,0,10).'..."';
				else
					$args[$key] = '"'.$value.'"';
			}
			else if(is_array($value)){$args[$key] = 'array';}                
			else if($value===null){$args[$key] = 'null';}				
			else if(is_resource($value)){$args[$key] = 'resource';}			
		}
		$out = implode(", ", $args);
		return $out;
	}

	protected function renderSourceCode($file,$errorLine,$maxLines)
	{
		$errorLine--;	// adjust line number to 0-based from 1-based
		if($errorLine<0 || ($lines=@file($file))===false || ($lineCount=count($lines))<=$errorLine)
			return '';

		$halfLines=(int)($maxLines/2);
		$beginLine=$errorLine-$halfLines>0 ? $errorLine-$halfLines:0;
		$endLine=$errorLine+$halfLines<$lineCount?$errorLine+$halfLines:$lineCount-1;
		$lineNumberWidth=strlen($endLine+1);

		$output='';
		for($i=$beginLine;$i<=$endLine;++$i)
		{
			$isErrorLine = $i===$errorLine;
            $code=sprintf("<span class=\"ln".($isErrorLine?' error-ln':'')."\">%0{$lineNumberWidth}d</span> %s",$i+1, str_replace("\t",'    ',$lines[$i]   ));
			if(!$isErrorLine)
				$output.=$code;
			else
				$output.='<span class="error">'.$code.'</span>';
		}
		return '<div class="code"><pre>'.$output.'</pre></div>';
	}

}
