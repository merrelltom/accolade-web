// Global stuff
var tm_body = jQuery( 'body' );
var validation = false;
var errorMessage = 'Please enter an answer for all questions';
var score = 0;
var modifier = 1;
var question_vals = {};
var price = 0;
var size = 'medium';

var prevTime = 0;
var t;
var started;
var timeOutTime = 60000;
var restartTime = 90000;
var complete = false;

var grad = [];
var mod = [];
var r = [];
var variance  = [];


function gen_result(b, size, score) {
    s = 0 - score;
    v = variance[size];
    m = mod[size];
    rand_b = ((Math.random() * (v * 2)) - v) + parseFloat(b);
    console.log("New baseline = £" + rand_b);
    if (s <= r[0]) {
        p = rand_b * Math.pow(m[0], s - r[0]) * Math.pow(m[1], r[0]);
    } else if (s > r[0] && s <= 0) {
        p = rand_b * Math.pow(m[1], s);
    } else if (s > 0 && s <= r[1]) {
        p = rand_b * Math.pow(m[2], s);
    } else if (s > r[1]) {
        p = rand_b * Math.pow(m[3], s - r[1]) * Math.pow(m[3], r[1]);
    }
    
    return p.toFixed(2);
 }

$(document).ready(function() {

	largePrice = tm_body.attr('data-large');
	mediumPrice = tm_body.attr('data-medium');
	smallPrice = tm_body.attr('data-small');
        variance = {'small': tm_body.attr('data-s-mod'),'medium': tm_body.attr('data-m-mod'), 'large': tm_body.attr('data-l-mod')};
        r = [tm_body.attr('data-range-low'), tm_body.attr('data-range-high')];
        grad["small"] = [tm_body.attr('data-s-grad1'), tm_body.attr('data-s-grad2'), tm_body.attr('data-s-grad3'), tm_body.attr('data-s-grad4')];
        grad["medium"] = [tm_body.attr('data-m-grad1'), tm_body.attr('data-m-grad2'), tm_body.attr('data-m-grad3'), tm_body.attr('data-m-grad4')];
        grad["large"] = [tm_body.attr('data-l-grad1'), tm_body.attr('data-l-grad2'), tm_body.attr('data-l-grad3'), tm_body.attr('data-l-grad4')];
        Object.keys(grad).forEach(function(key){
            mod[key] = [];
            for(let i = 0; i < 4; i++){
                mod[key].push(1 + grad[key][i] / 100);
                    }
        });
        
	/*  
	================================================================
	NEXT BUTTONS WITH BUILT IN VALIDATION
	================================================================  
	*/

	tm_body.on('click', '.next', function(){
		
		var parent = $(this).closest('section');
		var next = $(this).closest('.screen').next('.screen');
		var questions = parent.find('.answers:visible');


		// Postcode Screen
		// We will refine this depending on the postcode checking function?
		if(parent.hasClass('postcode-screen')){
			var answers = parent.find('input:checked');
			if(answers.length == 0 && !$('input.postcode').val()){
				questions.addClass('required');
			}else{
				questions.removeClass('required');
				postcodeCheck();
			}
		}else if(parent.hasClass('trophy-selection')){
			var answers = parent.find('input:checked');
			if(answers.length == 0){
				questions.addClass('required');
			}else{
				if(parent.find('input:checked').val() == 'small'){
					price = smallPrice;
                                        size = parent.find('input:checked').val();
					$("#trophy-results-small").prop("checked", true);
				}
				if(parent.find('input:checked').val() == 'medium'){
					price = mediumPrice;
                                        size = parent.find('input:checked').val();
					$("#trophy-results-medium").prop("checked", true);
				}
				if(parent.find('input:checked').val() == 'large'){
					price = largePrice;
                                        size = parent.find('input:checked').val();
					$("#trophy-results-large").prop("checked", true);
				}
                $('input[name="trophy-size"]').val(parent.find('input:checked').val());
				console.log(price);
				questions.removeClass('required');
			}
		}else{ 
			// loop through each question on the screen
			questions.each(function( index ) {
				// find checked inputs
				var answers = $(this).find('input:checked');
				if(answers.length == 0){
					$(this).addClass('required');
					$(document).scrollTop(0);
				}else{
					$(this).removeClass('required');
				}
			});
		}

		// Check if any questions are 'required'
		if(parent.find('.required:visible').length == 0){
			validation = true;
		}else{
			validation = false;
		}

		// If validated proceed to next
		if(validation == true){
			started = 1;
			$(this).closest('.screen').removeClass('selected');
			next.addClass('selected');
			$(document).scrollTop(0);
            var running_total = 0;
            $('input:checked').each(function () {
                if ($.isNumeric($(this).val())) {
                    running_total += parseInt($(this).val());
                }
            });
            // document.getElementById("total-bar").innerHTML = "Running total:" + running_total + ", price: £" + gen_result(price, size, running_total);
		}

	});


	tm_body.on('click', '.prev', function(){
		var next = $(this).closest('.screen').prev('.screen');
		$(this).closest('.screen').removeClass('selected');
		next.addClass('selected');	
		$(document).scrollTop(0);
	});


	/*  
	================================================================
	CALCULATE PRICE
	================================================================  
	*/

	// tm_body.on('click', '.submit', calculatePrice);
        tm_body.on('click', '.next', calculateScore);
        
        function calculateScore(){
            score = 0;
            $("#formScore").val(score);
            $('input:checked').each(function(){
                if ( $.isNumeric($(this).val()) ) {
                    score += parseInt($(this).val());
                }
            });
            $("#formScore").val(score);
        }

	function calculatePrice(){
		complete = true;
		$('input:checked').each(function(){
            if ( $.isNumeric($(this).val()) ) {
                score += parseInt($(this).val());
            }
		});
                
		var x = $("#accolade-pricing-form").serializeArray();  
                $.each(x, function(i, field){  
                    $("#results-list").append("<li>" + field.name + ": " + field.value + "</li>");  
                });
                $("#results-list").append("<li>SCORE: " + score + "</li>");
                

                var result = gen_result(price, size, score);
                $('input[name="final-price"]').val(result);
                
                $("#results-list").append("<li>FUNCTION: "+price+" *(1 + "+modifier+"/100) ^ "+score+"  =  £"+result+"</li>");

				// Add Price to results screen
				$("#results-price").html("£"+result);
				$(this).closest('.screen').removeClass('selected');
                $('#results-screen').addClass('selected');
	}


	/*  
	================================================================
	POSTCODE CHECK
	================================================================  
	*/

    function postcodeCheck(){
	    var x = document.getElementById("postcode").value;
	    console.log('clicked');
	    console.log(x);
	    $.ajax({
	        type: "POST",
	        url: "assets/php/postcode_get.php",
	        dataType: 'json',
	        data: {postcode: x},
	        success: function (data) {
	            if (Number.isInteger(data)) {
	                if (data != -99) {
	                		console.log(data);
                            $('#pc_result').val(data);
                           	// document.getElementById("postcode-message").innerHTML = '<div class="inner valid">Postcode valid</span>';
                        }
                    }
                    if (data == -99){
                    	console.log(data);
                    	document.getElementById("postcode-message").innerHTML ='<div class="inner error">Postcode not valid</span>';
                    }
	        }
	    });
	    return false;
	};


	/*  
	================================================================
	CONDITIONAL FORM (FINANCIAL SECTION)
	================================================================  
	*/

	tm_body.on('change', 'input[type=radio][name=financial-consent]', function(){
		if( $('input[type=radio][name=financial-consent]:checked').val() == 'yes'){
			$('.financial-conditional-questions').slideToggle(500);
		}else{
			$('.financial-conditional-questions').slideUp(500);
		}
	});


    /*  
	================================================================
	CHANGE TROPHY SIZE ON RESULTS PAGE
	================================================================  
	*/


	// tm_body.on('change', 'input[type=radio][name=trophy-results]', function(){
	// 	if($(this).val() == 'small'){
	// 		price = smallPrice;
 //                        size = $(this).val();
	// 	}
	// 	if($(this).val() == 'medium'){
	// 		price = mediumPrice;
 //                        size = $(this).val();
	// 	}
	// 	if($(this).val() == 'large'){
	// 		price = largePrice;
 //                        size = $(this).val();
	// 	}
	// 	var result = gen_result(price, size, score);
	// 	$("#results-price").html("£"+result);
	// });
        
    /*  
	================================================================
	PAYMENT SUBMIT
	================================================================  
	*/
        
        tm_body.on('click', '#pay_online', function(){
               $('#payment_submit').attr('action', $(this).attr("data-href"));
               $('#payment_submit').submit();
               
        });
        
        tm_body.on('click', '#pay_cashier', function(){
               $('#payment_submit').attr('action', $(this).attr("data-href"));
               $('#payment_submit').submit();
               
        });


});/*CLOSE*/
