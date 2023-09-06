
function createObject() {

	var request_type;

    if (window.XMLHttpRequest){

        // code for IE7+, Firefox, Chrome, Opera, Safari
        request_type=new XMLHttpRequest();
    }
    else{

        // code for IE6, IE5
        request_type=new ActiveXObject("Microsoft.XMLHTTP");
    }

	return request_type;
}

var http = createObject();


function trim( stringToTrim ) {
    
    return stringToTrim.toString().replace( /^\s+|\s+$/g, "" );
}

function ltrim( stringToTrim ) {
    
    return stringToTrim.toString().replace( /^\s+/, "" );
}

function rtrim( stringToTrim ) {
    
    return stringToTrim.toString().replace( /\s+$/, "" );
}

function form_validation(control,msg_text)
{

  control=control.split("*");
  msg_text=msg_text.split("*");
  var bgcolor='-moz-linear-gradient(bottom, rgb(254,151,174) 0%, rgb(255,255,255) 10%, rgb(254,151,174) 96%)';
  var new_elem="";
  for (var i=0; i<control.length; i++)
  {
        const el = document.querySelector('#'+control[i]); //This is used for -If input field not found, it will show the Field Id/Name:: Aziz/Helal
        if (el) {
        
        }
        else{

            console.log('Id name: ' + control[i] + ' not found');
        }
            
        var type = document.getElementById(control[i]).type;
        var tag = document.getElementById(control[i]).tagName;
        document.getElementById(control[i]).style.backgroundImage="";
        var cls=$('#'+control[i]).attr('class');

        if( cls=="text_boxes_numeric" ) //if ( type == 'text' || type == 'password' || type == 'textarea' )
        {
            if (trim(document.getElementById(control[i]).value)=="" || (trim(document.getElementById(control[i]).value)*1)==0)
            {
                document.getElementById(control[i]).focus();
                document.getElementById(control[i]).style.backgroundImage=bgcolor;
                /*$('#messagebox_main', window.parent.document).fadeTo(100,1,function() //start fading the messagebox
                {
                    $(this).html('Please Fill up '+msg_text[i]+' field Value').removeClass('messagebox').addClass('messagebox_error').fadeOut(2500);
                });*/
                return 0;
            }
        }

        if ( type == 'text' || type == 'password' || type == 'textarea' || type == 'file' || type == 'email' || type == 'date' )
        {
            if (trim(document.getElementById(control[i]).value)=="")
            {
                document.getElementById(control[i]).focus();
                document.getElementById(control[i]).style.backgroundImage=bgcolor;
                /*$('#messagebox_main', window.parent.document).fadeTo(100,1,function() //start fading the messagebox
                {
                    $(this).html('Please Fill up '+msg_text[i]+' field Value').removeClass('messagebox').addClass('messagebox_error').fadeOut(2500);
                });*/
                return 0;
            }
        }
        if ( type == 'email' )
        {
            var email_format = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

            if (!email_format.test(trim(document.getElementById(control[i]).value)))
            {
                document.getElementById(control[i]).focus();
                document.getElementById(control[i]).style.backgroundImage=bgcolor;
                /*$('#messagebox_main', window.parent.document).fadeTo(100,1,function() //start fading the messagebox
                {
                    $(this).html('Please Fill up '+msg_text[i]+' field Value').removeClass('messagebox').addClass('messagebox_error').fadeOut(2500);
                });*/
                return 0;
            }
        }
        else if (type == 'select-one' || type=='select' )
        {
            //alert(control[i]);
            if ( trim(document.getElementById(control[i]).value)=='')
            {
                document.getElementById(control[i]).focus();
                document.getElementById(control[i]).style.backgroundImage=bgcolor;
                /*$('#messagebox_main', window.parent.document).fadeTo(100,1,function() //start fading the messagebox
                {
                    $(this).html('Please Select  '+msg_text[i]+' field Value').removeClass('messagebox').addClass('messagebox_error').fadeOut(2500);

                });*/
                return 0;
            }
        }
        else if (type == 'checkbox' || type == 'radio')
        {
            document.getElementById(control[i]).style.backgroundImage=bgcolor;
            if (new_elem=="") new_elem=control[i]; else new_elem=new_elem+","+control[i];
        }
        else if (type == 'hidden' )
        {
            if(trim(document.getElementById(control[i]).value)=='')
            {
                if(msg_text[i]!='')
                {
                    /*$('#messagebox_main', window.parent.document).fadeTo(100,1,function() //start fading the messagebox
                    {
                        $(this).html('Please Fill up or Select '+msg_text[i]+' field Value').removeClass('messagebox').addClass('messagebox_error').fadeOut(2500);

                    });*/
                    return 0;
                }
                else
                {
                    /*$('#messagebox_main', window.parent.document).fadeTo(100,1,function() //start fading the messagebox
                    {
                        $(this).html('Please fill up master field Value').removeClass('messagebox').addClass('messagebox_error').fadeOut(2500);

                    });*/
                    return 0;
                }

            }
        }
  }
  return 1;
}