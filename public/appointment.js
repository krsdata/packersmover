document.addEventListener("DOMContentLoaded", function(){
    document.getElementById('vp_front_data').innerHTML='<h2> Book an Appointment</h2><form id=vpfront_form><div class=inputlabelrow><div class=labelclass> <label for=select_employee>Employee</label></div><div class=inputclass> <select id=select_employee name=employee_id onchange=validate_empl()><option value="">Select Employee</option> </select> <span class=error_message id=employee_visitor> Please select employee. </span></div></div><div class=inputlabelrow><div class=labelclass> <label for=vp_date_12>Date</label></div><div class=inputclass> <input type=date name=vp_date_12 id=vp_date_12 onchange=getdate() required> <span class=error_message id=date_visitor> Visiting date is required. </span></div></div><div class=inputlabelrow><div class=labelclass> <label for=vp_time_12>Time</label></div><div class=inputclass> <input type=time name=vp_time_12 id=vp_time_12 value=00:00 min=00:00 onchange=gettime() required> <span class=error_message id=time_visitor> Visiting time is required. </span> <span id=past_time style=display:none;color:red> The time you have selected is before the Current Time. Please select a valid time. </span></div></div><div class=inputlabelrow><div class=labelclass> <label for=vp_name_12>Visitor Name</label></div><div class=inputclass> <input name=vp_name_12 id=vp_name_12 class=form-control onchange=getname() required> <span class=error_message id=vp_name_12_error> Visitor name is required. </span></div></div><div class=inputlabelrow><div class=labelclass> <label for=v_vp_email_12>Visitor Email</label></div><div class=inputclass> <input name=v_vp_email_12 id=v_vp_email_12 onchange=getvp_email_12() required> <span class=error_message id=v_vp_email_12_error> Visitor email is required. </span> <span id=vp_email_12_format_error style=display:none;color:red> Enter valid email address. </span></div></div><div class=inputlabelrow><div class=labelclass> <label for=v_vp_phone_12>Visitor Mobile No.</label></div><div class=inputclass> <input type=number name=v_vp_phone_12 id=v_vp_phone_12 onchange=getmobile() required> <span class=error_message id=v_vp_phone_12_error> Visitor mobile no. is required. </span> <span id=vp_phone_12_format_error style=display:none;color:red> Enter valid mobile no. </span></div></div><div class=inputlabelrow><div class=labelclass> <label for=vp_purpose_12> Visiting Purpose</label></div><div class=inputclass> <select id=vp_purpose_12 name=vp_purpose_12 onchange=getpurpose() required><option value="">Select Purpose</option><option value=interview>Interview</option><option value=meeting>Meeting</option><option value=delivery>Delivery</option> </select> <span class=error_message id=v_vp_purpose_12> Please select purpose of visit</span></div></div><div class=inputlabelrow> <input type=button class="btn btn-primary" onclick=ajaxsubmitdata() value=Submit></div></form><style>#vp_front_data .error_message{color:red;display:none}#vp_front_data input[type=date]::-webkit-inner-spin-button{display:none;-moz-webkit-appearance:none}#vp_front_data{border-radius:5px;background-color:#f2f2f2;padding:20px;max-width:500px;margin:auto}#vp_front_data input[type=date],#vp_front_data input[type=number],#vp_front_data input[type=text],#vp_front_data input[type=time],#vp_front_data select,#vp_front_data textarea{border:1px solid #ccc;border-radius:4px;padding:12px;resize:vertical;width:100%}#vp_front_data .labelclass{float:left;margin-top:6px;width:25%}#vp_front_data .inputclass{float:left;margin-top:6px;width:75%}#vp_front_data .inputlabelrow:after{content:"";display:table;clear:both}#vp_front_data input[type=button]{background-color:#4caf50;color:#fff;padding:12px 20px;border:none;border-radius:4px;cursor:pointer;float:right;margin-top:10px}#vp_front_data input[type=submit]:hover{background-color:#45a049}@media screen and (max-width:600px){#vp_front_data .inputclass,#vp_front_data .labelclass,#vp_front_data input[type=submit]{width:100%;margin-top:0}}#vp_front_data label{padding:12px 12px 12px 0;display:inline-block}#vp_front_data *{box-sizing:border-box}#vp_front_data h2{text-align:center}#vp_front_data input::-webkit-inner-spin-button,#vp_front_data input::-webkit-outer-spin-button{-webkit-appearance:none;margin:0}#vp_front_data input[type=number]{-moz-appearance:textfield}</style>';
get_employee();
document.getElementById("vp_date_12").setAttribute("min",maxDate);
});
var i="",full_name="",percent_complete="",d=new Date((new Date).setDate((new Date).getDate()+1));e=document.getElementById("select_employee");var employee_id="",selected_date="",vp_time="",vp_name_12="",v_vp_email_12="",v_vp_phone_12="",e1=document.getElementById("vp_purpose_12"),vp_purpose_12="",time_Status="0",date_Status="0",name_Status="0",email_Status="0",employee_Status="0",mobile_Status="0",purpose_Status="0",APP_URL="http://localhost/vp/public/api";function get_employee(){var e=document.getElementById("vp_front_data").getAttribute("data-id"),t=document.getElementById("select_employee");if(e){var n=new FormData;n.append("company_id",e);var a=new XMLHttpRequest;a.onreadystatechange=function(){if(4==this.readyState&&200==this.status){var e=JSON.parse(this.responseText);if("null"!=e.data)for(i=0;i<e.data.length;i++)full_name=e.data[i].f_name.concat(" "+e.data[i].l_name),t.options[t.options.length]=new Option(full_name,e.data[i].id)}},a.upload.addEventListener("progress",function(e){e.loaded,e.total}),a.open("get",APP_URL+"/visitor_employee_id/"+e),a.send(n)}}var month=d.getMonth()+1,day=d.getDate(),year=d.getFullYear();month<10&&(month="0"+month.toString()),day<10&&(day="0"+day.toString());var maxDate=year+"-"+month+"-"+day;function getdate(){if(!(selected_date=document.getElementById("vp_date_12").value))return document.getElementById("date_visitor").style.display="inline",!1;document.getElementById("date_visitor").style.display="none",document.getElementById("vp_time_12").focus(),date_Status="1"}function gettime(){if(!(vp_time=document.getElementById("vp_time_12").value))return document.getElementById("time_visitor").style.display="inline",!1;document.getElementById("past_time").style.display="none",document.getElementById("vp_name_12").focus(),time_Status="1"}function getname(){if(!(vp_name_12=document.getElementById("vp_name_12").value)||" "==vp_name_12||!isNaN(vp_name_12))return document.getElementById("vp_name_12_error").style.display="inline",!1;document.getElementById("vp_name_12_error").style.display="none",document.getElementById("vp_date_12").focus(),name_Status="1"}function getvp_email_12(){return(v_vp_email_12=document.getElementById("v_vp_email_12").value)&&" "!=v_vp_email_12&&isNaN(v_vp_email_12)?(document.getElementById("v_vp_email_12_error").style.display="none",/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(v_vp_email_12)?(document.getElementById("vp_email_12_format_error").style.display="none",document.getElementById("v_vp_phone_12").focus(),void(email_Status="1")):(document.getElementById("v_vp_email_12_error").style.display="none",document.getElementById("vp_email_12_format_error").style.display="block",!1)):(document.getElementById("v_vp_email_12_error").style.display="inline",!1)}function validate_empl(){if(!(employee_id=document.getElementById("select_employee").value))return document.getElementById("employee_visitor").style.display="inline",!1;document.getElementById("employee_visitor").style.display="none",document.getElementById("vp_name_12").focus(),employee_Status="1"}function getmobile(){return(v_vp_phone_12=document.getElementById("v_vp_phone_12").value)&&" "!=v_vp_phone_12?(document.getElementById("v_vp_phone_12_error").style.display="none",/^(?!0+$)\d{8,15}$/.test(v_vp_phone_12)?(document.getElementById("vp_phone_12_format_error").style.display="none",document.getElementById("v_vp_purpose_12").focus(),void(mobile_Status="1")):(document.getElementById("v_vp_phone_12_error").style.display="none",document.getElementById("vp_phone_12_format_error").style.display="block",!1)):(document.getElementById("v_vp_phone_12_error").style.display="inline",!1)}function getpurpose(){if(!(vp_purpose_12=document.getElementById("vp_purpose_12").value))return document.getElementById("v_vp_purpose_12").style.display="inline",!1;document.getElementById("v_vp_purpose_12").style.display="none",purpose_Status="1"}function ajaxsubmitdata(){if("1"==time_Status&&"1"==date_Status&&"1"==email_Status&&"1"==employee_Status&&"1"==mobile_Status&&"1"==purpose_Status&&"1"==name_Status){var e=new FormData;e.append("employee_id",employee_id),e.append("selected_date",selected_date),e.append("selected_time",vp_time),e.append("v_name",vp_name_12),e.append("v_email",v_vp_email_12),e.append("v_phone",v_vp_phone_12),e.append("purpose",vp_purpose_12);var t=new XMLHttpRequest;t.onreadystatechange=function(){4==this.readyState&&200==this.status&&("null"!=JSON.parse(this.responseText).data&&(alert("Your appointment booked successfully!"),document.getElementById("vpfront_form").reset()))},t.upload.addEventListener("progress",function(e){percent_complete=e.loaded/e.total*100}),t.open("post",APP_URL+"/add_appointment_details"),t.send(e)}else alert("Please fill your appointment form")}document.getElementById("vp_date_12").setAttribute("min",maxDate);