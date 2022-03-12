@extends('voyager::bread.edit-add')
@section('submit-buttons')
    @parent
    <button type="submit" class="btn btn-primary save">Save And Publish</button>
@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function(){
            // get daily eod content
            $.ajax({
                url:"{{ route('eod.get') }}",
                success: function(res){
                    if (res.data && res.data.length) {
                        res.data.forEach(projectTarget => {
                        });
                        tinymce.get("richtextemail").setContent(" <h4><strong>Today's Activities:</strong></h4>" +
                            "<strong>Project:</strong> Tal Sanga" +
                            "<ul>" +
                            "<li>Resolve Image Broken Issue<ul>" +
                            "<li>dasfa" +
                            "<ul>" +
                            "<li>fasdf</li>" +
                            "</ul></li></ul></li>" +

                            "<li>AP-1933 Resolve tag issue<ul>" +
                            "<li>dfads</li>" +
                            "<li>dsaf</li></ul></li></ul> ");
                    }
                },
                error: function(data){
                    // Not found
                }
            });
        });
    </script>
@endsection
