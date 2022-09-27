$(document).ready(function () {

jQuery.validator.addMethod("noSpace", function(value, element) {
  return value.indexOf(" ") < 0 && value != "";
}, "No space please and don't leave it empty");

jQuery.validator.addMethod("ckeditor_required", function(value, element) {
    var editorId = $(element).attr('id');
    var messageLength = CKEDITOR.instances[editorId].getData().replace(/<[^>]*>/gi, '').length;
    return messageLength.length === 0;
 }, "This field is required");


//common validation classes + all forms here...
jQuery.validator.addClassRules({
  required: {
    required: true,
  },
  date:{
    date: true,
  },
  pass: {
    required: true,
    pattern:/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%&]).*$/,
  },
  email:{
    required: true,
    email:true
  },
  contact: {
    required: true,
    number: true,
    minlength: 8,
    maxlength: 15

},
image: {
        accept: "image/*",
    },

    ckeditor_required: {
        required: true,
    },
    'validate_que_number[*]': {
        required: true,
    },

});
});