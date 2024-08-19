<div class="panel widget center bgimage"
     style="margin-bottom:0;overflow:hidden;background-image:url({{ voyager_asset('images/widget-backgrounds/02.png') }});">
    <div class="panel-content">
        <div class="d-flex justify-content-between">
            <h4 class="chart-heading-text">User Payments Chart</h4>
            <form method="GET" class="d-flex">
                <select name="user_id" onchange="this.form.submit()">
                    <option value="">Select User</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ $user->id == $selectedUser ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <canvas id="paymentsChart"></canvas>
    </div>
</div>

@section('chart-js-user-payments')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('paymentsChart').getContext('2d');
        var chartData = {
            labels: {!! json_encode($data->pluck('date')) !!},
            datasets: [{
                label: 'Total Earnings',
                data: {!! json_encode($data->pluck('total_earning')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                yAxisID: 'y'
            }, {
                label: 'Developer Earnings',
                data: {!! json_encode($data->pluck('dev_earning')) !!},
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
                yAxisID: 'y'
            }, {
                label: 'Paid Amount',
                data: {!! json_encode($data->pluck('paid')) !!},
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1,
                yAxisID: 'y'
            }, {
                label: 'Payable Amount',
                data: {!! json_encode($data->pluck('payable')) !!},
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1,
                yAxisID: 'y'
            }]
        };

        var paymentsChart = new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += context.parsed.y.toFixed(2);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
