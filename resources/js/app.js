import './bootstrap';

$(document).ready(function(){

    $("#search-button").click(function(){
        let searchText = $('#search-text-field').val();
        let urlParams = new URLSearchParams(window.location.search);
        searchText !== '' ? urlParams.set('searchText', searchText) : urlParams.delete('searchText');
        window.location.search = urlParams;
    });

    document.querySelectorAll('.icon_add').forEach(item => {
        addImageUploadingEvent(item, '#icon_add_');
    })

    document.querySelectorAll('.icon_gray_add').forEach(item => {
        addImageUploadingEvent(item, '#icon_gray_add_');
    })

    document.querySelectorAll('.image_change').forEach(item => {
        addImageUpdatingEvent(item);
    })

    document.querySelectorAll('.image_delete').forEach(item => {
        addImageDeletingEvent(item);
    })

    function addImageDeletingEvent(item) {
        item.addEventListener('click', event => {
            let formData = new FormData();
            let imageId = item.dataset.imageId;
            formData.append('_token', $('meta[name="_token"]').attr('content'));
            formData.append('image_id', imageId);
            sendImageRequest(route("delete.image"), formData);
        })
    }

    function addImageUpdatingEvent(item) {
        item.addEventListener('click', event => {
            let formData = new FormData();
            let imageId = item.dataset.imageId;
            let imageInputSelector = '#image_change_' + imageId;
            formData.append('image', $(imageInputSelector)[0].files[0]);
            formData.append('_token', $('meta[name="_token"]').attr('content'));
            formData.append('image_id', imageId);
            sendImageRequest(route("update.image"), formData, imageInputSelector);
        })
    }

    function addImageUploadingEvent(item, imageInputSelectorPart) {
        item.addEventListener('click', event => {
            let formData = new FormData();
            let parameterId = item.dataset.parameterId;
            let imageInputSelector = imageInputSelectorPart + parameterId;
            let imageType = item.dataset.imageType;
            formData.append('image', $(imageInputSelector)[0].files[0]);
            formData.append('_token', $('meta[name="_token"]').attr('content'));
            formData.append('parameter_id', parameterId);
            formData.append('image_type', imageType);
            sendImageRequest(route("upload.image"), formData, imageInputSelector);
        })
    }

    function sendImageRequest(url, formData, imageInputSelector = '') {
        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response)
                let alertMessage = 'Something went wrong.'
                if (response.success) {
                    if(imageInputSelector !== '') {
                        $(imageInputSelector).val('');
                    }
                    alertMessage = 'Action was successful';
                }
                alert(alertMessage);
                window.location.reload();
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText)
            }
        });
    }
});
