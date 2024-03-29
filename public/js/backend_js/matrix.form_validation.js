
$(document).ready(function(){
	$("#current_pwd").keyup(function(){
		var current_pwd = $("#current_pwd").val();
		$.ajax({
			type:'get',
			url:'/admin/check-pwd',
			data:{current_pwd:current_pwd},
			success:function(resp){
				//alert(resp);
			if(resp=="false"){
					$("#chkPwd").html("<font color='red'>Wrong courrent password<font>");
				}else if(resp=="true"){
					$("#chkPwd").html("<font color='green'>Correct current password<font>");
				}
			}, error:function(){
				alert("error");
			}
			

		});
	});
	
	$('input[type=checkbox],input[type=radio],input[type=file]').uniform();
	
	$('select').select2();
	
	// Form Validation
    $("#basic_validate").validate({
		rules:{
			required:{
				required:true
			},
			email:{
				required:true,
				email: true
			},
			date:{
				required:true,
				date: true
			},
			url:{
				required:true,
				url: true
			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});

	$("#add_category").validate({
		rules:{
			category_name:{
				required:true
			},
			description:{
				required:true,
			},
			url:{
				required:true,
			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});
	// add products
	$("#add_product").validate({
		rules:{
			category_id:{
				required:true
			},
			product_name:{
				required:true
			},
			product_code:{
				required:true,
			},
			product_color:{
				required:true,

			},
			price:{
				required:true,
				number:true

			},
			brand:{
				required:true,
			},
			stock:{
				required:true,
			},
			description:{
				required:true,
			},
			image:{
				required:true,
			},
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});


	$("#edit_product").validate({
		rules:{
			category_id:{
				required:true
			},
			product_name:{
				required:true
			},
			product_code:{
				required:true,
			},
			product_color:{
				required:true,

			},
			price:{
				required:true,
				number:true
			},
			stock:{
				required:true,
				number:true
			},
			description:{
				required:true,
			},
			brand:{
				required:true,
			},
			
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});


	$("#edit_category").validate({
		rules:{
			category_name:{
				required:true
			},
			description:{
				required:true,
			},
			url:{
				required:true,

			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});

	$("#add_coupon").validate({
		rules:{
			coupon_code:{
				required:true
			},
			amount:{
				required:true,
				number:true
			},
			amount_type:{
				required:true
			},
			expiry_date:{
				required:true
			}

		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});

	$("#edit_coupon").validate({
		rules:{
			coupon_code:{
				required:true
			},
			amount:{
				required:true,
				number:true
			},
			amount_type:{
				required:true
			},
			expiry_date:{
				required:true
			}

		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});

	$("#add_banner").validate({
		rules:{
			image:{
				required:true
			},
			title:{
				required:true,
			},
			link:{
				required:true
			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});

	$("#edit_banner").validate({
		rules:{
			title:{
				required:true,
			},
			link:{
				required:true
			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});

	$("#add_developer").validate({
		rules:{
			developer_name:{
				required:true
			},
			developer_surname:{
				required:true
			},
			developer_email:{
				required:true,
				email:true
			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});

	$("#add_group").validate({
		rules:{
			group_name:{
				required:true
			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});
	
	$("#number_validate").validate({
		rules:{
			min:{
				required: true,
				min:10
			},
			max:{
				required:true,
				max:24
			},
			number:{
				required:true,
				number:true
			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});
	
	$("#password_validate").validate({
		rules:{
		    current_pwd:{
				required: true,
				minlength:6,
				maxlength:20
			},
			new_pwd:{
				required: true,
				minlength:6,
				maxlength:20
			},
			confirm_pwd:{
				required:true,
				minlength:6,
				maxlength:20,
				equalTo:"#new_pwd"
			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});

	/*$("#delCat").click(function(){
		if(confirm('Sei sicuro di voler eliminare questa categoria?')){
			return true;

		}
		return false;
	});*/

	/*$("#delProduct").click(function(){
		if(confirm('Sei sicuro di voler eliminare questo prodotto ?')){
			return true;

		}
		return false;
	}); */
	
	$(".deleteRecord").click(function(){
		var id=$(this).attr('rel');
		var deleteFunction = $(this).attr('rel1');
		swal({ 
			title: "Are you sure?",
			text: "You can't recover it later.",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-danger",
			confirmButtonText: "Yes, delete it!"
		},
		function(){
			window.location.href="/admin/"+ deleteFunction + "/" + id;
		});
	});
    $(document).ready(function(){
		var maxField = 10; //Input fields increment limitation
		var addButton = $('.add_button'); //Add button selector
		var wrapper = $('.field_wrapper'); //Input field wrapper
		var fieldHTML = '<div style="margin-left:180px;"><input type="text" name="price[]" id="price" placeholder="Price" style="120px; margin-top:2px;"/> <input type="text" name="stock[]" id="stock" placeholder="Stock" style="120px; margin-top:2px;"/><a href="javascript:void(0);" class="remove_button">Remove</a></div>'; //New input field html 
		var x = 1; //Initial field counter is 1
		
		//Once add button is clicked
		$(addButton).click(function(){
			//Check maximum number of input fields
			if(x < maxField){ 
				x++; //Increment field counter
				$(wrapper).append(fieldHTML); //Add field html
			}
		});
		
		//Once remove button is clicked
		$(wrapper).on('click', '.remove_button', function(e){
			e.preventDefault();
			$(this).parent('div').remove(); //Remove field html
			x--; //Decrement field counter
		});
	});

});
