<?php
/* Excel reader setup to read from CTV flat file */
//require_once 'Excel/reader.php';
//echo "Hello";
// print_r($_POST);
// exit;
$time_start = time();
  $time_lapse = 0;
require_once 'Excel/excel_reader2.php';
$data = new Spreadsheet_Excel_Reader("CTV_data_2016-xls.xls");
// $data = new Spreadsheet_Excel_Reader("ctv_data.xls");
error_reporting(E_ALL ^ E_NOTICE);


$search_var=$_POST['compoundName'];  //Retrieve compound name from user
$mol_Weight =$_POST['MolWeight']; //Retrieve molecular weight


echo '<link href="css/bootstrap.css" rel="stylesheet">';
echo '<script type="text/javascript" src="js/customScript.js"></script>';
echo '<br><br><br><div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="background-color:;">';

echo '<h2>Step 4<small>: Results</small></h2>';



echo '<div style = "width-max: 500px; float: right; background-color:; ">';
	// this div holds the table.
echo '<h4 style="text-align: left; background-color: ;"><b>Toxicity Values</b></h4>';	

echo '	<table id="compResults" border="2" style="text-align: center; margin: auto; ">';

echo '<tr><td colspan="2" style = "text-align: left; text-indent: 12px;">'. $_POST['compoundName']. '<td></tr>';




for ($i = 1; $i <= $data->rowcount($sheet_index=0); $i++) {
	if(strcasecmp(strtolower($data->val($i,3)), strtolower($_POST['compoundName'])) ==0) {	
		// if the compound name matches
		$value_RfD = $data->val($i, 15);
		$source_RfD = $data->val($i, 20);
		
		$value_RfC = $data->val($i, 23);
		$source_RfC = $data->val($i, 28);
		
		$value_OSF = $data->val($i, 31);
		$source_OSF = $data->val($i, 36);
		
		$value_IUR = $data->val($i, 39);
		$source_IUR = $data->val($i, 44);
		
		$value_CPV = $data->val($i, 47);
		$source_CPV = $data->val($i, 52);	 
		
		$CAS = $data->val($i, 4);
		echo '<tr><td colspan="2" style = "text-align: left; text-indent: 12px;">';
		echo 'CAS Number: '. $CAS. '<td></tr>';
		break;
		} 	// end of text match, if(strcasecmp($data->val($i,2), $_POST['compoundName']) ==0) {}
	}		// end of going through rows, for ($i = 1; $i <= $data->rowcount($sheet_index=0); $i++) {}

if($_POST['refDose'] == "true" && $value_RfD != 0 ){
	Display_exist_value("Reference Dose", $value_RfD, $source_RfD, 'mg/kg');
	$_POST['refDose'] = False;
	}

if($_POST['refConc'] == "true" && $value_RfC != 0 ){
	Display_exist_value("Reference Concentration", $value_RfC, $source_RfC, 'mg/m<sup>3</sup>');
	$_POST['refConc'] = False;
	}

if($_POST['oralSlope'] == "true" && $value_OSF != 0 ){
	Display_exist_value("Oral Slope Factor", $value_OSF, $source_OSF, 'kg/mg');
	$_POST['oralSlope'] = False;
	}
	
if($_POST['ihalUnit'] == "true" && $value_IUR != 0 ){
	Display_exist_value("Inhalation Unit Risk", $value_IUR, $source_IUR, 'm<sup>3</sup>/&micro;g');
	$_POST['ihalUnit'] = False;
	}

	
if($_POST['cancPot'] == "true" && $value_CPV != 0 ){
	Display_exist_value("Cancer Potency Value", $value_CPV, $source_CPV, 'kg/mg');
	$_POST['cancPot'] = False;
	}

	
// if any model is needed
$any_model_needed = $_POST['refDose'] == "true" || $_POST['refConc'] == "true";
$any_model_needed = $any_model_needed || $_POST['noel'] == "true";
$any_model_needed = $any_model_needed || $_POST['oralSlope'] == "true" || $_POST['ihalUnit'] == "true";
$any_model_needed = $any_model_needed || $_POST['cancPot'] == "true" || $_POST['onbdl'] == "true";
$any_model_needed = $any_model_needed || $_POST['onbd'] == "true";
// exit("589, Model needed?: ". $any_model_needed);

// echo '$_POST[refDose] '. $_POST['refDose'].'<br>';
// echo '$_POST[noel] '. $_POST['noel'].'<br>';
// echo '$any_model_needed: '. $any_model_needed;


if ($any_model_needed){
	
	//	login into chembench
  	$useDev = false;
  	$devSuffix = ($useDev === true ? '-dev' : '');
  	$username = 'soidowu';
  	$password = '5uns939r';
  	//$cookieJar = '/tmp/cookie.txt';
  	$cookieJar = __DIR__ . "/cookie.txt";
  	$baseUrl = "https://chembench{$devSuffix}.mml.unc.edu/";
  	$loginUrl = $baseUrl . "login?username={$username}&password={$password}";
	$requesttimeout = 700;
	
  	$loginRequest = curl_init();
  	curl_setopt($loginRequest, CURLOPT_SSL_VERIFYPEER, false);
  	curl_setopt($loginRequest, CURLOPT_SSL_VERIFYHOST, false);
  	curl_setopt($loginRequest, CURLOPT_URL, $loginUrl);
  	curl_setopt($loginRequest, CURLOPT_RETURNTRANSFER, true);
  	curl_setopt($loginRequest, CURLOPT_COOKIEJAR, $cookieJar);
  	curl_setopt($loginRequest, CURLOPT_CONNECTTIMEOUT, $requesttimeout);
  	// echo "Good so far.";
  	// echo '__DIR__: '. __DIR__;
  	$loginResult = curl_exec($loginRequest);
  	if ($loginResult === false) {
		echo "what?";
    	die(curl_error($loginRequest));
  		}
  	curl_close($loginRequest);		//end of login

  	// Start model curl
  	$smilesValue = $_POST['smilee'];
  	$cutoff = 'cutoff=999';
  	$url = "https://chembench.mml.unc.edu/makeSmilesPrediction?smiles=".$smilesValue;
  	$url .= "&cutoff=N/A&predictorIds=";
  	$mh = curl_multi_init();

	// Add each model to curl_multi
	if($_POST['refDose'] == "true")	{
	  	$REFD_CDK_60561 = Add_curl_to_multi_handle('60561'); 
	  	$REFD_ISIDA_70526 = Add_curl_to_multi_handle('70526');
	  	}
	if($_POST['noel'] == "true")	{
	  	$NOEL_CDK_66220 = Add_curl_to_multi_handle('66220'); 
	  	$NOEL_ISIDA_66226 = Add_curl_to_multi_handle('66226');
	  	}
	if($_POST['refConc'] == "true")	{
		$RFC_CDK_60573 = Add_curl_to_multi_handle('60573');  
		$RFC_ISIDA_70520 = Add_curl_to_multi_handle('70520');  
		}

	if($_POST['onbd'] == "true"){
	  	$ONBD_CDK_60471 = Add_curl_to_multi_handle('60471');	
		$ONBD_ISIDA_70508 = Add_curl_to_multi_handle('70508');
	  	}
	if($_POST['onbdl'] == "true"){
	  	$ONBDL_CDK_66208 = Add_curl_to_multi_handle('66208');
	  	$ONBDL_ISIDA_66214 = Add_curl_to_multi_handle('66214');
	  	}
		
	if($_POST['oralSlope'] == "true"){
		$OSF_CDK_60507 = Add_curl_to_multi_handle('60507');  
		$OSF_ISIDA_70514 = Add_curl_to_multi_handle('70514'); 
		}
  	if($_POST['ihalUnit'] == "true"){
		$IUR_CDK_60549 = Add_curl_to_multi_handle('60549');  
		$IUR_ISIDA_60555 = Add_curl_to_multi_handle('60555');  
		}
  	if($_POST['cancPot'] == "true"){
	  $CPV_CDK_60537 = Add_curl_to_multi_handle('60537');  
	  $CPV_ISIDA_60543 = Add_curl_to_multi_handle('60543');  
	  }  
	// above, setup multi handle.

  	// start to execute the handles
  
  	$active = null;
  
  	do {
		$mrc = curl_multi_exec($mh, $active);
  		} while ($mrc == CURLM_CALL_MULTI_PERFORM);

  	while ($active && $mrc == CURLM_OK) {
    	if (curl_multi_select($mh) != -1) {
        	do {
            	$mrc = curl_multi_exec($mh, $active);
        	} while ($mrc == CURLM_CALL_MULTI_PERFORM);
    	}
		else{
			usleep(10);
			do {
        		$mrc = curl_multi_exec($mh, $active);
        		} while ($mrc == CURLM_CALL_MULTI_PERFORM);
			}	// end of else{}
	
  		}	// end of while ($active && $mrc == CURLM_OK) {}, about 12 lines.  

	// Start to read data and display.

  	if($_POST['refDose'] == "true"){
		$model_value_1 = Read_model_curl($REFD_CDK_60561);		//$REFD_CDK
		$model_value_2 = Read_model_curl($REFD_ISIDA_70526);		
		if($model_value_1 == 1){$model_value_1 = 0;} if($model_value_2 == 1){$model_value_2 = 0;}
		$model_value = log((pow(10, $model_value_1) + pow(10, $model_value_2))/2, 10);
		if($model_value_1 == 0 || $model_value_2 == 0 ){$model_value = $model_value_1 + $model_value_2; }
		Display_model_value(round($model_value, 3), $mol_Weight, 'Reference Dose', '- Log<sub>10</sub>(Mole/kg)', 'mg/kg');	
		}
		
	if($_POST['noel'] == "true")	{
		$model_value_1 = Read_model_curl($NOEL_CDK_66220);	
		$model_value_2 = Read_model_curl($NOEL_ISIDA_66226);		
		if($model_value_1 == 1){$model_value_1 = 0;} if($model_value_2 == 1){$model_value_2 = 0;}
		$model_value = log((pow(10, $model_value_1) + pow(10, $model_value_2))/2, 10);
		if($model_value_1 == 0 || $model_value_2 == 0 ){$model_value = $model_value_1 + $model_value_2; }
		Display_model_value(round($model_value, 3), $mol_Weight, 'Reference Dose NOEL', '- Log<sub>10</sub>(Mole/kg)', 'mg/kg');
	  	}

  	if($_POST['refConc'] == "true"){
		$model_value_1 = Read_model_curl($RFC_CDK_60573);		
		$model_value_2 = Read_model_curl($RFC_ISIDA_70520);	
		// echo '$model_value_1 & 2: '. $model_value_1. $model_value_2;	
		if($model_value_1 == 1){$model_value_1 = 0;} if($model_value_2 == 1){$model_value_2 = 0;}
		$model_value = log((pow(10, $model_value_1) + pow(10, $model_value_2))/2, 10);
		if($model_value_1 == 0 || $model_value_2 == 0 ){$model_value = $model_value_1 + $model_value_2; }
		$model_value = round($model_value, 3);
		Display_model_value($model_value, $mol_Weight, 'Reference Concentration', '- Log<sub>10</sub>(Mole/m<sup>3</sup>)', 'mg/m<sup>3</sup>');	
	  	}
	
  	if($_POST['onbd'] == "true"){  			
    	$model_value_1 = Read_model_curl($ONBD_CDK_60471);	
		$model_value_2 = Read_model_curl($ONBD_ISIDA_70508);
		if($model_value_1 == 1){$model_value_1 = 0;} if($model_value_2 == 1){$model_value_2 = 0;}
		$model_value = log((pow(10, $model_value_1) + pow(10, $model_value_2))/2, 10);
		if($model_value_1 == 0 || $model_value_2 == 0 ){$model_value = $model_value_1 + $model_value_2; }
		$model_value = round($model_value, 3);
		Display_model_value($model_value, $mol_Weight, 'Oral Noncancer Benchmark', '- Log<sub>10</sub>(Mole/kg)', 'mg/kg');
		}
	
	if($_POST['onbdl'] == "true"){
		$model_value_1 = Read_model_curl($ONBDL_CDK_66208);		
		$model_value_2 = Read_model_curl($ONBDL_ISIDA_66214);	
		if($model_value_1 == 1){$model_value_1 = 0;} if($model_value_2 == 1){$model_value_2 = 0;}	
		$model_value = log((pow(10, $model_value_1) + pow(10, $model_value_2))/2, 10);
		if($model_value_1 == 0 || $model_value_2 == 0 ){$model_value = $model_value_1 + $model_value_2; }
		$model_value = round($model_value, 3);
		Display_model_value($model_value, $mol_Weight, 'Oral Noncancer Benchmark Level', '- Log<sub>10</sub>(Mole/kg)', 'mg/kg');
		}		  
		  
  	if($_POST['oralSlope'] == "true"){
		$model_value_1 = Read_model_curl($OSF_CDK_60507);		
		$model_value_2 = Read_model_curl($OSF_ISIDA_70514);	
		if($model_value_1 == 1){$model_value_1 = 0;} if($model_value_2 == 1){$model_value_2 = 0;}	
		$model_value = 1/log((pow(10, 1/$model_value_1) + pow(10, 1/$model_value_2))/2, 10);
		if($model_value_1 == 0 || $model_value_2 == 0 ){$model_value = $model_value_1 + $model_value_2; }
		$model_value = round($model_value, 3);
		Display_model_value($model_value, $mol_Weight, 'Oral Slope Factor', 'Log<sub>10</sub>(kg/Mole)', 'kg/mg');	
    	}
		  
	if($_POST['ihalUnit'] == "true"){		
		$model_value_1 = Read_model_curl($IUR_CDK_60549);		
		$model_value_2 = Read_model_curl($IUR_ISIDA_60555);		
		if($model_value_1 == 1){$model_value_1 = 0;} if($model_value_2 == 1){$model_value_2 = 0;}
		$model_value = 1/log((pow(10, 1/$model_value_1) + pow(10, 1/$model_value_2))/2, 10);
		if($model_value_1 == 0 || $model_value_2 == 0 ){$model_value = $model_value_1 + $model_value_2; }
		$model_value = round($model_value, 3);
		Display_model_value($model_value, $mol_Weight, 'Inhalation Unit Risk', 'Log<sub>10</sub>(m<sup>3</sup>/Mole)', 'm<sup>3</sup>/&micro;g');	
    	}
		    
  	if($_POST['cancPot'] == "true"){  			
    	$model_value_1 = Read_model_curl($CPV_CDK_60537 );		
		$model_value_2 = Read_model_curl($CPV_ISIDA_60543);	
		if($model_value_1 == 1){$model_value_1 = 0;} if($model_value_2 == 1){$model_value_2 = 0;}	
		$model_value = 1/log((pow(10, 1/$model_value_1) + pow(10, 1/$model_value_2))/2, 10);
		if($model_value_1 == 0 || $model_value_2 == 0 ){$model_value = $model_value_1 + $model_value_2; }
		$model_value = round($model_value, 3);
		Display_model_value($model_value, $mol_Weight, 'Cancer Potency Value', 'Log<sub>10</sub>(kg/Mole)', 'kg/mg');	
    	}
        		
	}		// end of if ($any_model_needed){}










echo'</table></div></div>';
echo '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="background-color:;"><br><br>';
$imageValue = $_POST['CompoundImage'];
echo '<div style="text-align: left;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;';
echo '<img src="data:image/png;base64,' . $imageValue . '" />';
echo "<p>Common Name: $search_var </p></div>";
echo '<ul class="legend">';
echo '<li><span class="awesome"></span> <b>These values were predicted*.</B></li>';
echo '<li><span class="superawesome"></span>';
echo ' <B style="text-indent: -30px;">These value were retrieved from publicly available sources ';
echo '(<a href="CTV_data_2016-xls.xls" target="_blank">Data Table</a>).</B></li><br>';
echo '</ul>';
echo ' <p align="left">';
echo '<input type="button" class="btn btn-primary" onclick="$(';
echo "'#compResults').table2CSV()";
echo '" value="Export as CSV">';
echo ' <a class="btn btn-danger" href="index-catch.php">New Acessment</a>';
echo '</p>';
echo '</div></div>';		// end of div row, and end of div colum
echo '<br><br>* Each of the predicted values is an average of the predictions from two ';
echo 'QSAR models (Random Forest with ';
echo '<a href="https://www.ncbi.nlm.nih.gov/pubmed/24479757" target="_blank">CDK</a>';
echo ' descriptors and Random Forest with ';
echo '<a href="https://www.ncbi.nlm.nih.gov/pubmed/27464350" target="_blank">ISIDA</a> descripters).';




function Read_model_curl($model_curl){
	if(curl_getinfo($model_curl, CURLINFO_HTTP_CODE) == 500)   // 500 is error
		{
		$http_response = 500;
		$output = curl_multi_getcontent($model_curl);
		}
	else{
		$model_value = curl_multi_getcontent($model_curl);
        $model_value = explode('&',$model_value);
		$results = explode('<td>', $model_value[1]);
        $model_value = $results[2];
		}
	return $model_value;
	}

		
function Display_model_value($model_value, $mol_Weight, $model_name, $model_unit, $converted_unit){	
	echo '<tr id="title" ';
	echo 'style = "all: none; border: 5px; border-top: 8px solid black; ';
	echo 'border-bottom: 2px solid black; ">'. 
		'<td colspan="2"><B>CTV '. $model_name. '</B></td></tr>';
		
	// echo '<br>$model_value: '. $model_value.'<br>';
	if ($model_value != 0){		 
		if ($model_value >= 100 || $model_value < 0.1){
			$model_value_f = sprintf("%.3e", $model_value);}
			else{$model_value_f = round($model_value, 3);}
			
		$SD = round(($model_value * 0.05), 3);
		if ($SD >= 100 || $SD < 0.1){
			$SD_f = sprintf("%.3e", $SD);}
			else{$SD_f = round($SD, 3);}
				
				
				
		if ($model_name == 'Oral Slope Factor' || $model_name== 'Cancer Potency Value' || 
			$model_name == 'Inhalation Unit Risk' ){		// three models that mole is on bottom.
			
			$model_value = 1/$model_value;	
			if ($model_name == 'Oral Slope Factor'){
				$converted_value = pow(10, $model_value) / (1000 * 1000 * $mol_Weight);}
				else{$converted_value = pow(10, $model_value) / (1000 * $mol_Weight);}
			$converted_value = 1/$converted_value;
			}		// end of three models that mole is on bottom.
			
			elseif($model_name == 'Reference Dose' || $model_name== 'Reference Concentration' || 
				$model_name == 'Oral Noncancer Benchmark Level' || $model_name == 'Oral Noncancer Benchmark' || $model_name == 'Reference Dose NOEL'){
							
				$model_value = $model_value * (-1);
				$converted_value = pow(10, $model_value) * 1000 * $mol_Weight;
			
				}		// end of three models that mole is on top.
			
			
			
			if ($converted_value >= 100 || $converted_value < 0.1){
				$converted_value_f = sprintf("%.3e", $converted_value);}
				else{$converted_value_f = sprintf("%.3f", $converted_value);}
		
			$converted_SD = sprintf("%.3e", $converted_value*0.05);
			if ($converted_SD >= 100 || $converted_SD < 0.1){
				$converted_SD_f = sprintf("%.3e", $converted_SD);}
				else{$converted_SD_f = round($converted_SD, 3);}
	
			echo '<tr style = "border: 2px; border-collapse: separate; ">';
			echo '<td style = "text-align: center; text-indent: 3px; padding-right: 3px;">';
			echo $model_unit.  ' &#177; SD</td>';
			echo'<td style = "text-align: center; text-indent: 3px; padding-right: 3px;">';
			echo $converted_unit. ' &#177; SD</td></tr>';
			echo '<tr style = "border-collapse: separate;">';
			echo '<td bgcolor="#56A0D3" style = "text-indent: 12px; ">';
    		echo $model_value_f. " &#177; ". $SD_f. "</td>";
			echo '<td bgcolor="#56A0D3" >'. $converted_value_f. " &#177; ". $converted_SD_f. "</td></tr>";	
			
	}	// end of 	if ($model_value != 0){	)
	else{
		echo'<tr style = "border: 2px;">';
		echo '<td colspan="2" bgcolor="#56A0D3"> Prediction not available</td>';
		}
    }
	
function Add_curl_to_multi_handle($Model_ID){
	global $mh, $url, $cookieJar, $requesttimeout;
	$model_url = $url.$Model_ID;
    $model_curl = curl_init();
	curl_setopt($model_curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($model_curl, CURLOPT_URL, $model_url);
    curl_setopt($model_curl, CURLOPT_ENCODING, $model_url);
	curl_setopt($model_curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($model_curl, CURLOPT_COOKIEFILE, $cookieJar);
	curl_setopt($model_curl, CURLOPT_CONNECTTIMEOUT, $requesttimeout);
	// curl_setopt($model_curl, CURLOPT_TIMEOUT, 120); 			//timeout in seconds
	curl_multi_add_handle($mh, $model_curl);
	
	return $model_curl;
}
//Display_exist_value("Reference Dose", $value_RfD, $mol_Weight, 'mg/(kg x day)');

function Display_exist_value($model_name, $value, $source, $converted_unit){
    echo'<tr style = "all: none; border: 5px; border-top: 8px solid black; border-right: 2px;  border-bottom: 2px solid black; ">';
	echo '<td colspan="2"><B>'. $model_name. '</B></td></tr>';
    echo"<tr><td> $converted_unit</td><td>Source</td></tr>";
	if ($value >= 100 || $value < 0.1){
		$field_1 = sprintf("%.3e",($value));}
		else{$field_1 = round($value, 3);}
    echo '<tr><td>'. $field_1. '</td><td>'. $source ."</td></tr>";
}	  


?>