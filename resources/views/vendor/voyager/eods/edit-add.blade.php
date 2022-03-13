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
                                eodDynamicHtml += `<li><b>${projectTarget.title}</b> <span class="${projectTarget.status}">[${projectTarget.status}]</span> </li>`;
                                if (projectTarget.tasks && projectTarget.tasks.length) {
                                    projectTarget.tasks.forEach(projectTargetTasks => {
                                        eodDynamicHtml += projectTargetTasks.description
                                    });
                                }
                            });
                        }
                        eodDynamicHtml += '</ul>';
                        // append email configuration signatures
                        eodDynamicHtml += `${res.data.eod_configuration.signature}`;
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
