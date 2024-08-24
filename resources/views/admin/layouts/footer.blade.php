</div>
<div class="footer-copyright-area navbar-fixed-bottom">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="footer-copy-right">
                    <p>Copyright &copy; {{ date('Y') }} <a href="https://www.sumagoinfotech.com"
                            target="_blank"> Made with Passion by <img src="../storage/logs/log.png" width="20px" hight="20px"> Sumago Infotech Pvt. Ltd.</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        var currentYear = new Date().getFullYear();
        var startYear = 1980;
        var endYear = currentYear + 10; // Change this to the desired number of future years

        for (var year = startYear; year <= endYear; year++) {
            var option = $("<option>").val(year).text(year);
            if (year < currentYear) {
                option.prop("disabled", true);
            }
            $("#dYear").append(option);
        }
    });
</script>
<script>
    $('.show-btn').click(function(e) {
        $("#show_id").val($(this).attr("data-id"));
        $("#showform").submit();
    })
</script>
<script src="{{ asset('js/vendor/jquery-1.11.3.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/wow.min.js') }}"></script>
<script src="{{ asset('js/jquery-price-slider.js') }}"></script>
<script src="{{ asset('js/jquery.meanmenu.js') }}"></script>
<script src="{{ asset('js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('js/jquery.sticky.js') }}"></script>
<script src="{{ asset('js/jquery.scrollUp.min.js') }}"></script>
<script src="{{ asset('js/scrollbar/jquery.mCustomScrollbar.concat.min.js') }}"></script>
<script src="{{ asset('js/scrollbar/mCustomScrollbar-active.js') }}"></script>
<script src="{{ asset('js/metisMenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('js/metisMenu/metisMenu-active.js') }}"></script>
<!-- <script src="{{ asset('js/morrisjs/raphael-min.js') }}"></script>
<script src="{{ asset('js/morrisjs/morris.js') }}"></script>
<script src="{{ asset('js/morrisjs/morris-active.js') }}"></script> -->
<script src="{{ asset('js/sparkline/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('js/sparkline/jquery.charts-sparkline.js') }}"></script>
<script src="{{ asset('js/calendar/moment.min.js') }}"></script>
<script src="{{ asset('js/calendar/fullcalendar.min.js') }}"></script>
<script src="{{ asset('js/calendar/fullcalendar-active.js') }}"></script>
<script src="{{ asset('js/plugins.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/data-table/bootstrap-table.js') }}"></script>
<script src="{{ asset('js/data-table/tableExport.js') }}"></script>
<script src="{{ asset('js/data-table/data-table-active.js') }}"></script>
<script src="{{ asset('js/data-table/bootstrap-table-editable.js') }}"></script>
<script src="{{ asset('js/data-table/bootstrap-editable.js') }}"></script>
<script src="{{ asset('js/data-table/bootstrap-table-resizable.js') }}"></script>
<script src="{{ asset('js/data-table/colResizable-1.5.source.js') }}"></script>
<script src="{{ asset('js/data-table/bootstrap-table-export.js') }}"></script>
<script src="{{ asset('js/editable/jquery.mockjax.js') }}"></script>
<script src="{{ asset('js/editable/mock-active.js') }}"></script>
<script src="{{ asset('js/editable/select2.js') }}"></script>
<script src="{{ asset('js/editable/moment.min.js') }}"></script>
<script src="{{ asset('js/editable/bootstrap-datetimepicker.js') }}"></script>
<script src="{{ asset('js/editable/bootstrap-editable.js') }}"></script>
<script src="{{ asset('js/editable/xediable-active.js') }}"></script>
<script src="{{ asset('js/chart/jquery.peity.min.js') }}"></script>
<script src="{{ asset('js/peity/peity-active.js') }}"></script>
<script src="{{ asset('js/tab.js') }}"></script>
<link rel="stylesheet" href="{{ asset('https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css') }}">
<script src="{{ asset('https://cdn.jsdelivr.net/npm/flatpickr') }}"></script>

<script>
     $(document).ready(() => {
        $("#image").change(function() {
            $('#english').css('display', 'none');
            $("#english_imgPreview").show();

            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    $("#english_imgPreview").attr("src", event.target.result);
                };
                reader.readAsDataURL(file);
            }
        });
    })
    </script>
<script>
    // $('.delete-btn').click(function(e) {
    $(document).on('click', '.delete-btn', function(e) {
 // Get the data-id attribute of the clicked button
 var id = $(this).attr('data-id');

// Show the id in an alert
alert('ID: ' + id);
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $("#delete_id").val($(this).attr("data-id"));
                $("#deleteform").submit();
            }
        })

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

<script>
            
                function fetch_new_hold(){
                    var TestVal='1';
                    if (TestVal !== '') {
                        $.ajax({
                            url: '{{ route('get-notification') }}',
                            type: 'GET',
                            data: {
                                TestVal: TestVal
                            },
                            // headers: {
                            //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            // },
                            success: function(response) {
                                console.log(response);
                                if (response.notification_count > 0) {
                                        $('#notification-count').text(response.notification_count);

                                        var notificationMessages = '';
                                        $.each(response.notifications, function(index, notification) {
                                        // response.notifications.forEach(function(notification) {
                                        var urlvar=notification.url;
                                        if(notification.admin_count > 0){        
                                            notificationMessages += `
                                                <li>
                                                    <a href="${urlvar}">
                                                        <div class="notification-content">
                                                            <h2 style="color:#444;">${notification.message}</h2>
                                                        </div>
                                                    </a>
                                                </li>`;
                                        }    
                                        });
                                        console.log(notificationMessages);

                                        $('#notification-messages').html(notificationMessages);
                                    
                                }else{
                                        $('#notification-count').text('0');
                                    }
                            }
                        });
                    }
                }


                $(document).ready(function(){
                    setInterval(fetch_new_hold,1000);
                });
        </script>

        <!-- <script>
        $(document).ready(function() {
            // var agent_id = '1';
            // if (agent_id != '') {
                $.ajax({
                    url: "",
                    method: "POST",
                    data: {
                        agent_id: agent_id
                    },
                    success: function(responce) {
                        //  console.log(responce);
                        //  console.log('jjjjjjjjjjjjjjjjjjjj');
                        if (responce > 0) {
                            $('#total_notification_count').append('' + responce + '');
                            // $('#btn_agent').prop('disabled', true)

                        } else {
                            $('#total_notification_count').html('No Enquiry');
                            //  $('#btn_agent').prop('disabled', false)
                        }
                    }
                });
            // }
        });
        </script> -->

    

</body>

</html>
