function account_code_alert(type, action, url) {
    swal({
        title: 'Are you sure?',
        text: 'You wont be able to revert this!',
        type: 'warning',
        input: "text",
        inputPlaceholder: "Enter account code",
        showCancelButton: true,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, ' + action + ' it!',
        cancelButtonText: 'No, cancel!',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        buttonsStyling: false,
        reverseButtons: true,
    }).then((result) => {
        if (result.value) {
            var account_code = result.value;            
            $.ajax({
                type: 'get',
                url: url,
                data : {account_code:account_code},
                "success": function (response) {
                    if (response.success == '1') {
                        swal(
                            action + '!',
                            response.message,                            
                            'success'
                        )
                        if (response.reload == '1') {
                            location.reload();
                        }
                        if (response.remove_action != '') {
                            $('.' + response.remove_action).html('');
                        }
                        if (response.badge != '' && response.badge_data != '') {
                            $('#badge_' + response.badge_data).html(response.badge);
                        }
                        if (response.delete != '') {
                            $('#' + response.delete).remove();
                        }
                        $('.table-data').DataTable().ajax.reload();

                    } else {
                        swal(
                            'Failed',
                            response.message,
                            'error'
                        )
                    }
                }
            });
        } else {
            if( result.dismiss === swal.DismissReason.cancel ) {
                swal(
                    'Cancelled',
                    'Action not performed :)',
                    'error'
                )
            } else {
                swal(
                    'Warning',
                    'Please enter account code.',
                    'error'
                ).then((result) => {
                    account_code_alert(type, action, url);
                });
            }
        } 
    })
}