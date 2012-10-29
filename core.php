<?


/************ Check the length of a string ****************/
// $item			= name of the string you want to check
// $length			= length of the string you want to check

function checkLength($item, $length){
	if(strlen($item)<=$length){
		return false;
	} else {
		return true;
	}
}


/************ Clean unformatted text ****************/
// creates new lines, decodes entities and strips c-slashes from database content

function cleanUp($data){
	$content = stripcslashes($data);
	return $content;
}

/************ Create an array from database query results ****************/
// $value 			= the array value (should coincide with a database column)
// $label 			= the array label (should coincide with a database column)
// $table 			= the database table
// $condition 		= the query conditional, typically the where statement
// sample:			createArray('user_id', 'username', 'users', "user_type = 'admin'");
// returns:			array("1"=>"test_user", "2"=>"test_admin2");

function createArray($value, $label, $table, $condition){
	
	$a	= array();
	$b	= array();
	
	$query 	= "select $value, $label from $table";
	
	if(!empty($condition)){
		$query .= " where $condition";
	}
	
	// $query .= " order by $label asc";
	// echo $query;
	
	$result = mysql_query($query);
	$number = @mysql_num_rows($result);
	
	if($number > 0){
		while($d = mysql_fetch_assoc($result)){
		
			$value_index = stripcslashes($d["$value"]);
			$label_index = stripcslashes($d["$label"]);
			
			$a[] .= $value_index;
			$b[] .= $label_index;
		}
		
	}
	
	$c = array_combine($a, $b);	
			
	return $c;
}


/************** FORM FIELD GENERATORS using Twitter Bootstrap **************/

function formButton($field_id, $label, $class = 'btn'){
	$html = null;
	$html .= "<div id='parent_$field_id' class='control-group'>";
	$html .= "<input type='submit' name='$field_id' value='$label' class='$class' />";
	$html .= "</div>";
	
	return $html;
	
}


function formCheckBoxes($field_id, $label, $array, $value, $error, $error_message, $span = 'span3'){
	if($error == true):
		$html  = "<div id='parent_$field_id' class='control-group error'>";
	else:
		$html  = "<div id='parent_$field_id' class='control-group'>";
	endif;

	$html .= "<label class='control-label' for='$field_id'>$label</label>";
	$html .= "<div class='controls'>";
	
    if(is_array($array)){
	    foreach($array as $k => $v){
	    	
			$string .= "<label class='checkbox'>";
			$string .= "<input type='checkbox' name='".$field_id."[]' value='$k'>";
			$string .= $v;
			$string .= "</label>";
	    		
	    }
	}

	$html .= $string;	

	if($error == true):
		$html .= "<span class='help-inline'>$error_message</span>";
	endif;
	
	$html .= "</div>";
	$html .= "</div>";
	
	return $html;
	
}

function formDropDown($field_id, $label, $array, $value, $error, $error_message, $span = 'span3'){

	if($error == true):
		$html  = "<div id='parent_$field_id' class='control-group error'>";
	else:
		$html  = "<div id='parent_$field_id' class='control-group'>";
	endif;

	$html .= "<label class='control-label' for='$field_id'>$label</label>";
	$html .= "<div class='controls'>";
	$html .= "<select class='$span' name='$field_id' id='$field_id'>";

	if(!empty($value)){
		$current = $value;
	} else {
		$current = '';
	}
    $string = '';
    if(is_array($array)){
	    foreach($array as $k => $v){
	    	if($current == $k){
	       	 $string .= '<option selected="selected" value="'.$k.'"'.$s.'>'.$v.'</option>'."\n";
	       	 } else {
	       	 $string .= '<option value="'.$k.'"'.$s.'>'.$v.'</option>'."\n";
	       	 }
	    }
    } else {
    	$string .= $array;
    }
	$html .= $string;	

	$html .= "</select>";
		
	if($error == true):
		$html .= "<span class='help-inline'>$error_message</span>";
	endif;
	
	$html .= "</div>";
	$html .= "</div>";
	
	return $html;
	
}

function formFileUpload($field_id, $label, $error, $error_message){
	$html = null;
	
	if($error == true):
		$html  = "<div id='parent_$field_id' class='control-group error'>";
	else:
		$html  = "<div id='parent_$field_id' class='control-group'>";
	endif;
	$html .= "<label class='control-label' for='$field_id'>$label</label>";
	$html .= "<div class='controls'>";
	$html .= "<input type='file' class='input-xlarge' name='$field_id' id='$field_id' />";
	if($error == true):
		$html .= "<span class='help-inline'>$error_message</span>";
	endif;
	$html .= "</div>";
	$html .= "</div>";
	
	return $html;
	
}


function formPassword($field_id, $label, $value,  $error, $error_message){
	$html = null;

	if($error == true):
		$html  = "<div id='parent_$field_id' class='control-group error'>";
	else:
		$html  = "<div id='parent_$field_id' class='control-group'>";
	endif;
	$html .= "<label class='control-label' for='$field_id'>$label</label>";
	$html .= "<div class='controls'>";
	$html .= "<input type='password' class='input-xlarge' name='$field_id' id='$field_id' value='$value' />";
	if($error == true):
		$html .= "<span class='help-inline'>$error_message</span>";
	endif;
	$html .= "</div>";
	$html .= "</div>";
	
	return $html;
	
}

function formTextArea($field_id = 'description', $label, $value, $error, $error_message){
	if($error == true):
		$html  = "<div id='parent_$field_id' class='control-group error'>";
	else:
		$html  = "<div id='parent_$field_id' class='control-group'>";
	endif;
	$html .= "<label class='control-label' for='$field_id'>$label</label>";
	$html .= "<div class='controls'>";
	$html .= "<textarea name='$field_id' class='input-xlarge' id='textarea' rows='3'>$value</textarea>";
	if($error == true):
		$html .= "<span class='help-inline'>$error_message</span>";
	endif;
	
	$html .= "</div>";
	$html .= "</div>";
	
	return $html;
	
}

function formTextBox($field_id, $label, $value, $error, $error_message){
	$html = null;
	
	if($error == true):
		$html  = "<div id='parent_$field_id' class='control-group error'>";
	else:
		$html  = "<div id='parent_$field_id' class='control-group'>";
	endif;
	$html .= "<label class='control-label' for='$field_id'>$label</label>";
	$html .= "<div class='controls'>";
	$html .= "<input type='text' class='input-xlarge' name='$field_id' id='$field_id' value='$value' />";
	if($error == true):
		$html .= "<span class='help-inline'>$error_message</span>";
	endif;
	$html .= "</div>";
	$html .= "</div>";
	
	return $html;
	
}

function formTextBoxReadOnly($field_id, $label, $value, $error, $error_message){
	$html = null;
	
	if($error == true):
		$html  = "<div id='parent_$field_id' class='control-group error'>";
	else:
		$html  = "<div id='parent_$field_id' class='control-group'>";
	endif;
	$html .= "<label class='control-label' for='$field_id'>$label</label>";
	$html .= "<div class='controls'>";
	$html .= "<input readonly='readonly' type='text' class='input-xlarge' name='$field_id' id='$field_id' value='$value' />";
	if($error == true):
		$html .= "<span class='help-inline'>$error_message</span>";
	endif;
	$html .= "</div>";
	$html .= "</div>";
	
	return $html;
	
}

/************ Returns date in Month Day, Year format ****************/
function newDate($date){
	$new = date('M d, Y', $date);
	return $new;
}



/************ Returns date in Month Day, Year and time format ****************/
function newDateTime($date){
	$new = date('M d, Y, g:i A', $date);
	return $new;
}


/************ MySQL Delete Query ****************/
//$conditions			= array containing the conditions you typically place in a mysql statement
// sample conditions:	$conditions = array("id"=>$id);
// $table				= the database table this data should be updated

function queryDelete($conditions, $table, $process = true) { 

	if (!is_array($conditions)) { die("Delete failed"); } 
	$sql = "DELETE FROM $table"; 
	$sql .= " WHERE ";
	foreach($conditions as $c => $d){
    	$sql .= "$c = '$d' AND ";
    }
	$sql = substr($sql, '0', -4);

	if($process == false){
		echo "<br />".$sql;	
	} else {
		$run = mysql_query($sql);
	}

	if($run){
		return true;
	} else {
		return false;
	}
} 

/************ MySQL Insert Query ****************/
// $info			= array containing the database column and value to be inserted
// sample array:	$info = array("column"=>$value, "column2"=>$value2);
// $table			= the database table this data should be inserted

function queryInsert($info, $table, $process = true) { 

	if (!is_array($info)) { die("Insert failed"); } 
	$sql = "INSERT INTO ".$table." ("; 
	for ($i=0; $i<count($info); $i++) { 
		$sql .= key($info); 
		if ($i < (count($info)-1)) { 
			$sql .= ", "; 
		} else {
			$sql .= ") "; 
		}
		next($info); 
	} 
	
	reset($info); 
	$sql .= "VALUES ("; 
	for ($j=0; $j<count($info); $j++) { 
		$sql .= "'".current($info)."'"; 
		if ($j < (count($info)-1)) { 
		   $sql .= ", "; 
		} else { 
			$sql .= ") "; 
		}
		next($info); 
	} 
	
	if($process == false){
		echo "<br />".$sql;	
	} else {
		$run = mysql_query($sql);
	}

	if($run){
		return true;
	} else {
		return false;
	}
} 

/************ MySQL Update Query ****************/
// $array				= array containing the database column and value to be inserted
// sample array:		$info = array("column"=>$value, "column2"=>$value2);
//$conditions			= array containing the conditions you typically place in a mysql statement
// sample conditions:	$conditions = array("id"=>$id);
// $table				= the database table this data should be updated

function queryUpdate($array, $conditions, $table, $process = true) { 

	if (!is_array($array)) { die("Insert failed"); } 
	$sql = "UPDATE $table SET "; 
	foreach($array as $k => $v){
    	$sql .= "$k = '$v', ";
    }
	
	$sql = substr($sql, '0', -2);
	$sql .= " WHERE ";

	foreach($conditions as $c => $d){
    	$sql .= "$c = '$d', ";
    }

	$sql = substr($sql, '0', -2);
	
	if($process == false){
		echo "<br />".$sql;	
	} else {
		$run = mysql_query($sql);
	}
		
	if($run){
		return true;
	} else {
		return false;
	}
} 

/************ MySQL Insert Query ****************/
// $data			= the database column you want to return
// $table			= the database table this data should be inserted
// $condition		= the query condition
// sample:			returnData('column', 'table', "id = '$id'")

function returnData($data, $table, $condition){
	$q = "select $data from $table where $condition";
	$result = mysql_query($q);
	$number = @mysql_num_rows($result);
	if($number>0){
		while($x = mysql_fetch_assoc($result)){
			$name = stripcslashes($x["$data"]);
			return $name;
		}
	}
}

/************ Cleans content for database input ****************/
// a quicker way to use mysql_real_escape_string

function sanitize($value){
	return mysql_real_escape_string($value);
}

/************ Returns the total sum of a select group of rows ****************/
function sumNumbers($column, $table, $condition){
	
	$sql 	= "select sum($column) from $table where $condition";
	$result = mysql_query($sql);
	while ($num = mysql_fetch_assoc($result)):
		$total = $num['sum(num_hours)'];
	endwhile;
	
	if(empty($total) || $total == 0):
		$total = 0;
	endif;
	
	return $total;
}

/************ Returns the total number of rows ****************/
function totalNumber($table, $condition){
	$query = "select * from $table where $condition";
	$result = mysql_query($query);
	$number = mysql_num_rows($result);
	
	return $number;
}

function createSlug($string){
   
   $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
   $slug = $slug."-".time();
   $slug = strtolower($slug);
   
   return $slug;
}

/************ Truncates the length of a string ****************/
function truncateText($text, $max=100, $append='&hellip;') {
       if (strlen($text) <= $max) return $text;
       $out = substr($text,0,$max);
       if (strpos($text,' ') === FALSE) return $out.$append;
       return preg_replace('/\w+$/','',$out).$append;
}


/************ UPLOAD SCRIPT ****************/
// $file_id 	= field name of the file
// $folder 		= destination folder
// $types		= acceptable file types 
function uploadStuff($file_id, $folder="", $types="") {
    if(!$_FILES[$file_id]['name']) return array('','No file specified');

    $file_title = $_FILES[$file_id]['name'];
    //Get file extension
    $ext_arr = split("\.",basename($file_title));
    $ext = strtolower($ext_arr[count($ext_arr)-1]); //Get the last extension

    //Not really uniqe - but for all practical reasons, it is
    $uniqer = substr(md5(uniqid(rand(),1)),0,5);
    $file_name = $uniqer . '_' . $file_title;//Get Unique Name

    $all_types = explode(",",strtolower($types));
    if($types) {
        if(in_array($ext,$all_types));
        else {
            $result = "'".$_FILES[$file_id]['name']."' is not a valid file."; //Show error if any.
            return array('',$result);
        }
    }

    //Where the file must be uploaded to
    if($folder) $folder .= '/';//Add a '/' at the end of the folder
    $uploadfile = $folder . $file_name;

    $result = '';
    //Move the file from the stored location to the new location
    if (!move_uploaded_file($_FILES[$file_id]['tmp_name'], $uploadfile)) {
        $result = "Cannot upload the file '".$_FILES[$file_id]['name']."'"; //Show error if any.
        if(!file_exists($folder)) {
            $result .= " : Folder don't exist.";
        } elseif(!is_writable($folder)) {
            $result .= " : Folder not writable.";
        } elseif(!is_writable($uploadfile)) {
            $result .= " : File not writable.";
        }
        $file_name = '';
        
    } else {
        if(!$_FILES[$file_id]['size']) { //Check if the file is made
            @unlink($uploadfile);//Delete the Empty file
            $file_name = '';
            $result = "Empty file found - please use a valid file."; //Show the error message
        } else {
            chmod($uploadfile,0777);//Make it universally writable.
        }
    }

    return array($file_name,$result);
}



function sendEmail($template, $to_array, $from_array, $subject, $content, $text_content, $replace_array, $process = true){
	
	// $to_array = all emails and names of the recipients
	// from_array = single array item for from
	// subject = grab subject
	// content = content that should be inside the template
	// $replace_array = all of the variables and text to be replaced
	// process = false or true, sends the actual email
	
	
	require_once('class.phpmailer.php');
	$mail             = new PHPMailer(); // defaults to using php "mail()"
	$mail->IsSendmail(); // telling the class to use SendMail transport
	$body             = file_get_contents($template);
	$body             = str_replace("[\]",'',$body);
	
	foreach($replace_array as $item=>$text){
		$content = str_replace($item, $text, $content);
		$text_content = str_replace($item, $text, $text_content);
	}
	$template_replace = array("{TEMPLATE_NAME}"=>$template_name, "{SITE}"=>SITE, "{EMAIL_BODY_HERE}"=>$content);
	foreach($template_replace as $item=>$text){
		$body = str_replace($item, $text, $body);
		$text_content = str_replace($item, $text, $text_content);
	}
	
	foreach($from_array as $email=>$name){
		$mail->AddReplyTo($email,$name);
		$mail->SetFrom($email, $name);
		$mail->AddReplyTo($email, $name);
	}
	foreach($to_array as $email=>$name){
		$mail->AddAddress($email, $name);
	}
		
	$mail->Subject    = $subject;
	$mail->AltBody    = $text_content;
	$mail->MsgHTML($body);
	
	if($process == true){
		if(!$mail->Send()) {
		  return false;
		} else {
		  return true;
		}
	} else {
		echo $body;
		echo "<br />";
		echo $text_content;
	}
	

}





?>