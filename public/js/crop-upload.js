jQuery('body').on({ 'change': regularImageUpload1 }, '#profile_img');
$("#crop_image_pop").on("hidden.bs.modal", function() {
    $('#profile_img').val(null);
    $('#splash_screen_img').val(null);
});

function isEmpty(obj) {
    for (var key in obj) {
        if (obj.hasOwnProperty(key))
            return false;
    }

    return true;
}

function dataURItoBlob(dataURI) {
    // convert base64 to raw binary data held in a string
    // doesn't handle URLEncoded DataURIs - see SO answer #6850276 for code that does this
    var byteString = atob(dataURI.split(',')[1]);
    // separate out the mime component
    var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
    // write the bytes of the string to an ArrayBuffer
    var ab = new ArrayBuffer(byteString.length);
    var ia = new Uint8Array(ab);
    for (var i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }
    //Old Code
    //write the ArrayBuffer to a blob, and you're done
    //var bb = new BlobBuilder();
    //bb.append(ab);
    //return bb.getBlob(mimeString);
    //New Code
    return new Blob([ab], { type: mimeString });
}

function regularImageUpload1(e) {
    // alert('1');
    $('#canvas_empty').html('');
    $('#canvas_empty').html('<canvas id="canvas">Your browser does not support the HTML5 canvas ele</canvas>');
    e.preventDefault();
    var canvas = $("#canvas"),
        context = canvas.get(0).getContext("2d");
    //$result = $('#profile_imgd');
    if (this.files && this.files[0]) {
        $('#crop_image_pop').modal('show');

        if (this.files[0].type.match(/^image\//)) {
            var reader = new FileReader();
            reader.onload = function(evt) {
                var img = new Image();
                img.onload = function() {
                    context.canvas.height = img.height;
                    context.canvas.width = img.width;
                    context.drawImage(img, 0, 0);
                    var cropper = canvas.cropper({
                        aspectRatio: 1 / 1,
                        minCropBoxWidth: 150,
                        minCropBoxHeight: 150,
                        minContainerWidth: 400,
                        minContainerHeight: 350,
                        zoomable: false,

                        //autoCropArea: 0.5,
                        //cropBoxResizable: false,
                        //built: function() {
                    });
                    $('#btnCrop').click(function() {
                        // Get a string base 64 data url

                        if (canvas.cropper("isCropped")) {
                            var can = canvas.cropper('getCroppedCanvas', { 'width': 400, 'height': 350 });
                            if (isEmpty(can)) {
                                //alert(can);
                                var croppedImageDataURL = can.toDataURL();
                                var blob = dataURItoBlob(croppedImageDataURL);
                                var fd = new FormData();
                                fd.append("_token", _token);
                                fd.append("file", blob);
                                $.ajax({
                                    type: "POST",
                                    url: APP_URL + '/admin/upload_image',
                                    data: fd,
                                    contentType: false,
                                    cache: false,
                                    processData: false,
                                    success: function(response) {
                                        if (response == 'false') {
                                            $('label[for="image_error"]').text("Something went wrong");
                                        } else {
                                            $('.static_img').hide();
                                            $('label[for="image_error"]').text('');
                                            $(".dropify-render img").remove();
                                            var img = $('<img id="dynamic">');
                                            img.attr('src', APP_URL + '/upload_image/' + response);
                                            img.appendTo('.dropify-render');
                                            $('#image').val(response);
                                            $('#crop_image_pop').modal('hide');
                                            $('#profile_img').val(null);
                                        }
                                    }
                                });
                            } else {}
                        }
                    });
                    $('#btnRestore').click(function() {
                        //canvas.cropper('reset');
                        //$result.empty();
                    });
                };
                img.src = evt.target.result;
            };
            reader.readAsDataURL(this.files[0]);
            //readUploadedImage123(this.files[0],this.files);

        } else {
            alert("Invalid file type! Please select an image file.");
        }
    } else {
        alert('No file(s) selected.');
    }

    e.preventDefault();

}
