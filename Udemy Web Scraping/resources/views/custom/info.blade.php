<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Learn4Free Analytics</title>
    <link rel="stylesheet" href="https://learn4free.fivescream.com/bootstrap-4/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
</head>
<body>
<div class="container py-5">
<h2>Analytics</h2>
    <div class="my-5">
        <table class="table table-striped table-border">
            <thead>
                <th>Visits</th>
                <th>Count</th>
            </thead>
            @foreach($new_courses_count as $ind => $course_count)
            <tr>
                <td>{{ $ind }}</td>
                <td>{{ $course_count }}</td>
            </tr>
            @endforeach
        </table>
    </div>
    <canvas id="myChart"></canvas>
    <div class="row">
        <div class="my-5 col-sm-6">
            <h4 class="mt-3">Last Visited Courses</h4>
            <table class='table table-striped'>
                <thead>
                    <th>Course Name</th>
                    <th>Visits</th>
                    <th>Last Updated</th>
                </thead>
                @foreach($last_visited_courses as $last_visited_course)
                    <tr>
                        <td>{{ $last_visited_course->title }}</td>
                        <td>{{ $last_visited_course->visits }}</td>
                        <td>{{ $last_visited_course->updated_at }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="my-5 col-sm-6">
            <h4 class="mt-3">Top Courses</h4>
            <ol>
            @foreach($top_courses as $top_course)
                <li><p>{{ $top_course->title }}({{ $top_course->visits }} hits)</p></li>
            @endforeach
            </ol>
        </div>
    </div>
</div>
<script>
var ctx = document.getElementById('myChart').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'bar',

    // The data for our dataset
    data: {
        labels: [
            @foreach($visits as $visit)
                '{{ str_replace("visits_on_","",$visit->name) }}',
            @endforeach
            ],
        datasets: [{
            label: 'Clicks',
            backgroundColor: '#ACEBFF',
            borderColor: '#98BDFF',
            data: [
                @foreach($visits as $visit)
                    '{{ $visit->value }}',
                @endforeach
            ]
        }]
    },

    // Configuration options go here
    options: {}
});
</script>
</body>
</html>