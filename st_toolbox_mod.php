<?php
/* - - - - - - - - - - - - - - - - -
 * [PROGRAM NAME]		string toolbox [MODIFIED - see https://github.com/jyerdon/st_toolbox for original]
 * [PROGRAM VERSION]	1.0.0
 * [LICENSE]			GPLv2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * [AUTHOR]				jyerdon (jyerdon@gmail.com)
 * - - - - - - - - - - - - - - - - -
*/

if(!defined('__DEBUG')) {									//define a DEBUG constant
	define('__DEBUG', TRUE); }
	
if(__DEBUG)													//set a DEBUG condition
{
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
}

/* *****************
 * @name st_serialize
 * @param 	$data			- 	command to maybe serialize
 * 			$command		-	serialize/unserialize data
 *
 * @notes	[SAMPLE COMMAND STRINGS]	
 *			"s:numeric-c:alpha"  
 *				Will strip the string of everything but numbers and then convert that to a string
 *
 *			"s:whitespace:left-s:alpha-c:locase"
 *				remove whitespaces from the left portion of the string, then
 *				strip everything but alpha characters, then
 *				lowcase entire string
 *
 *			"s:url-c:locase"
 *				strip all but valid URL characters, then
 *				lowcase entire string
*/
function st_serialize($data, $command = 'serialize')
{
	if(__DEBUG) {
		error_log('DEBUG:STRING TOOL::inside st_serialize', 0); }
		
	if(is_array($data) && $command == 'serialize') {
		return serialize($data); }
	elseif(is_array($data) && $command == 'unserialize') {
		return unserialize($data); }
	elseif(is_string($command) && strlen($data) > 3) {
		return $data; }
	else {
		return 'ERR: Passed Parameters invalid'; }
}


/* *****************
 * @name st_string
 * @param 	$command_string	-  	is an array or a delimited string; provides the chain of commands to be parsed by the
 *								string toolbox processor
 *			$data_string	- 	actual data to transform
 *
 * @notes	[SAMPLE COMMAND STRINGS]	
 *			"s:numeric-c:alpha"  
 *				Will strip the string of everything but numbers and then convert that to a string
 *
 *			"s:whitespace:left-s:alpha-c:locase"
 *				remove whitespaces from the left portion of the string, then
 *				strip everything but alpha characters, then
 *				lowcase entire string
 *
 *			"s:url-c:locase"
 *				strip all but valid URL characters, then
 *				lowcase entire string
*/
function st_string($command_string, $data_string)
{
	if(__DEBUG) {
		error_log('DEBUG:STRING TOOL::inside st_string - command '.st_serialize($command_string), 0); }
		
	// ---- TODO ------
	//strip out extra delimiters and guard against bad commands
	//right now we're counting on the programmer to use this correctly
	// ----------------
	if(is_string($command_string) && strlen($command_string) > 0) {					//if is a string and longer than 0 characters
		$commands = explode("-", strtolower($command_string)); }						//split command string into an array based on the delimiter and make sure it's lowcase
	elseif(is_array($command_string)) {												//else if this an array
		$commands = array_map('strtolower', $command_string); }							//locase the array
	else {																			//else
		$error = TRUE; }																	//set an error
	
	$value = FALSE;																	//instantiate the $value container
	
	if(isset($error) && !$error) {													//if the error is set to TRUE
		return 'ERR : Invalid Command String'; }										//return an error message
	else																			//else
	{
		foreach($commands as $command)													//for each command in the command array
		{
			$function = explode(":", $command);												//explode into array for switch and action
		
			if($value) {																	//if $value is no longer TRUE, which should only be true upon initialization
				$data_string = $value; }														//set $data_string to $value

			if($function[0] == 's') 
			{																				//SANITIZATION
				if(strlen($function[1]) > 0) {													//if the action is NOT empty
					$value = st_sanitize($data_string, $function[1]); }								//run the command with all the variables
				else {																			//otherwise
					$error = TRUE; }																//populate an error
			}
			elseif($function[0] == 'v')
			{																				//VALIDATION
				if(strlen($function[1]) > 0) {	echo $value;									//if the action is NOT empty
					$value = st_validate($data_string, $function[1], $function[2]); }				//run the command with all the variables
				else {																			//otherwise
					$error = TRUE; }																//populate an error
			}
			elseif($function[0] == 'c')														//CONVERSION
			{	
				if(strlen($function[1]) > 0) 													//if the action is NOT empty
				{													
					if(!isset($function[2])) {
						$value = st_convert($data_string, $function[1]); }						//run the command with all the variables
					else {
						$value = st_convert($data_string, $function[1], $function[2]); }						//run the command with all the variables
				}
			}
			else {																			//otherwise
					$error = TRUE; }																//populate an error	
		}
	
	if(isset($error) && !$error) {														
		return 'ERR: Invalid Command String'; }
	else {
		return $value;	}																//send back the value
	}	
}

/* *****************
 * @name st_convert
 * @param 	$action - provides main switch 
 *			$data	- actual data to transform
 *			$filter
*/
function st_convert($string, $action = 'alpha', $filter = FALSE)
{
	if(__DEBUG) {
		error_log('DEBUG:STRING TOOL::inside st_convert', 0); }
		
	if(is_string($string) && strlen($string) < 1)	{			//check to make sure this has at least one character
		return 'ERR : string is not a valid length'; }
	elseif(is_array($string) && empty($string)) {				//check to make sure array is not empty
		return 'ERR : array is not valid'; }

	switch($action)
	{
		default:
		case 'alpha':					//alphabet only
			$value = (string)$string;
			break;
			
		case 'numeric':					//numeric only
			$value = (int)$string;
			break;
			
		case 'serialize':				//array to serialized string
			$value = serialize($string);
			break;
			
		case 'delimited':				//array to delimited string
			if(!$filter) {					//make sure the filter exists; if it doesn't, set a default of comma
				$filter = ','; }
			$value = implode($filter, $string);
			break;
			
		case 'locase':					//string to locase
			$value = strtolower($string);
			break;		
			
		case 'upcase':					//string to upcase
			$value = strtoupper($string);
			break;	
			
		case 'ucfirst':					//first char of sentence to upcase
			$value = ucfirst($string);
			break;	
			
		case 'lcfirst':					//first char of sentence to locase
			$value = lcfirst($string);
			break;					
	}
	
	return $value;
}

/* *****************
 * @name st_validate
 * @param 	$action - provides main switch 
 *			$data	- actual data to transform
 *			$filter	- any filter to be performed within the action
*/
function st_validate($string, $action = 'alphanumeric', $filter = 'Aa')
{	
	if(__DEBUG) {
		error_log('DEBUG:STRING TOOL::inside st_validate', 0); }
		
	if(!isset($string) && is_string($string) && strlen($string) < 1)	{				//check to make sure this has at least one character
		echo 'ERR : string is not a valid length'; }
	else {
		$charcount = strlen($string); }

	switch($action)
	{
		case 'alpha':					//alphabet only
			if(preg_replace("/[^A-Za-z]/","",$string)) {
				$value = TRUE; }
			else {
				$value = FALSE; }
			break;
			
		case 'numeric':					//numeric only
			if(filter_var($string, FILTER_VALIDATE_INT)) {
				$value = TRUE; }
			else {
				$value = FALSE; }
			break;
		
		default:						
		case 'alphanumeric':			//alphanumeric
			if(preg_replace("/[^A-Za-z0-9]/","",$string)) {
				$value = TRUE; }
			else {
				$value = FALSE; }
			break;
		
		case 'email':					//all valid email address characters all valid URL characters
			if(filter_var($string, FILTER_VALIDATE_EMAIL)) {
				$value = TRUE; }
			else {
				$value = FALSE; }
			break;
			
		case 'url':						//all valid email address characters
			if(filter_var($string, FILTER_VALIDATE_URL)) { //NOTE: This is not compatible with all valid URLs, such as phoen numbers. Next version will have a custom addition
				$value = TRUE; }
			else {
				$value = FALSE; }
			break;
			
		case 'count':					//count characters in string
			$value = $charcount;
			break;								
	}
	
	return $value;
}

/* *****************
 * @name st_sanitize
 * @param 	$action - provides main switch 
 *			$data	- actual data to transform
*/
function st_sanitize($string, $action = 'alphanumeric', $filter = 'both')
{
	if(__DEBUG) {
		error_log('DEBUG:STRING TOOL::inside st_sanitize', 0); }
		
	if(!is_string($string) || strlen($string) < 1)	{								//check to make sure this has at least one character
		return 'ERR :string is not valid'; }											//send back an error message
		
	switch($action)																	
	{
		case 'alpha':																	//alphabet only
			$result = preg_replace("/[^A-Za-z]/","",$string);
			break;
			
		case 'numeric':																	//numeric only
			$result = filter_var($string, FILTER_SANITIZE_NUMBER_INT);
			break;
			
		case 'float':																	//float only
			$result = filter_var($string, FILTER_SANITIZE_NUMBER_FLOAT);
			break;
		
		default:						
		case 'alphanumeric':															//alphanumeric
			$result = preg_replace("/[^A-Za-z0-9]/","",$string);
			break;	
								
		case 'alphanumerichyphen':														//alphanumeric and hyphen
			$result = preg_replace("/[^-A-Za-z0-9]/","",$string);
			break;
		
		case 'email':																	//all valid email address characters all valid URL characters
			$result = filter_var($string, FILTER_SANITIZE_EMAIL);
			break;
			
		case 'url':																		//all valid email address characters
			$result = filter_var($string, FILTER_SANITIZE_URL);
			break;		
			
		case 'whitespace':																//all whitespace from beginning of string
			if($filter == 'left') {															//trim from the left
				$result = ltrim($string, " "); }													//LTRIM
			elseif($filter == 'right') {													//trim from the right
				$result = rtrim($string, " "); }													//RTRIM
			elseif($filter == 'both') {														//trim from both ends
				$result = trim($string, " "); }													//TRIM
			else {																			//if there was no choice
				$result = preg_replace('/\s+/', '', $string); }													//TRIM
				
			break;				
	}
	
	return $result;
}
