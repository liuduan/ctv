<!DOCTYPE html>
<html lang="en">
<!-- Scripts/script.js was used to sense the click of buttons -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>CTV demo </title>

    <script type="text/javascript" src="Scripts/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.form.js"></script>
    <script type="text/javascript" src="Scripts/script.js"></script>
    <script type="text/javascript" src="Scripts/Search-js.js"></script>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script type="text/javascript" language="javascript" src="Scripts/jsme.nocache.js"></script>
	
    <!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	
    <!-- <link href="css/bootstrap.css" rel="stylesheet"> -->
    <!-- Custom styles for this template -->
    <link href="css/jumbotron.css" rel="stylesheet">

    <script>
        function jsmeOnLoad() {
            jsmeApplet = new JSApplet.JSME("jsme_container", "350px", "290px");
            document.JME = jsmeApplet;
        }

        function getSmiles() {
            var data = document.JME.smiles();
            document.getElementById("compoundNames").value = data;
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function(event) {
            $('#form1').ajaxForm(function(data)
			{
                $('#ctvInfo').replaceWith(data);
                $('#file_check').css("display", "block");
            });
        });
    </script>
	<style>
		td {
    		border: 1px solid black;
		}
		#title{border-top: 10px;}
	</style>
</head>

<body background="images/multiple-patterns.gif">
<div id = "container-outside">
<?php
include("Header.html");
?>

    <div id="results" title="Results">
        <div id="resultss" title="Results">resultss
            <p></p>
        </div>
    </div>

    <div class="container">
		 <div id="spinner" class="spinner" style="display:none;">
                <p align="center">
                    <img id="img-spinner" src="images/ajax-loader.gif" alt="Loading ..." />
                    Please wait, the analysis may last more than 2 minutes.
                    <div id="show_content">...</div>
                </p>
         </div>
        
        <div class="body-content">

            <div class="row" id="step1">
                <div>
                    <div id="single_compound">
                        <h2>Step 1</h2>
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<p>
									<b>Enter compound name, CASRN, or SMILES below. Compounds will be searched using <a href="http://www.chemspider.com/" target="_blank">ChemSpider.</a> Mixtures, inorganic compounds, and metallic compounds cannot be predicted by CTV.</b>
								</p>
								<textarea rows="3" cols="10" id="compoundNames" class="form-control" placeholder="Enter compound name OR SMILES OR CAS Registry Number"></textarea>
							</div>

							<div class="col-md-6 col-sm-12">
								<div id="jsme_applet" class="row">
									<div id="draw_structure" class="col-md-8">
										<div id="jsme_container"></div>
									</div>
									<div class="col-md-8">
										<button type="button" class="btn btn-customctv" onclick='getSmiles();'>Get smiles</button>
									</div>
								</div>
							</div>

							<p></p>

						</div>
						<div class="row" align="right">
							<a class="btn btn-default" id="multi_compounds">Multiple compounds</a>
							<button type="submit" id="compoundSearch" class="btn btn-default btn-primary">Search</button>
						</div>
                    </div>
                    <div id="inputfile" style="display:none;">
                        <h2>Step 1</h2>
                        <p><b>Upload a CSV file with a maximum of 10 smile strings. Compounds will NOT be validated, so please ensure smiles strings are accurate. Mixtures, inorganic compounds, and metallic compounds cannot be predicted by CTV.</b>
                        </p>
                        <fieldset>
                            <p></p>
                            <form id="form1" 
                            action="http://35.163.227.246/6-CTV/fileValidator-catch.php" 
                            method="post" enctype="multipart/form-data" target="uploader_iframe">
                                <input id="file" type="file" name="file" />
                                <br/>
                                <a class="btn btn-primary btn-customctv" id="cancel_multiple">Cancel</a>
                                <input class="btn btn-default" id="submit" type="submit" value="Search" />
                                </p>
                            </form>
                        </fieldset>
                        <br>new
                            <form id="form1" 
                            action="http://35.163.227.246/6-CTV/fileValidator-catch.php" 
                            method="post" enctype="multipart/form-data" target="_blank">
                            <input id="file" type="file" name="file" />
                                
                                <br/>
                                
                                <input type="submit" value="Open New..." />
                                </p>
                            </form>
                        
                        
                        
                    </div>
                </div>
            </div>

            <div class="row" id="step2" style="display:none;">
                <div class="col-lg-4">
                    <h2>Step 2</h2> 
                    <div id="ctvInfo">
                    </div>
                    <div id="reset_check" style="display:none;">
                        <p align="right">
                            <a class="btn btn-default" id="reset_results">Reset</a>
                        </p>
                    </div>

                    <div id="select_check" style="display:none; width: 100%;">
                        <p align="right">
                            <a class="btn btn-default" id="cancel_search">Cancel</a>
                            <a class="btn btn-primary btn-customctv" id="enable_check">Select</a>
                        </p>
                    </div>
                    <div id="file_check" style="display:none; width: 100%;">
                        <p align="right">
                            <a class="btn btn-default" id="cancel_file">Cancel</a>
                            <a class="btn btn-primary btn-customctv" id="enable_model">Continue</a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="row" id="step3" style="display:none;">
                <div id="stepper" class="col-lg-6">
                    <h2>Step 3</h2>
                    <label id="steptwoinstructions"><b> Select compound above before continuing </b>
                    </label>
                    <div id="steptwo" style="display:none;">
                        <p>Select toxicity value. You can select multiple toxicity values </p>
                        <p>Each toxicity value is predicted using QSAR modeling (specifically, Random Forest with CDK and ISIDA descriptors)</p>
                        <input type="checkbox" id="Ref_dose" disabled="disabled" value="Ref_dose1">&nbsp;&nbsp; CTV Reference Dose (Avarage of Two Models)
                        <br>
                        <input type="checkbox" id="Ref_conc" disabled="disabled" value="Ref_conc1">&nbsp;&nbsp; CTV Reference Concentration
                        <BR>
                        
                        <input type="checkbox" id="ONBD" disabled="disabled" value="ONBD1">&nbsp;&nbsp; CTV Oral Noncancer Benchmark Dose (Avarage of Three Models)
                        <BR>
                        <input type="checkbox" id="OCBD" disabled="disabled" value="OCBD1">&nbsp;&nbsp; CTV Oral Cancer Benchmark Dose<BR>
                        
                        <input type="checkbox" id="Oral_slope" disabled="disabled" value="Oral_slope1">&nbsp;&nbsp; CTV Oral Slope Factor
                        <br>
                        <input type="checkbox" id="Ihal_unit" disabled="disabled" value="Ihal_unit1">&nbsp;&nbsp; CTV Inhalation Unit Risk
                        <BR>
                        <input type="checkbox" id="Canc_pot" disabled="disabled" value="Canc_pot1">&nbsp;&nbsp; CTV Cancer Potency Value (Avarage of Two Models)
                        <BR>
                      
                        
                        <p></p>

                    </div>
                    <div id="scompoundSubmit" style="display:none;" align="right">
                        <p>
							<i></i>
                            <a class="btn btn-default" id="returnStep2s">Cancel</a>
							<!--
                            <button type="submit" id="Run" class="btn btn-default btn-primary">Run</button>&nbsp;&nbsp; -->
                            <button type="submit" id="Search-Data-and-Model" class="btn btn-default btn-primary">Search Data & Run Model</button>
                        </p>
                    </div>
                    <div id="mcompoundSubmit" style="display:none;" align="right">
                        <p>
							<i>(Please allow up to 5 mins for the analysis to run)</i>
							<a class="btn btn-default" id="returnStep2c">Cancel</a>
                            <button type="submit" id="Runfile" class="btn btn-default btn-primary">Run</button>
                        </p>
                    </div>

                </div>
            </div>
            <hr>
            <footer>
                <p>&copy; Company 2015</p>
            </footer>
        </div>

    </div>
</div> 		<!== end of div id = container-outside ==>
<script>
$(document).ready(function(){
	
// $("#container-outside").css({
//     background: "-webkit-gradient(linear, left top, left bottom, from(#00dede), to(#6495ed))" })


});		//end of $(document).ready(function(){
</script>
   
</body>

</html>