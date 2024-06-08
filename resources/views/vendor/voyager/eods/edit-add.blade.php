@extends('voyager::bread.edit-add')


@section('javascript')
    <script type="text/javascript">

        var eodConfiguration = @json($eodConfiguration);
        var $example = $(".select2-ajax").select2();
        $example.select2("open");
        function fetchDataAndUpdateDropdown(projectId) {
            // Check if eodConfiguration is not empty
            if (eodConfiguration) {
                // Your existing AJAX call to fetch data
                $.ajax({
                    url: "{{ route('eod.get') }}",
                    data: {
                        project_id: projectId,
                    },
                    success: function (res) {
                        // Your existing success logic here
                        console.log(res);
                        if (res.data) {
                            // add email (eod) configuration greetings
                            eodDynamicHtml = (res.data.eod_configuration && res.data.eod_configuration.signature)? `${res.data.eod_configuration.signature} <ul>` :`<ul>`;
                            // if (res.data.targets && res.data.targets.length) {
                            //     res.data.targets.forEach(projectTarget => {
                            //         eodDynamicHtml += `<li><b>${projectTarget.title}</b>`;
                            //         // shown project target status if enabled from eod configuration
                            //         if (res.data.eod_configuration && res.data.eod_configuration.project_target_status) {
                            //             eodDynamicHtml += ` <span class="${projectTarget.status}">[${projectTarget.status}]</span>`;
                            //         }
                            //         // if (res.data.eod_configuration.project_task_hours) {
                            //         //     eodDynamicHtml += ` <span>[${projectTargetTasks.hours}h ${projectTargetTasks.minutes}m]</span>`;
                            //         // }
                            //         eodDynamicHtml += `  </li>`;
                            //         if (projectTarget.tasks && projectTarget.tasks.length) {
                            //             projectTarget.tasks.forEach(projectTargetTasks => {
                            //                 // shown time if enable from eod configuration
                            //                 eodDynamicHtml += projectTargetTasks.description
                            //             });
                            //         }
                            //     });
                            // }
                            eodDynamicHtml += '</ul>';
                            // append email configuration signatures
                            // eodDynamicHtml += (res.data.eod_configuration && res.data.eod_configuration.signature) ? `${res.data.eod_configuration.signature}`: '';
                            // set dynamic email content in rich text editor
                            console.log('eodDynamicHtml')
                            console.log(eodDynamicHtml)
                            tinymce.get("richtextemail")
                                .setContent(`${eodDynamicHtml}`);
                            tinymce.get("richtextemail").focus();
                        }
                    },
                    error: function (data) {
                        // Your existing error handling logic here
                        console.error(data);
                    }
                });
            }
        }

        $(document).ready(function () {
            // Check if eodConfiguration is not empty on page load
            if (eodConfiguration) {
                var initialProjectId = eodConfiguration.project_id; // Replace with the actual property name
                // Set the initial value and trigger the change event after a delay
                console.log(initialProjectId);
                fetchDataAndUpdateDropdown(initialProjectId);
                // $('.select2-ajax').val(initialProjectId).trigger('change');
            }

            // Initialize select2-ajax after the document is ready
            // $('.select2-ajax').select2({
            //     // Add your select2 configuration options here
            // });

            // Bind change event
            $('.select2-ajax').on('change', function () {
                // Retrieve the selected project ID
                var projectId = $(this).val();
                fetchDataAndUpdateDropdown(projectId);
            });
        });
    </script>
@endsection
