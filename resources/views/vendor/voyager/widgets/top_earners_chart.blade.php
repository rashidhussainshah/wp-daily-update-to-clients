<div class="panel widget center bgimage"
     style="margin-bottom:0;overflow:hidden;background-image:url({{ voyager_asset('images/widget-backgrounds/03.png') }});">
    <div class="panel-content">
        <h4 class="chart-heading-text">Top 10 Earners</h4>
        <canvas id="topEarnersChart"></canvas>
    </div>
</div>

@section('chart-js-top-earners')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('topEarnersChart').getContext('2d');
        var chartData = {
            labels: {!! json_encode($chartData->pluck('name')) !!},
            datasets: [{
                label: 'Total Earnings',
                data: {!! json_encode($chartData->pluck('total_earnings')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        var topEarnersChart = new Chart(ctx, {
            type: 'bar',
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
