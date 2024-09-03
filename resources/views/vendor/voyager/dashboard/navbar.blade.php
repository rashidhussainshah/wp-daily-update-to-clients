<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<nav class="navbar navbar-default navbar-fixed-top navbar-top" style="background:#004891;" >
    <div class="container-fluid">
        <div class="navbar-header">
            <button class="hamburger btn-link">
                <span class="hamburger-inner" ></span>
            </button>
            @section('breadcrumbs')
                <ol class="breadcrumb hidden-xs">
                    @php
                        $segments = array_filter(explode('/', str_replace(route('voyager.dashboard'), '', Request::url())));
                        $url = route('voyager.dashboard');
                    @endphp
                    @if(count($segments) == 0)
                        <li class="active"><i class="voyager-boat"></i> {{ __('voyager::generic.dashboard') }}</li>
                    @else
                        <li class="active">
                            <a href="{{ route('voyager.dashboard')}}"><i class="voyager-boat"></i> {{ __('voyager::generic.dashboard') }}</a>
                        </li>
                        @foreach ($segments as $segment)
                            @php
                                $url .= '/'.$segment;
                            @endphp
                            @if ($loop->last)
                                <li>{{ ucfirst(urldecode($segment)) }}</li>
                            @else
                                <li>
                                    <a href="{{ $url }}">{{ ucfirst(urldecode($segment)) }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                </ol>
            @show
        </div>
        <ul class="nav navbar-nav @if (__('voyager::generic.is_rtl') == 'true') navbar-left @else navbar-right @endif">
            <li class="dropdown profile">
                <a href="#" class="dropdown-toggle text-right" data-toggle="dropdown" role="button"
                   aria-expanded="false"><img src="{{ $user_avatar }}" class="profile-img"> <span
                        class="caret"></span></a>
                <ul class="dropdown-menu dropdown-menu-animated">
                    <li class="profile-img">
                        <img src="{{ $user_avatar }}" class="profile-img">
                        <div class="profile-body">
                            <h5>{{ Auth::user()->name }}</h5>
                            <h6>{{ Auth::user()->email }}</h6>
                        </div>
                    </li>
                    <li class="divider"></li>
                        <?php $nav_items = config('voyager.dashboard.navbar_items'); ?>
                    @if(is_array($nav_items) && !empty($nav_items))
                        @foreach($nav_items as $name => $item)
                            <li {!! isset($item['classes']) && !empty($item['classes']) ? 'class="'.$item['classes'].'"' : '' !!}>
                                @if(isset($item['route']) && $item['route'] == 'voyager.logout')
                                    <form action="{{ route('voyager.logout') }}" method="POST">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-danger btn-block">
                                            @if(isset($item['icon_class']) && !empty($item['icon_class']))
                                                <i class="{!! $item['icon_class'] !!}"></i>
                                            @endif
                                            {{__($name)}}
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ isset($item['route']) && Route::has($item['route']) ? route($item['route']) : (isset($item['route']) ? $item['route'] : '#') }}" {!! isset($item['target_blank']) && $item['target_blank'] ? 'target="_blank"' : '' !!}>
                                        @if(isset($item['icon_class']) && !empty($item['icon_class']))
                                            <i class="{!! $item['icon_class'] !!}"></i>
                                        @endif
                                        {{__($name)}}
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    @endif
                </ul>
            </li>
        </ul>
    </div>
</nav>

<!-- Floating Buttons -->
<div class="floating-buttons">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#checkinModal"  onclick="focusTextarea('today_work_plan')">Check In</button>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#checkoutModal" onclick="focusTextarea('end_of_day_report')">Check Out</button>
</div>

<!-- Check-in Modal -->
<div class="modal fade" id="checkinModal" tabindex="-1" role="dialog" aria-labelledby="checkinModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkinModalLabel">Check In</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('checkin.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Display yesterday's work plan -->
                    <div class="form-group">
                        <label>Yesterday's Work Plan</label>
                        <textarea id="yesterdaysWorkPlan" class="form-control" readonly rows="5"></textarea>

                    </div>
                    <div class="form-group">
                        <label for="today_work_plan">Today's Work Plan</label>
                        <textarea id="today_work_plan" name="today_work_plan" class="form-control" required rows="10" cols="400"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Check In</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{--Check-out Modal --}}
<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkoutModalLabel">Check Out</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('checkout.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="end_of_day_report">End of Day Report</label>
                        <textarea id="end_of_day_report" name="end_of_day_report" class="form-control" required rows="10" cols="400"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="tomorrow_work_plan">Plan for Tomorrow</label>
                        <textarea id="tomorrow_work_plan" name="tomorrow_work_plan" class="form-control" required rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Check Out</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Example script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

    // jQuery code using $(document).ready()
    $(document).ready(function() {
        // AJAX request inside $(document).ready()
        $('#checkinModal').on('show.bs.modal', function (event) {
            // Fetch yesterday's work plan via AJAX
            $.ajax({
                url: '{{ route('get.yesterdays.plan') }}', // Replace with your route to fetch yesterday's plan
                type: 'GET',
                success: function(response) {
                    $('#yesterdaysWorkPlan').val(response.yesterdaysWorkPlan);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching yesterday\'s work plan:', error);
                }
            });
        });
    });

    function focusTextarea(id) {
        setTimeout(function () {
            $('#' + id).focus();
        }, 1000); // 1000 milliseconds = 1 second
        // Perform an AJAX request to get today's Clockify entries
        fetch('/admin/clockify/today-entries')
            .then(response => response.json())
            .then(data => {
                if (data.entries) {
                    document.getElementById('end_of_day_report').value = data.entries;
                } else if (data.error) {
                    console.error(data.error);
                }
            })
            .catch(error => {
                console.error('Error fetching Clockify entries:', error);
            });
    }
</script>
