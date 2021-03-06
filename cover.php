

<!DOCTYPE html>
<html lang="en">

<link rel="stylesheet" type="text/css" href="/css/custom.css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<script src="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.css" />

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>



<style>
/* unvisited link */
a:link {
    color: black;
    text-decoration: none;
}

/* visited link */
a:visited {
    color: black;
    text-decoration: none;
}

/* mouse over link */
a:hover {
    color: black;
    text-decoration: none;
}

/* selected link */
a:active {
    color: black;
    text-decoration: none;
}

/* Highlighted_rows*/
.Highlighted_rows{
	background-color: lightblue;
	border-width: 8px;
	border-color: CornflowerBlue;
	border-style: solid;
	border-radius: 10px;
	height: 50px;
	text-align: center;
	text-shadow:2px 2px 5px SkyBlue;
	font-size: 14;
	font-weight: bold;
}

</style>

<body background="images/multiple-patterns.gif">

<div id="container">
<?php
include("Header.html");
?>

<br><br>
<h1 style="text-align:center; font-size: 36px;"><b>CTV <br> Conditional Toxicity Value</b></h1>
<h2 style="text-align:center; font-size: 24px;">An <i>In Silico</i> Approach for Generating Toxicity Values for Chemicals</h2>
<br><br><br>

<div style="text-align:center;">
<a href = "index-catch.php">
<img src = "images/Continue-button.png" style="height: 70px;" align="middle"/></a>

</div>

<div style = "padding:100px; font-size: 18px; text-indent: 50px; text-align: justify;">
     Human health assessments produce qualitative toxicity values or standards by relying on epidemiological data or animal studies. Such assessments are data-, time-, and resource-intensive, and cannot be realistically expected for most environmental chemicals. The National Research Council's "Science and Decisions" report called for development of default approaches to support risk estimation for toxcicants lacking chemical-specific information. 
     
	<p style = "text-indent: 50px; ">
    To address the challenge of risk management for data-poor chemicals, we developed quantitative structure-activity relationship (QSAR) models that use chemical properties to predict toxicity values. 
    
    The development and operation of continuous QSAR models are based on a comprehensive database of existing guidance values from US Federal and State agencies. </p>
    
 

	<p style = "text-indent: 50px; "> 
	The QSAR models are reliable. For non-cancer threshold-based values and cancer slope factors with external validation-based Q2 ranging from 0.30 to 0.53. Mean model errors ranged from 0.66 to 1.0 log10 units of concentration/dose; most models can be used to calculate values from > 90% of environmental chemicals. The physico-chemical properties and structural features are informative to model predictions.</p>


    
	<p style = "text-indent: 50px; "> 
	An <i>in silico</i> tool that can predict a toxicity value with an error of less than a factor of 10 fills a critical gap in the current risk management paradigm. It can be used to quickly assess relative hazards of environmental exposures when toxicity data or risk assessments are unavailable.</p>
    
    
       <p style = "text-indent: 50px; ">
    This website serves as a publicly-accessible web-based tool that allows end-users to retrieve existing or calculate predicted toxicity values for the chemicals of interest. 
Toxicity models are installed in Chembench.org.
Chemspider.org is linked for chemical names and SMILES strings. This website is maintained by Dr. Ivan Rusyn and Dr. Weihsueh Chiu working groups at Texas A&M University.</p>
</div><br><br><br>

</div>



<script>
$(document).ready(function(){
	
// $("#container").css({
    // background: "-webkit-gradient(linear, left top, left bottom, from(#00dede), to(#6495ed))" })


});		//end of $(document).ready(function(){
</script>


</body>