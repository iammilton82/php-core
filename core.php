<?

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




/************ Creates a select box from an array ****************/
// $array			= array options
// sample array:	$array = array("name"=>"value", "name2"=>"value2");
// $preselect		= you can pass in a preselected value

function showSelectOptions($array, $preselect)
{
	if(!empty($preselect)){
		$current = $preselect;
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
    return $string;
}

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


/************ Return Database Data By ID ****************/
// $data			= the database columns you want to return
// $table			= the database table
// $id				= id of the row
// sample: 			getDBData('id', 'table_name', 5);
 
function getDBData($data, $table, $id){
	$q = "select $data from $table where id = '$id'";
	$result = mysql_query($q);
	if(mysql_num_rows($result)>0){
		while($x = mysql_fetch_assoc($result)){
			$name = $x["$data"];
			return $name;
		}
	}
}

/************ Return Database Data By Conditional ****************/
// $data			= the database columns you want to return
// $table			= the database table
// $condition		= this is typically what you put into the where statement in a mysql statement

// sample: 			getDBData2('id', 'table_name', "name = '$string'");
function getDBData2($data, $table, $condition){
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



/************ MySQL Insert Query ****************/
// $info			= array containing the database column and value to be inserted
// sample array:	$info = array("column"=>$value, "column2"=>$value2);
// $table			= the database table this data should be inserted

function insertQuery($info, $table) { 

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
	
	// echo $sql;	
	
	$run = mysql_query($sql); 
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

function updateQuery($array, $conditions, $table) { 

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
	
	// echo "<br />".$sql;
		
	$run = mysql_query($sql); 
	if($run){
		return true;
	} else {
		return false;
	}
} 



/************ MySQL Delete Query ****************/
//$conditions			= array containing the conditions you typically place in a mysql statement
// sample conditions:	$conditions = array("id"=>$id);
// $table				= the database table this data should be updated

function deleteQuery($conditions, $table) { 

	if (!is_array($conditions)) { die("Delete failed"); } 
	$sql = "DELETE FROM $table"; 
	$sql .= " WHERE ";
	foreach($conditions as $c => $d){
    	$sql .= "$c = '$d' AND ";
    }
	$sql = substr($sql, '0', -4);
	
	// echo "<br />".$sql;
	
	$run = mysql_query($sql); 
	if($run){
		return true;
	} else {
		return false;
	}
} 

/************ Clean unformatted text ****************/
// creates new lines, decodes entities and strips c-slashes from database content

function cleanUp($data){
	$content = nl2br($data);
	$content = html_entity_decode($content);
	$content = stripcslashes($content);
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


/************ BUILD CHECK BOXES FROM ARRAY ************/
// $name 			= field name (typically sent to $_POST, no spaces or special characters)
// $array 			= array of options
// sample array:		$info = array("column"=>$value, "column2"=>$value2);
// $preselect		= preselect value (optional)

function buildCheckBoxes($name, $array, $preselect){
	foreach($array as $value => $label):
		
		if(in_array($value, $preselect)):
			$checked = 'checked';
		else:
			$checked = 'rel = "notchecked"';
		endif;		
		
		$checkbox .= "<div><input id='$name' type='checkbox' name='".$name."[]' value='$value' $checked> $label</div>";
	
	endforeach;
	
		return $checkbox;
	
}


/************ BUILD CHECK BOXES FROM ARRAY ************/
function buildRadioButtons($name, $array, $preselect){
	foreach($array as $value => $label):
		
		if(in_array($value, $preselect)):
			$checked = 'checked';
		else:
			$checked = 'rel = "notchecked"';
		endif;		
		
		$checkbox .= "<div><input id='$name' type='radio' name='".$name."' value='$value' $checked> $label</div>";
	
	endforeach;
	
		return $checkbox;
	
}




/****************************************** CREATE FORM FIELDS ***************************************/
// label = public name/description of the field of the field
// type = the type of form field
	/* type options: 
	textarea
	radio
	radio2 (multiple radio buttons)
	file
	checkbox (single check box)
	checkbox2 (multiple check boxes)
	select
	select2 (with "select one...")
	readonly
	hidden
	textbox (the default)
	*/
// name = field name (typically sent to $_POST, no spaces or special characters)
// default = default text (does not work in select)
// options = options array for select
// preselect = preselected item for select
	
function createField($label, $type, $name, $default, $options, $preselect){

	if($type != 'hidden'):
	$field .= "<div class='field $name $type' rel='$name'>";
	$field .= "<label for='$name'>$label</label>";
	$field .= "<div class='user-input $name'>";
	endif;
	
	switch ($type) {
		case "textarea":
			$field .= "<textarea id='$name' name='$name'>$default</textarea>";
			break;
		case "radio":
			$field .= "<input id='$name' type='$type' name='$name' value='$default'> $default";
			break;
		case "file":
			$field .= "<input id='$name' type='$type' name='$name'  />";
			break;
		case "checkbox":
			$field .= "<input id='$name' type='$type' name='$name' value='$default'> $default";
			break;
		case "checkbox2":
			// name = field name
			// options = value array
			// default = preselected values
			$field .= buildCheckBoxes($name, $options, $default);
			break;
		case "radio2":
			// name = field name
			// options = value array
			// default = preselected values
			$field .= buildRadioButtons($name, $options, $default);
			break;
		case "select":
			$field .= "<select id='$name' name='$name'>";
			$field .= showOptionsDrop($options, $preselect);
			$field .= "</select>";
			break;
		case "select2":
			$field .= "<select id='$name' name='$name'>";
			$field .= "<option value=''>Select one...</option>";
			$field .= showOptionsDrop($options, $preselect);
			$field .= "</select>";
			break;
		case "readonly":
			$field .= "<input id='$name' readonly='readonly' type='text' name='$name' value='$default' />";
			break;
		default:
			$field .= "<input id='$name' type='$type' name='$name' value='$default' />";
			break;
	}
	
	if($type != 'hidden'):
	$field .= "</div>";
	$field .= "</div>";
	endif;
	
	return $field;
}

?>