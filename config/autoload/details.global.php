<?php 
date_default_timezone_set('America/Argentina/Buenos_Aires');
//$isTest = (strpos($_SERVER['SERVER_NAME'], 'ar') === false);

return [
	'isTest'			  => true, //$isTest,
	'debug'				  => false,
	'version'			  => '3.0.2.0531', //MMDD
	'name'				  => 'OSPIQ y P Z-C',

	'exceptionEmail'	  => 'hola@juliangorge.com.ar',
	'exceptionEmailPwd'   => 'G07tqhRpKexWQO2',
	'exceptionHost'	 	  => 'mail.juliangorge.com.ar',
	'exceptionHostPort'	  => 587,
	'exceptionEmailTo'	  => 'juliangorge@hotmail.com',
	'emailErrors'		  => 'hola@juliangorge.com.ar',
];
