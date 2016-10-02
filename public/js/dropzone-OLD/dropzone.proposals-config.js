/**
 * Created by sam on 8/21/15.
 */
$(document).ready(function () {

    Dropzone.autoDiscover = false;
    var fileList = {};
    var attachments;

    $("#dZUpload").dropzone({
        url: "/product-proposals/attachments",
        maxFilesize: 3,
        maxFiles: 10,
        acceptedFiles: "image/png, image/jpeg, image/bmp, image/x-windows-bmp, image/gif, application/pdf, text/csv, text/plain, " +
        "application/excel, application/x-excel, application/x-msexcel, application/msword, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, " +
        "application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/plain, application/vnd.ms-excel, " +
        "application/vnd.openxmlformats-officedocument.presentationml.presentation, application/x-compressed, application/x-zip-compressed, application/zip, multipart/x-zip",
        addRemoveLinks: true,
        dictMessage: "Drop files here to upload",
        dictRemoveFile: "Remove",
        dictFileTooBig: "Files must not exceed 3MB.",
        dictMaxFilesExceeded: "You have exceeded the max number of 5 files.",
        removedfile: function (file) {
            var el = file.previewElement;
            el.parentNode.removeChild(file.previewElement);
            $.ajax({
                url: "/product-proposals/attachments/" + fileList[file.name].type + "/" + fileList[file.name].id,
                headers: {
                    'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                },
                type: "DELETE",
                success: function () {
                    delete fileList[file.name];
                    attachments = $("#file-uploads").val();
                    attachments = attachments != "" ? JSON.parse(attachments) : {};
                    delete attachments[file.name];
                    $("#file-uploads").val(JSON.stringify(attachments));

                    console.log(attachments);
                    console.log($("#file-uploads").val());
                    console.log(fileList);
                },
            });
        },
        success: function (file, response) {
            var attachmentId = response.id;
            var attachmentType = response.type;
            fileList[file.name] = {"id": attachmentId, "type": attachmentType};
            file.previewElement.classList.add("dz-success");
            attachments = $("#file-uploads").val();
            attachments = attachments != "" ? JSON.parse(attachments) : {};
            attachments[file.name] = {"id": attachmentId, "type": attachmentType};
            $("#file-uploads").val(JSON.stringify(attachments));

            console.log('Successfully uploaded file: ' + file.name);
            console.log(attachments);
            console.log($("#file-uploads").val());
            console.log(fileList);
        },
        error: function (file, response) {
            file.previewElement.classList.add("dz-error");
        }
    });

});