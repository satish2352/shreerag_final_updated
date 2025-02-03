</div>
<div class="footer-copyright-area navbar-fixed-bottom">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="footer-copy-right">
                    <p>Copyright &copy; {{ date('Y') }} <a href="https://web.sumagoinfotech.com"
                            target="_blank"> Made with Passion by <img src="../storage/logs/log.png" width="20px" hight="20px"> Sumago Infotech Pvt. Ltd.</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<audio id="notificationSound" preload="auto">
    <source src="{{ asset('uploads/Notification_sound.mp3') }}" type="audio/mp3">
</audio>
{{-- <audio id="notificationSound" preload="auto">
    <source src="{{ asset('uploads/Notification_sound.mp3') }}" type="audio/mp3">
</audio> --}}
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
// alert('ID: ' + id);
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


{{-- <script>
    var previousNotificationCount = 0;

    function playNotificationSound() {
        const sound = document.getElementById('notificationSound');
        sound.play().catch(error => {
            console.log("Sound couldn't be played automatically: ", error);
        });
    }

    function fetch_new_hold() {
        var TestVal = '1';
        if (TestVal !== '') {
            $.ajax({
                url: '{{ route('get-notification') }}',
                type: 'GET',
                data: { TestVal: TestVal },
                success: function(response) {
                    if (response.notification_count > 0) {
                        $('#notification-count').text(response.notification_count);
    var notificationMessages = '';
    $.each(response.notifications, function(index, notification) {
        var urlvar = notification.url;
        if (notification.admin_count > 0) {
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

    // Instead of displaying the notification count, directly show the messages
    $('#notification-messages').html(notificationMessages);


}

                    // if (response.notification_count > 0) {
                    //         $('#notification-count').text(response.notification_count);
                    //         var notificationMessages = '';
                    //         $.each(response.notifications, function(index, notification) {
                    //             var urlvar = notification.url;
                    //             if (notification.admin_count > 0) {
                    //                 notificationMessages += `
                    //                     <li>
                    //                         <a href="${urlvar}">
                    //                             <div class="notification-content">
                    //                                 <h2 style="color:#444;">${notification.message}</h2>
                    //                             </div>
                    //                         </a>
                    //                     </li>`;
                    //             }
                    //         });
                    // }
                    if(localStorage.getItem('sound_count') =='' && response.notification_count > 0) {
                        if (response.notification_count > 0) {
                            
                            localStorage.setItem('sound_count', response.notification_count )
                            // Update notification count display
                            $('#notification-count').text(response.notification_count);
    
                            // Check if the new count is greater than the previous count
                            if (response.notification_count > previousNotificationCount) {
                                playNotificationSound();
                            }
    
                            previousNotificationCount = response.notification_count; // Update previous count
    
                            var notificationMessages = '';
                            $.each(response.notifications, function(index, notification) {
                                var urlvar = notification.url;
                                if (notification.admin_count > 0) {
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
    
                            $('#notification-messages').html(notificationMessages);
                        } else {
                            $('#notification-count').text('');
                            previousNotificationCount = 0; // Reset the counter when no notifications
                        } 
                    } else if( response.notification_count > localStorage.getItem('sound_count')) {
                        if (response.notification_count > 0) {
                            localStorage.setItem('sound_count', response.notification_count )
                            // Update notification count display
                            $('#notification-count').text(response.notification_count);
    
                            // Check if the new count is greater than the previous count
                            if (response.notification_count > previousNotificationCount) {
                                playNotificationSound();
                            }
    
                            previousNotificationCount = response.notification_count; // Update previous count
    
                            var notificationMessages = '';
                            $.each(response.notifications, function(index, notification) {
                                var urlvar = notification.url;
                                if (notification.admin_count > 0) {
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
    
                            $('#notification-messages').html(notificationMessages);
                        } else {
                            $('#notification-count').text('');
                            previousNotificationCount = 0; // Reset the counter when no notifications
                        } 
                    } 

                    
                   
                },
                error: function(err) {
                    console.log("Error fetching notifications:", err);
                }
            });
        }
    }

    $(document).ready(function() {
        setInterval(fetch_new_hold, 2000); // Check notifications every 60 seconds
    });
</script> --}}
{{-- <script>
    var previousNotificationCount = 0;
    var userId = '{{ session('user_id') }}';
    function playNotificationSound() {
        const sound = document.getElementById('notificationSound');
        sound.play().catch(error => {
            console.log("Sound couldn't be played automatically: ", error);
        });
    }

    function fetch_new_hold() {
        var TestVal = '1';
        if (TestVal !== '') {
            $.ajax({
                url: '{{ route('get-notification') }}',
                type: 'GET',
                data: {
                    userId: userId
                },
                success: function(response) {
                    // Always show the notification count and messages
                    $('#notification-count').text(response.notification_count);

                    var notificationMessages = '';
                    $.each(response.notifications, function(index, notification) {
                        var urlvar = notification.url;
                        if (notification.admin_count > 0) {
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

                    $('#notification-messages').html(notificationMessages);
                  // Debugging previousNotificationCount and response.notification_count
                console.log('Previous Count:', previousNotificationCount);
                console.log('Current Count:', response.notification_count);
                    // Check if the new count is greater than the previous count
                    if (response.notification_count > previousNotificationCount && 
                    response.notification_count != previousNotificationCount) {
                      
                        if (response.notification_count > 0) {
                            playNotificationSound();
                        }
                    }

                    // Update previousNotificationCount
                    previousNotificationCount = response.notification_count;
                },
                error: function(err) {
                    console.log("Error fetching notifications:", err);
                }
            });
        }
    }

    $(document).ready(function() {
        fetch_new_hold(); // Fetch notifications immediately on page load
        setInterval(fetch_new_hold, 2000); // Check notifications every 2 seconds
    });
</script> --}}

<script>
    var previousNotificationCount = parseInt(localStorage.getItem('previousNotificationCount')) || 0;
    var userId = '{{ session('user_id') }}';

    function playNotificationSound() {
        const sound = document.getElementById('notificationSound');
        sound.play().catch(error => {
            console.log("Sound couldn't be played automatically: ", error);
        });
    }

    function fetch_new_hold() {
        var TestVal = '1';
        if (TestVal !== '') {
            $.ajax({
                url: '{{ route('get-notification') }}',
                type: 'GET',
                data: {
                    userId: userId
                },
                success: function(response) {
                    // Always show the notification count and messages
                    $('#notification-count').text(response.notification_count);

                    var notificationMessages = '';
                    $.each(response.notifications, function(index, notification) {
                        var urlvar = notification.url;
                        if (notification.admin_count > 0) {
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

                    $('#notification-messages').html(notificationMessages);

                    // Debugging previousNotificationCount and response.notification_count
                    console.log('Previous Count:', previousNotificationCount);
                    console.log('Current Count:', response.notification_count);

                    // Check if the new count is greater than the previous count
                    if (response.notification_count > previousNotificationCount && 
                        response.notification_count != previousNotificationCount) {
                        if (response.notification_count > 0) {
                            playNotificationSound();
                        }
                    }

                    // Update previousNotificationCount and save it to localStorage
                    previousNotificationCount = response.notification_count;
                    localStorage.setItem('previousNotificationCount', previousNotificationCount);
                },
                error: function(err) {
                    console.log("Error fetching notifications:", err);
                }
            });
        }
    }

    $(document).ready(function() {
        fetch_new_hold(); // Fetch notifications immediately on page load
        setInterval(fetch_new_hold, 2000); // Check notifications every 2 seconds
    });
</script>

</body>

</html>
