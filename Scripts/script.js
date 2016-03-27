$(document).ready(
    function() {
        $('#compoundSearch').click(function() {
			// alert("compoundSearch clicked, compoundName: "+ $('#compoundNames').val());
            $('#ctvInfo').hide();
            $('#step1').hide();
            $('#result').hide();
            $('#spinner').show();
			$('#step2').show();
            $.post("http://toxvalue.org/compoundSearch.php", {
			// $.post("http://toxvalue.org/cookie.txt", {
                    compoundName: $('#compoundNames').val()
                },
                function(data) {
					// alert("got data");
                    $('#spinner').hide(),
                        $('#ctvInfo').replaceWith(data),
                        $('#select_check').css("display", "block");
                }
            );

        });

        $('#enable_check').click(function() {
            $('#step2').hide();
            $('#step3').show();
            $('#steptwo').css("display", "block");
            $('#steptwoinstructions').css("display", "none"),
                $('#scompoundSubmit').css("display", "block"),
                $('#Ref_dose').removeAttr("disabled"),
                $('#Ref_conc').removeAttr("disabled"),
                $('#Oral_slope').removeAttr("disabled"),
                $('#Ihal_unit').removeAttr("disabled"),
                $('#Canc_pot').removeAttr("disabled");
        });

        $('#enable_model').click(function() {
            $('#steptwo').css("display", "block");
            $('#steptwoinstructions').css("display", "none"),
                $('#mcompoundSubmit').css("display", "block"),
                $('#Ref_dose').removeAttr("disabled"),
                $('#Ref_conc').removeAttr("disabled"),
                $('#Oral_slope').removeAttr("disabled"),
                $('#Ihal_unit').removeAttr("disabled"),
                $('#Canc_pot').removeAttr("disabled");
        });

        $('#multi_compounds').click(function() {
            $('#draw_structure').hide();
            $('#single_compound').replaceWith('');
            $('#inputfile').show();

        });


        $('#cancel_search').click(function() {
            location.reload(true);
        });
        $('#cancel_file').click(function() {
            location.reload(true);
        });
		
		$('#returnStep2s').click(function() {
            $('#step2').show();
            $('#step3').hide();
        });

        $('#cancel_multiple').click(function() {
            location.reload(true);
        });

        $('#reset_results').click(function() {
            location.reload(true);
        });

        $("#spinner").bind("ajaxSend", function() {
            $(this).show();
        }).bind("ajaxStop", function() {
            $(this).hide();
        }).bind("ajaxError", function() {
            $(this).hide();
        });


        $('#results').dialog({
            autoOpen: false,
            show: {
                effect: "blind",
                duration: 1000
            },
            hide: {
                effect: "fade",
                duration: 1000
            },
            height: 500,
            width: 1000,
            modal: true
        });


        $('#Run').click(function() {
            $('#result').hide();
            $('#select_check').hide();
            $('#spinner').show();

            $.post("results.php", {
                    compoundName: $('#compoundNamer').text(),
                    submitValue: $('#submission').text(),
                    MolWeight: $('#Molecularweight').text(),
                    refDose: $('#Ref_dose').is(":checked"),
                    refConc: $('#Ref_conc').is(":checked"),
                    oralSlope: $('#Oral_slope').is(":checked"),
                    ihalUnit: $('#Ihal_unit').is(":checked"),
                    cancPot: $('#Canc_pot').is(":checked"),
                    noael: $('#NOAEL').is(":checked"),
                    onbd: $('#ONBD').is(":checked"),
                    ocbd: $('#ocbd').is(":checked"),
                    smilee: $('#smiles').text(),
                    CompoundImage: $('#compoundImage').text()

                },
                function(newdata) {
                    $('#spinner').hide(),
                        $('#result').show();
                    $('#reset_check').css("display", "block");
                    $('#resultss').replaceWith(newdata);
                    $('#results').dialog("open");
                }
            );

        });

        $('#Runfile').click(function() {
            $('#result').hide();
            $('#file_check').hide();
            $('#spinner').show();

            $.post("results.php", {
                    submitValue: $('#submission').text(),
                    fileName: $('#filename').text(),
                    refDose: $('#Ref_dose').is(":checked"),
                    refConc: $('#Ref_conc').is(":checked"),
                    oralSlope: $('#Oral_slope').is(":checked"),
                    ihalUnit: $('#Ihal_unit').is(":checked"),
                    cancPot: $('#Canc_pot').is(":checked"),
                    noael: $('#NOAEL').is(":checked"),
                    onbd: $('#ONBD').is(":checked"),
                    ocbd: $('#ocbd').is(":checked")

                },
                function(newdata) {
                    $('#spinner').hide(),
                        $('#result').show();
                    $('#reset_check').css("display", "block");
                    $('#resultss').replaceWith(newdata);
                    $('#results').dialog("open");
                }
            );

        });



        $("#btnExport").click(function(e) {
            window.open('data:application/vnd.ms-excel,' + $('#results').html());
            e.preventDefault();
        });

        $('#drawStructure').click(function() {
            $("#dialog").load('marvin.html', function() {
                $("#dialog").dialog("open");
            });
        });


        $("#dialog").dialog({
            autoOpen: false,
            show: {
                effect: "blind",
                duration: 1000
            },
            hide: {
                effect: "fade",
                duration: 1000
            },
            height: 500,
            width: 1000,
            modal: true
        });

    });