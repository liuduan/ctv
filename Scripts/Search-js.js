$(document).ready(
    function() {

        $('#Search-Data-and-Model').click(function() {
            $('#result').hide();
            $('#select_check').hide();
            // alert("Start Search Now");
			/*alert("This site is currently being tested.\n compoundName: "+ 	
				"compoundName: "+ $('#compoundNamer').text() +
                ", \n submitValue: " + $('#submission').text() +
                "\n MolWeight.:  "+ $('#Molecularweight').text() +
                "\n refDose:  "+ $('#Ref_dose').is(":checked") +
                "\n refConc:  "+ $('#Ref_conc').is(":checked") +
                "\n oralSlope:  "+ $('#Oral_slope').is(":checked") +
                "\n ihalUnit:  "+ $('#Ihal_unit').is(":checked") +
                "\n cancPot:  "+ $('#Canc_pot').is(":checked") +
                "\n noael:  "+ $('#NOAEL').is(":checked") +
                "\n onbd:  "+ $('#ONBD').is(":checked") +
                "\n ocbd:  "+ $('#OCBD').is(":checked") +
                "\n smilee:  "+ $('#smiles').text() +
                "\n CompoundImage:  "+ $('#compoundImage').text() + "");*/
				$('#spinner').show();
				seconds_elapse();
            $.post("Search-php.php", {
                    compoundName: $('#compoundNamer').text(),
                    submitValue: $('#submission').text(),
                    MolWeight: $('#Molecularweight').text(),
					
                    refDose: $('#Ref_dose').is(":checked"),
                    refConc: $('#Ref_conc').is(":checked"),
					
					onbd: $('#ONBD').is(":checked"),
                    ocbd: $('#OCBD').is(":checked"),
                    oralSlope: $('#Oral_slope').is(":checked"),
					
                    ihalUnit: $('#Ihal_unit').is(":checked"),
                    cancPot: $('#Canc_pot').is(":checked"),
                    noael: $('#NOAEL').is(":checked"),

					
                    smilee: $('#smiles').text(),
                    CompoundImage: $('#compoundImage').text()

                },		// end of submitting data.
				
                function(newdata) {						// When search results received.
					// alert("Search Results Received. ");
                    $('#spinner').hide(),
                    $('#result').show();
                    $('#reset_check').css("display", "block");
                    $('#resultss').replaceWith(newdata);
                    $('#results').dialog("open");
				}
            );

        });			// end of $('#Search-Data-and-Model').click(function() {}

    });				// end of $(document).ready(function() {})
	