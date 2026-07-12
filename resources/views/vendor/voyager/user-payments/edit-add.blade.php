@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular') }}
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">
                    <!-- form start -->
                    <form role="form"
                            class="form-edit-add"
                            action="{{ $edit ? route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) : route('voyager.'.$dataType->slug.'.store') }}"
                            method="POST" enctype="multipart/form-data">
                        <!-- PUT Method if we are editing -->
                        @if($edit)
                            {{ method_field("PUT") }}
                        @endif

                        <!-- CSRF TOKEN -->
                        {{ csrf_field() }}

                        <div class="panel-body">

                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Adding / Editing -->
                            @php
                                $dataTypeRows = $dataType->{($edit ? 'editRows' : 'addRows' )};
                            @endphp

                            @foreach($dataTypeRows as $row)
                                <!-- GET THE DISPLAY OPTIONS -->
                                @php
                                    $display_options = $row->details->display ?? NULL;
                                    if ($dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')}) {
                                        $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')};
                                    }
                                @endphp
                                @if (isset($row->details->legend) && isset($row->details->legend->text))
                                    <legend class="text-{{ $row->details->legend->align ?? 'center' }}" style="background-color: {{ $row->details->legend->bgcolor ?? '#f0f0f0' }};padding: 5px;">{{ $row->details->legend->text }}</legend>
                                @endif

                                <div class="form-group @if($row->type == 'hidden') hidden @endif col-md-{{ $display_options->width ?? 12 }} {{ $errors->has($row->field) ? 'has-error' : '' }}" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
                                    {{ $row->slugify }}
                                    <label class="control-label" for="name">{{ $row->getTranslatedAttribute('display_name') }}</label>
                                    @include('voyager::multilingual.input-hidden-bread-edit-add')
                                    @if (isset($row->details->view))
                                        @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => ($edit ? 'edit' : 'add'), 'view' => ($edit ? 'edit' : 'add'), 'options' => $row->details])
                                    @elseif ($row->type == 'relationship')
                                        @include('voyager::formfields.relationship', ['options' => $row->details])
                                    @else
                                        {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                                    @endif

                                    @foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
                                        {!! $after->handle($row, $dataType, $dataTypeContent) !!}
                                    @endforeach
                                    @if ($errors->has($row->field))
                                        @foreach ($errors->get($row->field) as $error)
                                            <span class="help-block">{{ $error }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            @endforeach

                        </div><!-- panel-body -->

                        <div class="panel-footer">
                            @section('submit-buttons')
                                <button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                            @stop
                            @yield('submit-buttons')
                        </div>
                    </form>

                    <iframe id="form_target" name="form_target" style="display:none"></iframe>
                    <form id="my_form" action="{{ route('voyager.upload') }}" target="form_target" method="post"
                            enctype="multipart/form-data" style="width:0;height:0;overflow:hidden">
                        <input name="image" id="upload_file" type="file"
                                 onchange="$('#my_form').submit();this.value='';">
                        <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
                        {{ csrf_field() }}
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- Quick add project modal --}}
    <div class="modal fade" id="quick_add_project_modal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add New Project</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="quick_project_name">Project name</label>
                        <input type="text" id="quick_project_name" class="form-control" placeholder="Project name">
                    </div>
                    <p class="text-danger" id="quick_project_error" style="display:none;"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    <button type="button" class="btn btn-primary" id="quick_project_save">Save Project</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick add project target modal --}}
    <div class="modal fade" id="quick_add_target_modal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add New Project Target</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="quick_target_title">Target title</label>
                        <input type="text" id="quick_target_title" class="form-control" placeholder="Target title">
                    </div>
                    <p class="text-muted"><small>The target will be created under the project selected in the form.</small></p>
                    <p class="text-danger" id="quick_target_error" style="display:none;"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    <button type="button" class="btn btn-primary" id="quick_target_save">Save Target</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-danger" id="confirm_delete_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="voyager-warning"></i> {{ __('voyager::generic.are_you_sure') }}</h4>
                </div>

                <div class="modal-body">
                    <h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name"></span>'</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirm_delete">{{ __('voyager::generic.delete_confirm') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Delete File Modal -->
@stop

@section('javascript')
    <script>
        var params = {};
        var $file;

        function deleteHandler(tag, isMulti) {
          return function() {
            $file = $(this).siblings(tag);

            params = {
                slug:   '{{ $dataType->slug }}',
                filename:  $file.data('file-name'),
                id:     $file.data('id'),
                field:  $file.parent().data('field-name'),
                multi: isMulti,
                _token: '{{ csrf_token() }}'
            }

            $('.confirm_delete_name').text(params.filename);
            $('#confirm_delete_modal').modal('show');
          };
        }

        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();

            //Init datepicker for date fields if data-datepicker attribute defined
            //or if browser does not handle date inputs
            $('.form-group input[type=date]').each(function (idx, elt) {
                if (elt.hasAttribute('data-datepicker')) {
                    elt.type = 'text';
                    $(elt).datetimepicker($(elt).data('datepicker'));
                } else if (elt.type != 'date') {
                    elt.type = 'text';
                    $(elt).datetimepicker({
                        format: 'L',
                        extraFormats: [ 'YYYY-MM-DD' ]
                    }).datetimepicker($(elt).data('datepicker'));
                }
            });

            @if ($isModelTranslatable)
                $('.side-body').multilingual({"editing": true});
            @endif

            $('.side-body input[data-slug-origin]').each(function(i, el) {
                $(el).slugify();
            });

            $('.form-group').on('click', '.remove-multi-image', deleteHandler('img', true));
            $('.form-group').on('click', '.remove-single-image', deleteHandler('img', false));
            $('.form-group').on('click', '.remove-multi-file', deleteHandler('a', true));
            $('.form-group').on('click', '.remove-single-file', deleteHandler('a', false));

            $('#confirm_delete').on('click', function(){
                $.post('{{ route('voyager.'.$dataType->slug.'.media.remove') }}', params, function (response) {
                    if ( response
                        && response.data
                        && response.data.status
                        && response.data.status == 200 ) {

                        toastr.success(response.data.message);
                        $file.parent().fadeOut(300, function() { $(this).remove(); })
                    } else {
                        toastr.error("Error removing file.");
                    }
                });

                $('#confirm_delete_modal').modal('hide');
            });
            $('[data-toggle="tooltip"]').tooltip();
        });

    </script>

    <script>
        const currentCurrencyRateInput = document.querySelector('input[name="currency_current_rate"]');
        const clientSourceSelect = document.querySelector('select[name="client_source"]');

        // Add a focus-out event listener to the total earning input field
        currentCurrencyRateInput.addEventListener("blur", autofillFields);
        // Using Select2's event binding to detect changes
        $(clientSourceSelect).on('select2:select', function (e) {
            autofillFields();
        });
            @php
                // The share rate must belong to the OWNER of the payment request,
                // not the logged-in editor (e.g. accountant editing a business
                // developer's request must use his 5-6%, not 37.5%).
                $calculator = app(\App\Services\PaymentCalculationService::class);
                $rateOwner = ($edit ? $dataTypeContent->developer : null) ?? Auth::user();
                $isBusinessDeveloperShare = $edit
                    ? $dataTypeContent->share_type === \App\Models\UserPayment::SHARE_TYPE_BUSINESS_DEVELOPER
                    : in_array(Auth::id(), [\App\Models\User::AYUB_USER_ID, \App\Models\User::ALI_HASAN_USER_ID]);
                $userPercentageValue = $isBusinessDeveloperShare
                    ? $calculator->businessDeveloperRate($rateOwner)
                    : $calculator->developmentPartnerRate($rateOwner);
            @endphp
            const userPercentage = {{ $userPercentageValue }}; // Share rate of the payment's owner
            // console.log(userPercentage);
            // console.log(typeof userPercentage);
        function autofillFields() {
            // Get references to the input fields by name
            const totalEarningInput = document.querySelector('input[name="total_earning"]');
            const clientSourceSelect = document.querySelector('select[name="client_source"]');
            const devEarningInput = document.querySelector('input[name="dev_earning"]');
            const currentCurrencyRateInput = document.querySelector('input[name="currency_current_rate"]');
            const payableInput = document.querySelector('input[name="payable"]');


            // Get the selected client source
            const selectedClientSource = clientSourceSelect.value;
            // console.log(selectedClientSource)
            // Get the total earning value
            const totalEarning = parseFloat(totalEarningInput.value);
            const currentCurrencyRate = parseFloat(currentCurrencyRateInput.value);
            // console.log(totalEarning)
            // console.log(currentCurrencyRate)
            if (!isNaN(totalEarning)) {
                let devEarning = 0;

                if (selectedClientSource === "fiverr") {
                    // Calculate dev earning for Fiverr (total earning - 20%)
                    devEarning = totalEarning * 0.8;
                } else if (selectedClientSource === "upwork") {
                    // Calculate dev earning for Upwork (total earning - 10%)
                    devEarning = totalEarning * 0.9;
                } else if (selectedClientSource === "payonner" || selectedClientSource === "other") {
                    // Assign total earning for Payonner and Other without deductions
                    devEarning = totalEarning;
                }
                // console.log('devEarning');
                // console.log(devEarning);
                // Calculate percentage of employee
                const devNetEarning = devEarning * userPercentage;
                // devEarning -= devNetEarning;
                // console.log('devNetEarning');
                // console.log(devNetEarning);


                // Update the dev earning input field with the calculated value
                devEarningInput.value = devNetEarning.toFixed(2); // Format the result to two decimal places
                // Calculate the payable amount by multiplying devNetEarning with the currency rate
                const payableAmount = devNetEarning * currentCurrencyRate;
                payableInput.value = payableAmount.toFixed(2); // Format the result to two decimal places
            } else {
                // If total earning is not a valid number, clear the dev earning input
                devEarningInput.value = "";
            }
        }
    </script>

    <script>
        // Quick-add buttons for project and project target, injected next to their selects.
        $(function () {
            var csrf = $('meta[name="csrf-token"]').attr('content');
            var projectSelect = $('select[name="project_id"]');
            var targetSelect = $('select[name="project_target_id"]');

            if (projectSelect.length) {
                projectSelect.closest('.form-group').append(
                    '<a href="#" class="btn btn-xs btn-default" id="quick_add_project_btn" style="margin-top:5px;"><i class="voyager-plus"></i> Add new project</a>'
                );
            }
            if (targetSelect.length) {
                targetSelect.closest('.form-group').append(
                    '<a href="#" class="btn btn-xs btn-default" id="quick_add_target_btn" style="margin-top:5px;"><i class="voyager-plus"></i> Add new target</a>'
                );
            }

            $(document).on('click', '#quick_add_project_btn', function (e) {
                e.preventDefault();
                $('#quick_project_name').val('');
                $('#quick_project_error').hide();
                $('#quick_add_project_modal').modal('show');
            });

            $(document).on('click', '#quick_add_target_btn', function (e) {
                e.preventDefault();
                if (!projectSelect.val()) {
                    alert('Select (or add) a project first, then add its target.');
                    return;
                }
                $('#quick_target_title').val('');
                $('#quick_target_error').hide();
                $('#quick_add_target_modal').modal('show');
            });

            $('#quick_project_save').on('click', function () {
                var name = $.trim($('#quick_project_name').val());
                if (!name) {
                    $('#quick_project_error').text('Project name is required.').show();
                    return;
                }
                $.post('{{ route('user-payments.quick-add-project') }}', {_token: csrf, name: name})
                    .done(function (data) {
                        projectSelect.append(new Option(data.name, data.id, true, true)).trigger('change');
                        $('#quick_add_project_modal').modal('hide');
                    })
                    .fail(function (xhr) {
                        var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Could not create the project.';
                        $('#quick_project_error').text(msg).show();
                    });
            });

            $('#quick_target_save').on('click', function () {
                var title = $.trim($('#quick_target_title').val());
                if (!title) {
                    $('#quick_target_error').text('Target title is required.').show();
                    return;
                }
                $.post('{{ route('user-payments.quick-add-target') }}', {_token: csrf, title: title, project_id: projectSelect.val()})
                    .done(function (data) {
                        targetSelect.append(new Option(data.title, data.id, true, true)).trigger('change');
                        $('#quick_add_target_modal').modal('hide');
                    })
                    .fail(function (xhr) {
                        var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Could not create the target.';
                        $('#quick_target_error').text(msg).show();
                    });
            });
        });
    </script>
@stop
