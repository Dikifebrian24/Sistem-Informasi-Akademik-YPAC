@extends('layouts.app')

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3>ChartJS Chart</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item">Dashboard</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12 col-md-12 box-col-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h5>Welcome back, USername</h5>
                        </div>
                        <div class="card-body">
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-12 box-col-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h5>Penyandang disabilitast</h5>
                        </div>
                        <div class="card-body chart-block">
                            <canvas id="myBarGraph"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-12 box-col-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h5>Polar Chart</h5>
                        </div>
                        <div class="card-body chart-block chart-vertical-center">
                            <canvas id="myPolarGraph"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>

    <div class="icon-hover-bottom p-fixed fa-fa-icon-show-div opecity-0">
        <div class="container-fluid">
            <div class="row">
                <div class="icon-popup">
                    <div class="close-icon"><i class="icofont icofont-close"></i></div>
                    <div class="icon-first"><i class="icon-drupal fa-2x" id="icon_main"></i></div>
                    <div class="icon-class">
                        <label class="icon-title">Class</label><span id="fclass1">icon-drupal</span>
                    </div>
                    <div class="icon-last icon-last">
                        <label class="icon-title">Markup</label>
                        <div class="form-inline">
                            <div class="form-group">
                                <input class="inp-val form-control m-r-10" id="input_copy" type="text" value="" readonly="readonly">
                                <button class="btn btn-primary notification" onclick="myFunction()">Copy text</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- footer start-->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Convert the PHP data to JavaScript
        const disabilitasData = @json($disabilitasData);

        // Prepare the labels and data for the chart
        const labels = disabilitasData.map(item => item.nm_kelainan);
        const data = disabilitasData.map(item => item.total_disabilitas);

        // Define an array of colors (you can add as many as you need)
        const colors = [
            'rgba(75, 192, 192, 0.2)',  // Light Green
            'rgba(255, 99, 132, 0.2)',  // Light Red
            'rgba(255, 159, 64, 0.2)',  // Light Orange
            'rgba(153, 102, 255, 0.2)', // Light Purple
            'rgba(54, 162, 235, 0.2)',  // Light Blue
            'rgba(255, 205, 86, 0.2)',  // Light Yellow
            'rgba(201, 203, 207, 0.2)'  // Light Grey
        ];

        // Generate the backgroundColor array dynamically based on the number of bars
        const backgroundColors = disabilitasData.map((_, index) => colors[index % colors.length]);

        // Create the chart
        const ctx = document.getElementById('myBarGraph').getContext('2d');
        const myBarGraph = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Penyandang Disabilitas',
                    data: data,
                    backgroundColor: backgroundColors,  // Set dynamic colors
                    borderColor: 'rgba(0, 0, 0, 1)',  // Black border for all bars
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
