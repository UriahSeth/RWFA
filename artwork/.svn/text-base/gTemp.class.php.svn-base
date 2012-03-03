<?php
/*******************************************************************************

	Class:	gTemp
	File:	gTemp.class.php
	Author:	Brian Pflugrad
	Date:	6/16/2008

	Description:
	Lightweight HTML templating language for use in rapid site development.

*******************************************************************************/
//Define constants for use in evaluate.
define("INIT", 0); //Initial run, default.
define("COND", 1); //Conditional statement, "IF".
define("LOOP", 2); //Looping statement.

class gTemp
{
	protected $input_array;
	protected $all_data;
	protected $linecount;

	function __construct($file_name)
	{
		if(file_exists($file_name))
		{
			$this->all_data = array();
			$this->input_array = file($file_name);
			$this->linecount = count($this->input_array);
			return true;
		} else {
			print("<span style='color:red; font-weight:bold;'>Input file not found!</span><br/>\n");
			return false;
		}
	}

	public function evaluate()
	{
		if(isset($this->all_data) && is_array($this->all_data) && isset($this->input_array) && is_array($this->input_array))
		{
			return($this->gEval($this->input_array, $this->all_data));
		} else {
			print("<span style='color:red; font-weight:bold;'>Input file or data error!</span><br/>\n");
			return false;
		}
	}

	/******************************************************************************
	gEval: Recursive function that evaluates an HTML template.
	By Brian Pflugrad.  3/3/2008.
	Inputs:
		$input: REQUIRED. Referenced array of input data (file data in line by line
		array form).  Each line will be popped off the array, when the array is
		empty the evaluation is complete.
		$data: OPTIONAL. Custom array of data to be used for replacements.  Array keys 
		may be embedded in HTML comments with the format {key} and will be replaced
		with the contents of the contents of the array for that key.  NOTE: If a key
		that is an array is called by this method the word "ARRAY" will be used as a
		replacement.
		$op: INTERNAL. Variable that contains the current "operation", this gets 
		changed when we recurse into a structure such as an IF (COND), or do (LOOP).
		$take: INTERNAL. Boolean variable that decides if we are taking the lines for 
		output or not.  This is switched by an <!-- ELSE --> tag.
		$alldata: INTERNAL. Workaround for scope resolution issue of the loop construct. 
		This variable will hold the entire data array when a loop is called so that
		elements in the main structure can be referenced inside loops (as long as
		the key name does not exist in the part of the array being looped over, if
		this happens the declared loop array has priority).
		$eval_else: INTERNAL. Allows us to avoid conditionals altogether while still
		recursing into them.
		$line_on: INTERNAL. Used for debugging, tells us what line an tag that didn't
		close opened on.
	Outputs:
		$result['output'] is filled with the line by line interpretation of the HTML
		template file and returned as a string.
	Supported Tags:
		NOTE: THESE TAGS MUST BE THE ONLY THING ON THE LINE!  ANYTHING ELSE ON THE
			  LINE WILL BE IGNORED!
		NOTE: You may now use SESS.<key> to access variables directly from $_SESSION.
		<!-- IF <key> --> = Data below this tag is added to the output if there
		is something in the array for that <key>.  Otherwise data below the ELSE
		is taken.
		<!-- EVEN --> = Used when looping.  Data below this tag is taken if
		the index of the array being looped over is an even number.  Used for
		alternating color schemes for blog or forum posts.  If the index is
		odd the data below ELSE is taken.
		<!-- ODD --> = Same as above except for odd numbers.
		<!-- ELSE --> = Data below the ELSE is taken if the value of IF is false.
		<!-- ENDIF --> = Terminates an IF statement (either IF <key> or EVEN/ODD)
		<!-- DO <key> --> = If the value of <key> is an array of data arrays then
		it will be looped over until exhausted.  This can be used for displaying
		large amounts of data such as forum or blog posts.
		<!-- LOOP --> = Terminates a DO tag.
	******************************************************************************/
	protected function gEval(&$input, $data = array(), $op = INIT, $take = true, $alldata = false, $eval_else = true, $line_on = 0)
	{
		$matches = array(); //Array for holding matches from preg_match().
		
		$return = array('output' => '', 'store' => array()); //Holds data to be returned.
		//Note that only the contents of output are returned at the end of processing.
		$result = false; //Holds results of recursive calls to evaluate.

		//Since array_pop happily takes items only from the END of the array we
		//need the array to be reversed when evaluate is first called.
		if($op == INIT)
		{
			$input = array_reverse($input); //Reverse the array.
			$data['self'] = $this->self();
		}

		$local_data = false; //Set local_data to false for possible loops.
		if($op == LOOP)
		{
			$alldata['x'] = 1;
			$local_data = array_pop($data); //Get first local_data...
		} else {
			$local_data = $data;
		}

		if($op == COND)
			if(isset($alldata['x']))
				unset($alldata['x']);

		$eval_temp = $eval_else; //Boolean that instructs us whether we will be switching
		//$take on ELSE lines.

		//Pop a line off $input and stop when there are none left (since $input is
		//a referenced variable pops that occur in lower levels of recursion will
		//also remove lines in every other level.  This allows us to blow straight
		//through and not have to worry about figuring out how many lines to
		//discard after an if or loop is evaluated.
		while(count($input) > 0)
		{
			$line = array_pop($input); //Get line from the array.
			//If we are LOOPing then we need to $store lines to repeat the loop.
			if($alldata !== false)
				array_push($return['store'], $line); //Add a line to store.

			//First check, see if the line is an IF line.
			if(preg_match('/<!-- IF (!)?(SESS\.)?(.+?) -->/', $line, $matches))
			{
				//If we are taking lines (meaning this is not inside an else or
				//other list of lines that should not be evaluated) then we will
				//consider the IF.  If not then we will not consider the else
				//that may appear later.
				
				if($take) //If we are taking then we need to deal with the conditional.
				//Otherwise we just make sure we aren't taking when we recurse.
				{
					if($matches[1] == '!')
						$not = false;
					else
						$not = true;
					
					if($matches[2] == 'SESS.') //Check $_SESSION instead of passed vars.
					{
						if(isset($_SESSION[$matches[3]]))
							$take_temp = ($_SESSION[$matches[3]]?$not:($not?false:true));
						else
							$take_temp = ($not?false:true);
					} else {
						//If we are in a loop we need to check in $alldata, sadly.
						if(isset($alldata) && is_array($alldata))
						{
							if(isset($local_data[$matches[3]]))
								$take_temp = ($local_data[$matches[3]]?$not:($not?false:true));
							else
								if(isset($alldata[$matches[3]]))
									$take_temp = ($alldata[$matches[3]]?$not:($not?false:true));
								else
									$take_temp = ($not?false:true);
						} else {
							if(isset($data[$matches[3]]))
								$take_temp = ($data[$matches[3]]?$not:($not?false:true));
							else
								$take_temp = ($not?false:true);
						}
					}
					unset($not);
				} else {
					$take_temp = false;
					$eval_temp = false;
				}

				//Recurse into the IF tag, the value of take will be whether
				//or not the key being evaluated is true or not.
				if($op != LOOP)
				{
					$result = $this->gEval($input, $data, COND, $take_temp, $alldata, $eval_temp, $this->linecount-count($this->input_array));
					$return['output'] .= $result['output'];
				} else {
					$result = $this->gEval($input, $local_data, COND, $take_temp, $alldata, $eval_temp, $this->linecount-count($this->input_array));
					$return['output'] .= $result['output'];
				}

				if(is_array($result['store']))
					$return['store'] = array_merge($return['store'], $result['store']);

				unset($take_temp, $result);

			//EVEN and ODD are for looping where we want alternating colors.  They behave like IF and ELSE.
			} else if(preg_match('/<!-- EVEN -->/', $line)) {
				if($alldata !== false)
				{
					if($take)
					{
						$take_temp = (count($data)%2?false:true);
					} else {
						$take_temp = false;
						$eval_temp = false;
					}

					$result = $this->gEval($input, $local_data, COND, $take_temp, $alldata, $eval_temp, $this->linecount-count($this->input_array));
					$return['output'] .= $result['output'];

					if(is_array($result['store']))
						$return['store'] = array_merge($return['store'], $result['store']);

					unset($result);
				}
			} else if(preg_match('/<!-- ODD -->/', $line)) {
				if($alldata !== false)
				{
					if($take)
					{
						$take_temp = count($data)%2;
					} else {
						$take_temp = false;
						$eval_temp = false;
					}
					$result = $this->gEval($input, $local_data, COND, $take_temp, $alldata, $eval_temp, $this->linecount-count($this->input_array));
					$return['output'] .= $result['output'];
		
					if(is_array($result['store']))
						$return['store'] = array_merge($return['store'], $result['store']);

					unset($result);
				}
			//Evaluate the ELSE tag, if applicable.
			} else if(preg_match('/<!-- ELSE -->/', $line)) {
				//If we are working with a CONDitional statement then we should
				//check if we are evaluating ELSE.
				if($op == COND && $eval_else)
				{
					//Swap $take from true to false or false to true.
					$take = $take?false:true;
					$eval_temp = $eval_else;
				}
			//Evaluate the ENDIF tag.
			} else if(preg_match('/<!-- ENDIF -->/', $line)) {
				//If we are in a CONDitional the ENDIF is the end of processing.
				if($op == COND)
					return $return;

			//Evaluate the DO loop.
			//The DO loop will simply iterate through an array similar to the way
			//a for($x = 0; $x < count($array); $x++){} would...
			} else if(preg_match('/<!-- DO (SESS\.)?(.+?) -->/', $line, $matches)) {
				$loop_array = array();
				if($matches[1]=='SESS.')
				{
					if(isset($_SESSION[$matches[2]]) && is_array($_SESSION[$matches[2]]) && count($_SESSION[$matches[2]]) > 0)
						$loop_array = array_reverse($_SESSION[$matches[2]]);
				} else {
					if(isset($local_data[$matches[2]]) && is_array($local_data[$matches[2]]) && count($local_data[$matches[2]]) > 0)
						$loop_array = array_reverse($local_data[$matches[2]]);
				}
				
				//We need to recurse into the loop even if we aren't taking lines.
				//We can then decide whether to take the lines that are within the loop...
				$result = $this->gEval($input, $loop_array, LOOP, ($take && $loop_array), $data, $eval_else, $this->linecount-count($this->input_array));
				$return['store'] = array_merge($return['store'], $result['store']);
				$return['output'] .= $result['output'];
				//Note that $data becomes the contents of the sub-array and
				//$alldata retains the entire contents of $data.
				unset($result, $loop_array);
			//Evaluate the closing LOOP tag.
			} else if(preg_match('/<!-- LOOP -->/', $line)) {
				//We only want to do this if we are LOOPing...
				if($op == LOOP)
				{
					//If we are not past the final element of the array...
					if(count($data) > 0 && $take)
					{
						//Place the storage array back on the stack (must be
						//reversed to accomplish this).
						$input = array_merge($input, array_reverse($return['store']));
						$return['store'] = array(); //Reset $store.
						$local_data = array_pop($data);
						$alldata['x']++;
					} else {
						return $return; //Otherwise we have come to the end of the
						//loop and should return what we have.
					}
				}
			} else if(preg_match('/<!-- MOD ([0-9]+?) -->/', $line, $matches)) {
				if($take)
				{
					$take_temp = $alldata['x']%$matches[1]==0?true:false;
				} else {
					$take_temp = false;
					$eval_temp = false;
				}

				$result = $this->gEval($input, $local_data, COND, $take_temp, $alldata, $eval_temp, $this->linecount-count($this->input_array));
				$return['output'] .= $result['output'];

				if(is_array($result['store']))
					$return['store'] = array_merge($return['store'], $result['store']);

				unset($result);
			//Ok, now to any other line we might encounter!
			} else {
				if($take) //We don't do anything if we aren't taking lines.
				{
					//Loop while there are things to replace.
					while(preg_match('/{(SESS\.)?([a-zA-Z0-9_]+?)}/', $line, $matches))
					{
						$replacement = "";
						if($matches[1]=='SESS.')
						{
								if(isset($_SESSION[$matches[2]]))
									$replacement = $_SESSION[$matches[2]];
								else
									$replacement = false;
						} else {
							if(isset($alldata) && is_array($alldata)) //Check the right places and make the
							//replacement string.
							{
								if(isset($local_data[$matches[2]]))
									$replacement = $local_data[$matches[2]];
								else
									if(isset($alldata[$matches[2]]))
										$replacement = $alldata[$matches[2]];
									else
										$replacement = false;
							} else {
								if(isset($data[$matches[2]]))
									$replacement = $data[$matches[2]];
								else
									$replacement = false;
							}
						}
						//Replace the occurance with the value of $replacement.
						$line = preg_replace('/{' . ($matches[1]=='SESS.'?'SESS.':'') . $matches[2] . '}/', is_array($replacement)?'ARRAY':$replacement, $line);
						unset($matches);
					}
					//Append the newly finished line to $output.
					//Use rtrim to avoid double linebreaking but to preserve indentation.
					$return['output'] .= rtrim($line) . "\n";
				}
			}
		}
		//We are done with the last line, time to unset our temporary variables
		//and return $output to be printed to the screen.
		unset($matches, $eval_temp, $x, $store, $replacement);
		
		//If we get here in a CONDitional or LOOP then we have an unclosed tag.
		if($op == COND)
		{
			$return['output'] .= '<span style="color:red; font-weight:bold;">UNCLOSED IF TAG! (Line ' . $line_on . ')</span><br/>' . "\n";
			return $return;
		} else if($op == LOOP) {
			$return['output'] .= '<span style="color:red; font-weight:bold;">UNCLOSED LOOP TAG! (Line ' . $line_on . ')</span><br/>' . "\n";
			return $return;
		}

		//Final return.  We should only get here in mode INIT.
		return $return['output'];
	}
	//END OF EVALUATE.

	//self: Returns or prints the value of $_SERVER['PHP_SELF'].
	public function self()
	{
		return($_SERVER['PHP_SELF']);
	}
	//END OF SELF

	public function set($data)
	{
		if(is_array($data))
			$this->all_data = array_merge($this->all_data, $data);
		else if (is_string($data))
			$this->all_data[$data] = true;
		else
			return false;
		
		return true;
	}

	public function del($data)
	{
		if(is_array($data))
			foreach($data as $key)
				unset($this->all_data[$key]);
		else if(is_string($data))
			unset($this->all_data[$data]);
		else
			return false;
		
		return true;
	}

	public function get($data)
	{
		if(isset($this->all_data[$data]))
			return($this->all_data[$data]);
		else
			return false;
	}

	//assign_whitelist: Assigns data field based on the type passed.
	protected function assign_whitelist($item, $session=false, &$data_array)
	{
		switch($data_array[$item]['type'])
		{
			case 'alnum':
				$data_array[$item]['v'] = ctype_alnum($this->wl_get($item, $session)) ? $this->wl_get($item, $session) : false;
			break;
			case 'alpha':
				$data_array[$item]['v'] = ctype_alpha($this->wl_get($item, $session)) ? $this->wl_get($item, $session) : false;
			break;
			case 'cntrl':
				$data_array[$item]['v'] = ctype_cntrl($this->wl_get($item, $session)) ? $this->wl_get($item, $session) : false;
			break;
			case 'digit':
				//Allows for negatives (digit failes on -).
				$data_array[$item]['v'] = is_numeric($this->wl_get($item, $session)) ? $this->wl_get($item, $session) : false;
			break;
			case 'graph':
				$data_array[$item]['v'] = ctype_graph($this->wl_get($item, $session)) ? $this->wl_get($item, $session) : false;
			break;
			case 'lower':
				$data_array[$item]['v'] = ctype_lower($this->wl_get($item, $session)) ? $this->wl_get($item, $session) : false;
			break;
			case 'print':
				$data_array[$item]['v'] = ctype_print($this->wl_get($item, $session)) ? $this->wl_get($item, $session) : false;
			break;
			case 'punct':
				$data_array[$item]['v'] = ctype_punct($this->wl_get($item, $session)) ? $this->wl_get($item, $session) : false;
			break;
			case 'space':
				$data_array[$item]['v'] = ctype_space($this->wl_get($item, $session)) ? $this->wl_get($item, $session) : false;
			break;
			case 'upper':
				$data_array[$item]['v'] = ctype_upper($this->wl_get($item, $session)) ? $this->wl_get($item, $session) : false;
			break;
			case 'xdigit':
				$data_array[$item]['v'] = ctype_xdigit($this->wl_get($item, $session)) ? $this->wl_get($item, $session) : false;
			break;
			case 'literal':
				$data_array[$item]['v'] = $this->wl_get($item, $session); //Just return it for multiline fields.
			break;
			Default:
				print("<span style='color:red; font-weight:bold;'>Erroneous type for '$item': '{$data_array[$item]['type']}'</span></br>");
				$data_array[$item]['v'] = "ERRONEOUS TYPE"; //Inform user ungracefully if an error has occured.
			break;
		}
	}

	//get: Simple wrapper for get_SESSION and get_REQUEST, defaults to get_REQUEST.
	protected function wl_get($what, $from=false)
	{
		if($from)
			return htmlspecialchars($this->get_SESSION($what));
		else
			return htmlspecialchars($this->get_REQUEST($what));
	}

	//get_REQUEST: Retreives the contents of $_REQUEST and returns it with slashes added or false if it does not exist.
	protected function get_REQUEST($field)
	{
		  if(!isset($_REQUEST[$field]) || $_REQUEST[$field] == "")
			return false;

		if (!get_magic_quotes_gpc()) 
			return $_REQUEST[$field];
		
		if (ini_get('magic_quotes_sybase')) 
			return str_replace("''", "'", $_REQUEST[$field]); 
		
		return stripslashes($_REQUEST[$field]);
	}

	//get_SESSION: Retreives the contents of $_SESSION and returns it with slashes added or false if it does not exist.
	protected function get_SESSION($field)
	{
		  if(!isset($_SESSION[$field]) || $_SESSION[$field] == "")
			return false;
		
		return $_SESSION[$field];
	}


	//get_whitelist:  Gets data from $_REQUEST (using get_REQUEST) and fills the passed array.
	//WARNING: This function has no return value, the array passed will be filled with the data.
	//Takes a two dimensional array the first dimension is the name of the value to be gotten from $_REQUEST (from a form or whatever) the second dimension should have 'type' and 'value.  'type' describes what character types are permitted, 'v' (for value) is where the data goes if the assignment is permitted.  False is assigned otherwise.  ALL ctypes may be used for 'type' (alnum, alpha, digit, lower, print, etc).
	//get_whitelist now can be used to populate the list from the $_SESSION variable.  Also, a single field may be populated (allowing you to fill one variable from session and others from $_REQUEST. 
	protected function get_whitelist(&$data_array, $session=false, $field="")
	{
		if($field != "")
		{
			$this->assign_whitelist($field, $session, $data_array);    
		} else {
			//Collect $_REQUEST data and fill array based on a white list.
			foreach($data_array as $item => $value)
			{    
				$this->assign_whitelist($item, $session, $data_array);    
			}
		}
	}


	public function whitelist()
	{
		if(!is_integer(func_num_args()/2))
		{
			print("<span style='color:red; font-weight:bold;'>Illegal number of arguments (" . func_num_args() . ") in whitelist!</span><br/>");
			return false;
		} else {
			$output = array();
			$args = func_get_args();

			for($x = 0; $x < func_num_args(); $x = $x+2)
			{
				$output[$args[$x]] = array('type' => $args[$x+1], 'v' => "");
			}
			unset($args);
			$this->get_whitelist($output);
			foreach($output as $key => $item)
			{
				$output[$key] = $output[$key]['v'];
			}
			$this->set($output);
			return($output);
		}
	}

	function __destruct()
	{
		unset($this->input_array, $this->all_data);
	}
}
?>