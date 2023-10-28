
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

        var cls_arr = cls.trim().split(" ");

        for (var n=0; n<cls_arr.length; n++)
        {
            if(cls_arr[n].trim()=="text_boxes_numeric") //if ( type == 'text' || type == 'password' || type == 'textarea' )
            {

                var numericOrFloatRegex = /^[+-]?\d+(\.\d+)?$/;

                if (trim(document.getElementById(control[i]).value)=="" || (trim(document.getElementById(control[i]).value)*1)==0 || !numericOrFloatRegex.test(document.getElementById(control[i]).value))
                {
                    document.getElementById(control[i]).focus();
                    document.getElementById(control[i]).style.backgroundImage=bgcolor;
                    /*$('#messagebox_main', window.parent.document).fadeTo(100,1,function() //start fading the messagebox
                    {
                        $(this).html('Please Fill up '+msg_text[i]+' field Value').removeClass('messagebox').addClass('messagebox_error').fadeOut(2500);
                    });*/

                    alert("Please Fill up "+msg_text[i]+" field Value");

                    return 0;
                }
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

                alert("Please Fill up "+msg_text[i]+" field Value");

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

                alert("Please Fill up "+msg_text[i]+" field Value");

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

                alert("Please Select "+msg_text[i]+" field Value");

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

                    alert("Please Fill up or Select "+msg_text[i]+" field Value");

                    return 0;
                }
                else
                {
                    /*$('#messagebox_main', window.parent.document).fadeTo(100,1,function() //start fading the messagebox
                    {
                        $(this).html('Please fill up master field Value').removeClass('messagebox').addClass('messagebox_error').fadeOut(2500);

                    });*/

                    alert("Please Fill up or Select "+msg_text[i]+" field Value");

                    return 0;
                }
            }
        }
  }
  return 1;
}

var operation=new Array (5);
operation[0]="Save";
operation[1]="Update";
operation[2]="Delete";
operation[3]="Approve";
operation[4]="Print";

var operation_msg=new Array (7);
operation_msg[0]="Data is Saving, Please wait...";
operation_msg[1]="Data is Updating, Please wait...";
operation_msg[2]="Data is Deleting, Please wait...";
operation_msg[4]="Data Processing, Please wait...";
operation_msg[5]="Report Generating, Please wait...";

var operation_success_msg=new Array (22);
operation_success_msg[0]="Data is Saved Successfully";
operation_success_msg[1]="Data is Updated Successfully";
operation_success_msg[3]="Data is not Saved Successfully";
operation_success_msg[4]="Data is not Updated Successfully";
operation_success_msg[5]="Data is not Deleted Successfully";

var quotes_msg=new Array (50);
quotes_msg[0]="";
quotes_msg[1]="Never tell your problems to anyone...20% don't care and the other 80% are glad you have them --Lou Holtz ";
quotes_msg[2]="Be who you are and say what you feel because those who mind don't matter and those who matter don't mind --Dr. Seuss ";
quotes_msg[3]="Advice is what we ask for when we already know the answer but wish we did not --Erica Jong ";
quotes_msg[4]="Work like you don't need the money, love like you've never been hurt and dance like no one is watching --Randall G Leighton ";
quotes_msg[5]="Glory is fleeting, but obscurity is forever.- Napoleon Bonaparte";
quotes_msg[6]="Victory goes to the player who makes the next-to-last mistake.- Chessmaster Savielly Grigorievitch Tartakower";
quotes_msg[7]="Don't be so humble - you are not that great.- Golda Meir";
quotes_msg[8]="You can avoid reality, but you cannot avoid the consequences of avoiding reality.- Ayn Rand";
quotes_msg[9]="Nothing in the world is more dangerous than sincere ignorance and conscientious stupidity - Martin Luther King Jr.";
quotes_msg[10]="Happiness equals reality minus expectations.- Tom Magliozzi";
quotes_msg[11]="The only difference between I and a madman is that I'm not mad.- Salvador Dali";
quotes_msg[12]="Be the change that you wish to see in the world.― Mahatma Gandhi";
quotes_msg[13]="When one door closes, another opens; but we often look so long and so regretfully upon the closed door that we do not see the one that has opened for us. - Alexander Graham Bell";
quotes_msg[14]="Challenges are what make life interesting and overcoming them is what makes life meaningful. – Joshua J. Marine";
quotes_msg[15]="Happiness cannot be traveled to, owned, earned, or worn. It is the spiritual experience of living every minute with love, grace & gratitude. – Denis Waitley";
quotes_msg[16]="In order to succeed, your desire for success should be greater than your fear of failure. – Bill Cosby";
quotes_msg[17]="I am thankful for all of those who said NO to me. Its because of them I’m doing it myself.– Albert Einstein";
quotes_msg[18]="The only way to do great work is to love what you do. If you haven’t found it yet, keep looking. Don’t settle. – Steve Jobs";
quotes_msg[19]="The best revenge is massive success. – Frank Sinatra";
quotes_msg[20]="In the end, it's not going to matter how many breaths you took, but how many moments took your breath away. --shing xiong ";

var mytime=0;
function freeze_window(msg)
{
    var sdf=Math.floor(Math.random()*(19-1+1)+1);
    document.getElementById('msg_text').innerHTML=quotes_msg[sdf];

    var id = '#dialog';

    $('#dialog').css({'height':150});

    //Get the window height and width
    var winH = $(window).height();
    var winW = $(window).width();
    //Set the popup window to center
    $(id).css('top',  winH/2-$(id).height()/2);
    $(id).css('left', winW/2-$(id).width()/2);
    document.getElementById("msg").innerHTML=0;
    var time=0;
    document.getElementById("msg").innerHTML=0;
    mytime=setInterval('count_process_time()',5000);
    //transition effect
    $(id).fadeIn(0);
}

function count_process_time()
{
    document.getElementById('msg').innerHTML=(document.getElementById('msg').innerHTML*1)+5;
    var vmax=20; var vmin=1;
    var smsg=Math.floor(Math.random()*(vmax-vmin+1)+vmin); //Math.floor(Math.random() * 6) + 1
    document.getElementById('msg_text').innerHTML=quotes_msg[smsg];
}

function release_freezing()
{
    $('.window').hide();
    clearInterval(mytime);
}

