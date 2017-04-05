var ProfilePictureCrop = {

    settings: {
        formId: "editProfilePictureForm",
        currentForm: null,
        userId: null,
        file: null,
        rotate: null,
        imageCoordinates: null,
        minWidth: 300,
        minHeight: 300,
        maxWidth: 1700,
        maxHeight: 1700
    },
    init: function () {
        this.bindUIActions();
    },


    imageCrop: function () {

        var $image = jQuery(".image-crop > img");

        $image.on({
            'build.cropper': function (e) {
                //console.log(e.type);
            },
            'built.cropper': function (e) {
                //console.log(e.type);
            },
            'cropstart.cropper': function (e) {
                //console.log(e.type, e.action);
            },
            'cropmove.cropper': function (e) {
                //console.log(e.type, e.action);
            },
            'cropend.cropper': function (e) {
                //console.log(e.type, e.action);
            },
            'crop.cropper': function (e) {
                //console.log(e.rotate, e.x, e.y, e.width, e.height);
                ProfilePictureCrop.settings.imageCoordinates = [e.x, e.y, e.width, e.height];
                ProfilePictureCrop.settings.rotate = e.rotate;
            },
            'zoom.cropper': function (e) {
                console.log(e.ratio);
                ProfilePictureCrop.settings.zoom = e.ratio;
            }
        }).cropper({
            fillColor: '#FFFFFF',
            highlight: true,
            // responsive: false,
            resizable: true,
            zoomable: true,
            rotatable: true,
            multiple: true,
            preview: ".img-preview",
            minContainerWidth: 1300,
            minContainerHeight: 600,
            autoCropArea: 1,

            done: function (data) {
                // Output the result data for cropping image.
            },
            built: function () {
                var canvasData = $image.cropper('getCanvasData', {fillColor: '#FFFFFF'});
                var cropBoxData = $image.cropper('getCropBoxData', {fillColor: '#FFFFFF'});
                var imageData = $image.cropper('getImageData', {fillColor: '#FFFFFF'});
            },
            crop: function (e) {
                //console.log(e.rotate, e.x, e.y, e.width, e.height);
                //console.log(e);
                ProfilePictureCrop.settings.imageCoordinates = [e.x, e.y, e.width, e.height];
                ProfilePictureCrop.settings.rotate = e.rotate;
            }
        });

        var $inputImage = $("#inputImage");

        if (window.FileReader) {
            $inputImage.change(function () {
                alert('caca');
                var fileReader = new FileReader(),
                    files = this.files,
                    file;

                if (!files.length) {
                    return;
                }

                file = files[0];
                ProfilePictureCrop.settings.file = file;

                if (/^image\/\w+$/.test(file.type)) {
                    fileReader.readAsDataURL(file);
                    fileReader.onload = function () {
                        $inputImage.val("");

                        $image.cropper("reset", true).cropper("replace", this.result);
                    };
                } else {
                    showMessage("Please choose an image file.");
                }
            });
        } else {
            $inputImage.addClass("hide");
        }

        $("#zoomIn").click(function () {
            $image.cropper("zoom", 0.1);
        });

        $("#zoomOut").click(function () {
            $image.cropper("zoom", -0.1);
        });

        $("#rotateLeft").click(function () {
            $image.cropper("rotate", 45);
        });

        $("#rotateRight").click(function () {
            $image.cropper("rotate", -45);
        });

        $("#setDrag").click(function () {
            $image.cropper("setDragMode", "crop");
        });
    },

    /*
     * --------------------------------
     * UPLOAD HANDLERS
     * --------------------------------
     */
    bindUIActions: function () {
        jQuery("#editProfilePicture").unbind().click(this.loadCropper);
        jQuery("#saveProfilePicture").unbind().click(this.editProfilePicture);
        jQuery("#resetCrop").unbind().click(this.resetImage);
        jQuery("#removeImage").unbind().click(this.removeProfilePicture);

        ProfilePictureCrop.imageCrop();
    },

    editProfilePicture: function (event) {
        var formData = new FormData();

        if (ProfilePictureCrop.settings.file) {
            formData.append('file', ProfilePictureCrop.settings.file);
            ProfilePictureCrop.settings.file = "";
        }
        formData.append('coordinates[x]', ProfilePictureCrop.settings.imageCoordinates[0]);
        formData.append('coordinates[y]', ProfilePictureCrop.settings.imageCoordinates[1]);
        formData.append('coordinates[w]', ProfilePictureCrop.settings.imageCoordinates[2]);
        formData.append('coordinates[h]', ProfilePictureCrop.settings.imageCoordinates[3]);
        formData.append('rotate', ProfilePictureCrop.settings.rotate);

        jQuery.ajax({
            url: "crooper.php",
            data: formData,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (response) {
               alert('success')
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log("Error: error on sending image and image data on ajax call");
                // MainOE.displayGeneralError();
            }
        });
    },


    addProfilePictureSuccess: function (response) {
        jQuery(".edit-profile-picture").cropper('destroy');
        jQuery('#editProfilePictureModal').modal('hide');
        MainOE.displayGeneralSuccess("Photo updated successfully!");
        ProfilePictureCrop.loadProfilePicture();
    },

    loadProfilePicture: function () {
        MainOE.displayAjaxLoaderStep++;
        MainOE.doAjaxGet(OE.urls.loadProfilePicture + '?id=' + ProfilePictureCrop.getUserId(),
            ProfilePictureCrop.loadProfilePictureSuccess);
    },

    loadProfilePictureSuccess: function (data) {
        var newImage = $(data).attr('src', $(data).attr('src') + '?' + Math.random().toString());
        jQuery(".edit-profile-picture").replaceWith(newImage.prop('outerHTML'));
        jQuery("#image").replaceWith(newImage.prop('outerHTML'));
        ProfilePictureCrop.bindUIActions();
        ProfilePictureCrop.imageCrop();
    },

    loadCropper: function (event) {
        event.preventDefault();
        jQuery("#image").replaceWith(jQuery(".edit-profile-picture").prop('outerHTML'));
        ProfilePictureCrop.bindUIActions();
        ProfilePictureCrop.imageCrop();
    },

    resetImage: function (event) {
        event.preventDefault();
        $(".image-crop > img").cropper("reset", true);
    },

    removeProfilePicture: function (event) {
        event.preventDefault();
        jQuery(".edit-profile-picture").cropper('destroy');
        jQuery('#editProfilePictureModal').modal('hide');
        MainOE.displayGeneralSuccess("Photo removed successfully!");
        MainOE.doAjaxGet(OE.urls.removeProfilePicture + '?id=' + ProfilePictureCrop.getUserId(),
            ProfilePictureCrop.loadProfilePictureSuccess
        );
    }
};

