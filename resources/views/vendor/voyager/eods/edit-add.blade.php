@extends('voyager::bread.edit-add')


@section('javascript')
    <script type="text/javascript">
        $(document).ready(function(){
            // get daily eod content
            $.ajax({
                url:"{{ route('eod.get') }}",
                success: function(res){
                    if (res.data) {
                        // add email (eod) configuration greetings
                        eodDynamicHtml = `${res.data.eod_configuration.greetings} <ul>`;
                        if (res.data.targets && res.data.targets.length) {
                            res.data.targets.forEach(projectTarget => {
                                eodDynamicHtml += `<li><b>${projectTarget.title}</b>`;
                                // shown project target status if enabled from eod configuration
                                if (res.data.eod_configuration.project_target_status) {
                                    eodDynamicHtml += ` <span class="${projectTarget.status}">[${projectTarget.status}]</span>`;
                                }
                                // if (res.data.eod_configuration.project_task_hours) {
                                //     eodDynamicHtml += ` <span>[${projectTargetTasks.hours}h ${projectTargetTasks.minutes}m]</span>`;
                                // }
                                eodDynamicHtml += `  </li>`;
                                if (projectTarget.tasks && projectTarget.tasks.length) {
                                    projectTarget.tasks.forEach(projectTargetTasks => {
                                        // shown time if enable from eod configuration
                                        eodDynamicHtml += projectTargetTasks.description
                                    });
                                }
                            });
                        }
                        eodDynamicHtml += '</ul>';
                        // append email configuration signatures
                        eodDynamicHtml += `${res.data.eod_configuration.signature}`;
                        // set dynamic email content in rich text editor
                        tinymce.get("richtextemail")
                                .setContent(`${eodDynamicHtml}`);
                    }
                },
                error: function(data){
                    // Not found
                }
            });
        });
    </script>
@endsection
