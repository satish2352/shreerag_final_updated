<!-- ========================= FOOTER START ========================= -->

<div class="footer-copyright-area navbar-fixed-bottom" style="width:100%;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="footer-copy-right">
                    <p>
                        Copyright © {{ date('Y') }}
                        <a href="https://www.sumagoinfotech.com/" target="_blank">
                            Made with Passion by Sumago Infotech Pvt. Ltd.
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notification Sound -->
<audio id="notificationSound" preload="auto">
    <source src="{{ asset('uploads/Notification_sound.mp3') }}" type="audio/mp3">
</audio>


<!-- ========================= CORRECT SCRIPT ORDER ========================= -->

<!-- 1. jQuery (Load ONLY ONCE) -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- 2. Popper -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>

<!-- 3. Bootstrap 4 -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- 4. jQuery Validate (Latest — Load ONLY ONCE) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

<!-- 5. SweetAlert2 (Load ONLY ONCE) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<!-- 6. Bootstrap Table + Export -->
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.21.0/dist/bootstrap-table.min.css">
<script src="https://unpkg.com/bootstrap-table@1.21.0/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.21.0/dist/extensions/export/bootstrap-table-export.min.js"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/tableexport/5.2.0/js/tableexport.min.js"></script> --}}

<!-- 7. Template Plugins -->
<script src="{{ asset('js/wow.min.js') }}"></script>
<script src="{{ asset('js/jquery.meanmenu.js') }}"></script>
<script src="{{ asset('js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('js/jquery.sticky.js') }}"></script>
<script src="{{ asset('js/jquery.scrollUp.min.js') }}"></script>

<!-- Custom Scroll -->
<script src="{{ asset('js/scrollbar/jquery.mCustomScrollbar.concat.min.js') }}"></script>
<script src="{{ asset('js/scrollbar/mCustomScrollbar-active.js') }}"></script>

<!-- Metis Menu -->
<script src="{{ asset('js/metisMenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('js/metisMenu/metisMenu-active.js') }}"></script>

<!-- Editable -->
<script src="{{ asset('js/editable/bootstrap-editable.js') }}"></script>

<!-- Tabs -->
<script src="{{ asset('js/tab.js') }}"></script>

<!-- ========================= FOOTER END ========================= -->


<!-- ========================= YOUR FUNCTIONAL SCRIPTS ========================= -->

<script>
$(document).ready(() => {

    /* Image Preview */
    $("#image").change(function () {
        $('#english').hide();
        $("#english_imgPreview").show();

        const file = this.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = e => $("#english_imgPreview").attr("src", e.target.result);
            reader.readAsDataURL(file);
        }
    });

});
</script>

<!-- DELETE BUTTON SCRIPT -->
<script>
$(document).on('click', '.remove-row', function () {

    let id = $(this).data('id');  
    console.log("Deleting ID = ", id);

    // New row: delete only in frontend
    if (id == 0) {
        $(this).closest("tr").remove();
        return;
    }

    Swal.fire({
        title: 'Are you sure?',
        text: "This row will be permanently deleted.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete!'
    }).then((result) => {
        if (result.isConfirmed) {
            $("#delete_id").val(id);
            $("#deleteform").submit();
        }
    });

});

</script>

<!-- SHOW BUTTON -->
<script>
$(document).on('click', '.show-btn', function () {
    $("#show_id").val($(this).data('id'));
    $("#showform").submit();
});
</script>

<!-- EDIT BUTTON -->
<script>
$(document).on('click', '.edit-btn', function () {
    $("#edit_id").val($(this).data('id'));
    $("#editform").submit();
});
</script>

<!-- EDIT USER BUTTON -->
<script>
$(document).on('click', '.edit-user-btn', function () {
    $("#edit_user_id").val($(this).data('id'));
    $("#edituserform").submit();
});
</script>

<!-- ACTIVE BUTTON -->
<script>
$(document).on('click', '.active-btn', function () {
    $("#active_id").val($(this).data('id'));
    $("#activeform").submit();
});
</script>

<!-- AUTO CLOSE ALERT -->
<script>
setTimeout(() => {
    $(".alert").alert('close');
}, 1000);
</script>

<!-- NOTIFICATION SYSTEM -->
<script>

var previousNotificationCount = parseInt(localStorage.getItem('previousNotificationCount')) || 0;
var userId = '{{ session('user_id') }}';

function playNotificationSound() {
    const sound = document.getElementById('notificationSound');
    sound.play().catch(err => console.log("Sound play blocked:", err));
}

function fetch_new_hold() {

    $.ajax({
        url: '{{ route('get-notification') }}',
        type: 'GET',
        data: { userId: userId },

        success: function (response) {

            $('#notification-count').text(response.notification_count);

            let messages = "";
            $.each(response.notifications, function (i, n) {
                if (n.admin_count > 0) {
                    messages += `
                    <li class="bullet-list">
                        <a href="${n.url}">
                            <div class="notification-content d-flex" >
                               <h6 style="color:#444;">-> ${n.message}</h6>
                            </div>
                        </a>
                    </li>`;
                }
            });

            $('#notification-messages').html(messages);

            if (response.notification_count > previousNotificationCount) {
                playNotificationSound();
            }

            previousNotificationCount = response.notification_count;
            localStorage.setItem('previousNotificationCount', previousNotificationCount);
        }
    });

}

$(document).ready(function () {
    fetch_new_hold();
    setInterval(fetch_new_hold, 2000);
});

</script>



<script>
    $(document).on('click', '.show-btn', function(e) {
    $("#show_id").val($(this).attr("data-id"));
    $("#showform").submit();
});

</script>


<script>
    $('.edit-btn').click(function(e) {
        $("#edit_id").val($(this).attr("data-id"));
        $("#editform").submit();
     })
 </script>
<script>
  
        $(document).on('click', '.edit-user-btn', function(e) {
        $("#edit_user_id").val($(this).attr("data-id"));
        $("#edituserform").submit();
    })
</script>

<script>
        $(document).on('click', '.active-btn', function(e) {
        $("#active_id").val($(this).attr("data-id"));
        $("#activeform").submit();
    })
</script>
<script>
    setTimeout(function() {
            $(".alert").alert('close');
        }, 1000); // 1000 milliseconds = 1 second
    </script>

{{-- report reset button code --}}
    <script>
         document.getElementById('resetFilters').addEventListener('click', function () {

    // Reset all form fields
    document.getElementById('filterForm').reset();

    // Clear Select2 dropdowns
    $('#project_name').val('').trigger('change');
    $('#business_details_id').val('').trigger('change');

    // Clear search input
    document.getElementById('searchKeyword').value = '';

    // Reset pagination
    currentPage = 1;

    // Load default report
    fetchReport(true);
});

    </script>
    <script>
        // Validate date range before applying filter
document.getElementById('filterForm').addEventListener('submit', function (e) {

    // Skip validation when exporting
    if (document.getElementById('export_type').value) {
        return;
    }

    let from = document.querySelector('input[name="from_date"]').value;
    let to = document.querySelector('input[name="to_date"]').value;

    if (from && to) {
        let fromDate = new Date(from);
        let toDate = new Date(to);

        if (fromDate > toDate) {
            e.preventDefault();
            alert("❗ 'From Date' cannot be greater than 'To Date'.");
            return false;
        }
    }
});

    </script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- ========================= FOOTER END ========================= -->
<!-- SUMMERNOTE CSS + JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>
<script>
$(document).ready(function () {
    $('#descriptionsumernote').summernote({
        height: 200,           // Set height
        minHeight: 150,        // Minimum height
        maxHeight: null,       
        focus: true
    });
});
</script>

@stack('scripts')

<!-- ========================= FOOTER END ========================= -->
