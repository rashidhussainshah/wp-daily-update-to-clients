@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());

    // True when the request belongs to a business developer submitting their
    // own salary-employee commission - editing an existing BD request looks
    // at its stored share_type, adding a new one looks at the logged-in
    // user's role (User::isBusinessDeveloper(), any current/future business
    // developer, not a hardcoded list of people). These requests never
    // involve a development partner, so "Select Business Developer" (who to
    // attribute a partner's commission to) doesn't apply and must not be shown.
    $submitterIsBusinessDeveloper = $edit
        ? $dataTypeContent->share_type === \App\Models\UserPayment::SHARE_TYPE_BUSINESS_DEVELOPER
        : Auth::user()->isBusinessDeveloper();
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Bolder labels/dropdown text + clearer input borders, for readability */
        .page-content .control-label {
            font-weight: 700;
        }
        .page-content select.form-control,
        .page-content select.form-control option,
        .page-content .select2-container .select2-selection__rendered {
            font-weight: 600;
        }
        /* select2's open dropdown list of options (rendered separately, not
           inside the <select>) */
        .select2-results__option {
            font-weight: 600;
        }
        .page-content .form-control {
            border: 1.5px solid #97a3af;
        }
        .page-content .form-control:focus {
            border-color: #55606c;
            box-shadow: 0 0 0 2px rgba(85, 96, 108, .15);
        }
        .page-content .select2-container--default .select2-selection--single,
        .page-content .select2-container--default .select2-selection--multiple {
            border: 1.5px solid #97a3af !important;
        }

        /* Mobile responsiveness - desktop layout above is untouched.
           Bootstrap's col-md-* grid already stacks fields full-width below
           768px on its own; this covers the pieces that don't. */
        @media (max-width: 767px) {
            .page-content.edit-add .select2-container,
            .page-content.edit-add select.form-control,
            .page-content.edit-add input.form-control,
            .page-content.edit-add textarea.form-control {
                width: 100% !important;
                max-width: 100% !important;
            }

            /* Quick-add links: full-width block under the select instead of
               a cramped inline link */
            #quick_add_project_btn,
            #quick_add_target_btn {
                display: block;
                width: 100%;
                text-align: center;
                margin-top: 6px !important;
            }

            /* Save button: full-width, easier to tap */
            .panel-footer .btn.save {
                display: block;
                width: 100%;
            }

            /* Modal footers with two buttons side by side: stack them */
            .modal-footer .btn {
                display: block;
                width: 100%;
                margin: 4px 0 !important;
            }
        }
    </style>
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
                                @continue(($row->field === 'user_payment_hasone_user_relationship' || ($row->details->column ?? null) === 'select_business_developer_id') && $submitterIsBusinessDeveloper)
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

        // currency_current_rate is hidden on the add form (partners/BD never
        // see rates) - only bind if it's actually rendered (edit form), so a
        // null-reference error here doesn't silently kill every script below
        // it, including the income_id -> autofillFields wiring.
        if (currentCurrencyRateInput) {
            currentCurrencyRateInput.addEventListener("blur", autofillFields);
        }
        // Using Select2's event binding to detect changes
        $(clientSourceSelect).on('select2:select', function (e) {
            autofillFields();
        });
        // Recalculate when the earning amount itself changes.
        const totalEarningField = document.querySelector('input[name="total_earning"]');
        if (totalEarningField) {
            totalEarningField.addEventListener("blur", autofillFields);
        }

        // Income-driven prefills: picking the income fills the source, the
        // earning amount and the PKR rate so nothing is typed twice.
        @php
            $incomePrefillMap = \App\Models\Income::query()->get()->mapWithKeys(fn($i) => [$i->id => [
                'source' => $i->clientSource(),
                'amount' => (float) $i->amount,
                'rate' => $i->conversion_rate ? (float) $i->conversion_rate : null,
            ]]);
        @endphp
        const incomePrefillMap = @json($incomePrefillMap);

        function applyIncomePrefills(incomeId) {
            const info = incomePrefillMap[incomeId];
            if (!info) return;
            const sourceSelect = document.querySelector('select[name="client_source"]');
            if (sourceSelect && info.source) {
                $(sourceSelect).val(info.source).trigger('change');
            }
            const totalInput = document.querySelector('input[name="total_earning"]');
            if (totalInput && !totalInput.value && info.amount) {
                totalInput.value = info.amount;
            }
            const rateInput = document.querySelector('input[name="currency_current_rate"]');
            if (rateInput && !rateInput.value && info.rate) {
                rateInput.value = info.rate;
            }
            autofillFields();
        }

        $('select[name="income_id"]').on('select2:select', function () {
            applyIncomePrefills(this.value);
        });
            @php
                // The share rate must belong to the OWNER of the payment request,
                // not the logged-in editor (e.g. accountant editing a business
                // developer's request must use his 5-6%, not 37.5%).
                $calculator = app(\App\Services\PaymentCalculationService::class);
                $rateOwner = ($edit ? $dataTypeContent->developer : null) ?? Auth::user();
                // Reuses $submitterIsBusinessDeveloper computed at the top of
                // this file - single source of truth.
                $userPercentageValue = $submitterIsBusinessDeveloper
                    ? $calculator->businessDeveloperRate($rateOwner)
                    : $calculator->developmentPartnerRate($rateOwner);
            @endphp
            const userPercentage = {{ $userPercentageValue }}; // Share rate of the payment's owner
        function autofillFields() {
            // Get references to the input fields by name
            const totalEarningInput = document.querySelector('input[name="total_earning"]');
            const clientSourceSelect = document.querySelector('select[name="client_source"]');
            // dev_earning/currency_current_rate/payable are hidden on the add
            // form (add=0 - partners/BD never see rates or shares), so these
            // may not exist in the DOM at all here; guard every use.
            const devEarningInput = document.querySelector('input[name="dev_earning"]');
            const currentCurrencyRateInput = document.querySelector('input[name="currency_current_rate"]');
            const payableInput = document.querySelector('input[name="payable"]');

            // Get the selected client source
            const selectedClientSource = clientSourceSelect.value;
            // Get the total earning value
            const totalEarning = parseFloat(totalEarningInput.value);
            const currentCurrencyRate = currentCurrencyRateInput ? parseFloat(currentCurrencyRateInput.value) : NaN;
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
                // Calculate percentage of employee
                const devNetEarning = devEarning * userPercentage;

                // Update the dev earning input field with the calculated value
                if (devEarningInput) {
                    devEarningInput.value = devNetEarning.toFixed(2); // Format the result to two decimal places
                }
                // Calculate the payable amount by multiplying devNetEarning with the currency rate
                if (payableInput) {
                    const payableAmount = devNetEarning * currentCurrencyRate;
                    payableInput.value = payableAmount.toFixed(2); // Format the result to two decimal places
                }
            } else if (devEarningInput) {
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

            // Newest records first in the relationship dropdowns (options come
            // from Voyager ordered oldest-first; ids descend = latest on top).
            ['project_id', 'project_target_id', 'income_id', 'select_business_developer_id'].forEach(function (field) {
                var sel = document.querySelector('select[name="' + field + '"]');
                if (!sel) return;
                var opts = Array.prototype.slice.call(sel.options);
                opts.sort(function (a, b) {
                    if (a.value === '') return -1; // keep the empty placeholder on top
                    if (b.value === '') return 1;
                    return parseInt(b.value, 10) - parseInt(a.value, 10);
                });
                var selected = sel.value;
                opts.forEach(function (o) { sel.appendChild(o); });
                sel.value = selected;
            });

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
