<?php
/**
 * Created by PhpStorm.
 * User: panayiotisgeorgiou
 * Date: 16/12/16
 */

session_start();
$str_num1 = rand(1,20);
$str_num2 = rand(1,20);
$_SESSION['expect_answer'] = $str_num1 + $str_num2;
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>AJAX Contact Form</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#submit_contact").click(function() {

                var is_validation = true;
                //simple validation at client's end
                //loop through each field and we simply change border color to red for invalid fields
                $("#contact_form input[required=true], #contact_form textarea[required=true]").each(function(){
                    $(this).css('border-color','');
                    if(!$.trim($(this).val())){ //if this field is empty
                        $(this).css('border-color','red'); //change border color to red
                        is_validation = false; //set do not is_validation flag
                    }
                    //check invalid email
                    var email_reg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                    if($(this).attr("type")=="email" && !email_reg.test($.trim($(this).val()))){
                        $(this).css('border-color','red'); //change border color to red
                        is_validation = false; //set do not is_validation flag
                    }
                });

                if(is_validation) //everything looks good! proceed...
                {
                    //get input field values data to be sent to server
                    post_data = {
                        'user_name'		: $('input[name=name]').val(),
                        'user_email'	: $('input[name=email]').val(),
                        'phone_number'	: $('input[name=phone2]').val(),
                        'subject'		: $('select[name=subject]').val(),
                        'message'		: $('textarea[name=message]').val(),
                        'captcha_answer': $('input[name=captcha_answer]').val()
                    };

                    //Ajax post data to server
                    $.post('submit.php', post_data, function(response){
                        if(response.type == 'error'){ //load json data from server and output message
                            output = '<div class="error">'+response.text+'</div>';
                        }else{
                            output = '<div class="success">'+response.text+'</div>';
                            $("#contact_form  input[required=true], #contact_form textarea[required=true]").val('');
                            $("#contact_form #contact_body").slideUp(); //hide form after success
                        }
                        $("#contact_form #contact_results").hide().html(output).slideDown();
                    }, 'json');
                }
            });

            //reset previously set border colors and hide all message on .keyup()
            $("#contact_form  input[required=true], #contact_form textarea[required=true]").keyup(function() {
                $(this).css('border-color','');
                $("#result").slideUp();
            });
        });
    </script>
    <link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="form-style" id="contact_form">
    <div class="form-style-heading">Please fill in the following form to contact us</div>
    <div id="contact_results"></div>
    <div id="contact_body">
        <label><span>Your Name <span class="required">*</span></span>
            <input type="text" name="name" id="name" required="true" class="input-field"/>
        </label>
        <label><span>Email <span class="required">*</span></span>
            <input type="email" name="email" required="true" class="input-field"/>
        </label>
        <label><span>Phone <span class="required">*</span></span>
            <input type="text" name="phone2" maxlength="15"  required="true" class="input-field" />
        </label>
        <label for="subject"><span>Regarding</span>
            <select name="subject" class="select-field">
                <option value="General Question">General Question</option>
                <option value="Technical Support">Technical Support</option>
                <option value="Sales">Sales</option>
            </select>
        </label>
        <label for="field5"><span>Message <span class="required">*</span></span>
            <textarea name="message" id="message" class="textarea-field" required="true"></textarea>
        </label>
        <label><span>Are you human?</span>
            <?php echo $str_num1 .' + '. $str_num2 ; ?> = <input type="text" name="captcha_answer" required="true" class="tel-number-field long" />
        </label>
        <label>
            <span>&nbsp;</span><input type="submit" id="submit_contact" value="Submit" />
        </label>
    </div>
</div>
</body>
</html>