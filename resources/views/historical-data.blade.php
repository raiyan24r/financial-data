<!DOCTYPE html>
<html>

<head>
    <title>Historical Data</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        $(function() {
            var dateFormat = "yy-mm-dd",

                from = $("#from")
                .datepicker({
                    changeMonth: true,
                    numberOfMonths: 2,
                    dateFormat: dateFormat,
                    maxDate: '+0m +0w'
                })
                .on("change", function() {
                    to.datepicker("option", "minDate", getDate(this));

                }),


                to = $("#to").datepicker({
                    changeMonth: true,
                    numberOfMonths: 1,
                    dateFormat: dateFormat,
                    maxDate: '+0m +0w'
                })
                .on("change", function() {
                    from.datepicker("option", "maxDate", getDate(this));
                });

            function getDate(element) {
                var date;
                let format = $(element).datepicker('option', 'dateFormat');
                let valueIsValid = false;
                try {
                    date = $.datepicker.parseDate(dateFormat, element.value);
                    valueIsValid = true;
                    $("#form-errors").empty();
                } catch (e) {
                    $errors = {
                        'error': [element.value + " is not a valid date format"]
                    }
                    alertErrors($errors);
                    date = null
                }

                return date;
            }
        });
    </script>
</head>

<body>
    <div class="container mt-4">

        <div class="card">
            <div class="card-header text-center font-weight-bold">
                Historical Data
            </div>
            <div class="card-body">

                <form class="mx-auto" id='form' onsubmit="manualSubmit();return false">
                    <div id="form-errors"></div>
                    <div class="mt-4 ">
                        <label for="company-symbol">Company Symbol</label>
                        <select name="company-symbol" id="company-symbol" style="min-width:20%;" required>
                            @foreach ($symbols as $company => $symbol)
                                <option data-name="{{ $company }}" value="{{ $symbol }}">{{ $symbol }}
                                </option>
                            @endforeach
                        </select>
                        <label for="email">Enter your email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="mb-4 mt-2">
                        <label for="from">From</label>
                        <input type="text" id="from" name="from" required>
                        <label for="to">to</label>
                        <input type="text" id="to" name="to" required>

                    </div>
                    <button id="submit" type='submit' class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
        <div class="my-4"><canvas id="myChart" width="200" height="200"></canvas></div>

        <div class="my-4" id="symbol-quotes-table">

        </div>

    </div>
</body>

</html>

<script>
    function alertErrors(errors) {

        errorsHtml = '<div class="alert alert-danger"><ul>';
        errorsHtml += '<p class="font-weight-bold">Something went wrong! Please try again</p>';
        $.each(errors, function(key, value) {
            errorsHtml += '<li>' + value[0] + '</li>';
        });
        errorsHtml += '</ul></div>';

        $('#form-errors').html(
            errorsHtml);
    }

    function manualSubmit() {
        var $this = $("#submit");
        BtnLoading($this);

        var from = $('#from').datepicker('getDate');
        var to = $('#to').datepicker('getDate');
        const offset = to.getTimezoneOffset()


        let dateTo = new Date(to.getTime() - (offset * 60 * 1000))
        let dateFrom = new Date(from.getTime() - (offset * 60 * 1000))
        dateTo = dateTo.toISOString().split('T')[0]
        dateFrom = dateFrom.toISOString().split('T')[0]

        var email = $('#email').val();
        var symbol = $('#company-symbol').find(":selected").val();
        var companyName = $('#company-symbol').find(":selected").data('name');



        var page = 1;
        $.ajax({
            url: '/api/symbol-data',
            type: 'post',
            dataType: 'json',
            data: {
                "email": email,
                "page": page,
                'companyName': companyName,
                "symbol": symbol,
                "from": dateFrom,
                "to": dateTo,
                'withChartData': 'true',
                'sendEmail': 'true'
            },
            success: function(responseData) {
                let getDataView = "<h1> Price Quotes </h1>";
                getDataView += responseData.view;
                $("#symbol-quotes-table").html(getDataView);

                renderChart(responseData.chartData.x_data, responseData.chartData.y_data.open, responseData
                    .chartData.y_data.close)
                BtnReset($this);
                return false;
            },
            error: function(responseData) {

                BtnReset($this);
                alertErrors(responseData.responseJSON.errors);
                return false;
            }
        });

    }

    function BtnLoading(elem) {
        $(elem).attr("data-original-text", $(elem).html());
        $(elem).prop("disabled", true);
        $(elem).html('<i class="spinner-border spinner-border-sm"></i> Loading...');
    }

    function BtnReset(elem) {
        $(elem).prop("disabled", false);
        $(elem).html($(elem).attr("data-original-text"));
    }

    function hitPreviousBtn(prevUrl, symbol, from, to) {
        var $this = $("#previousBtn");
        BtnLoading($this);
        $.ajax({
            url: '/api/symbol-data',
            type: 'post',
            dataType: 'json',
            data: {
                "page": prevUrl.replace('/?page=', ''),
                "symbol": symbol,
                "from": from,
                "to": to,
                'withChartData': 'false',
                'sendEmail': 'false'
            },
            success: function(responseData) {
                BtnReset($this)
                let getDataView = "<h1> Price Quotes </h1>";
                getDataView += responseData.view;
                $("#symbol-quotes-table").html(getDataView);
            },
            error: function(responseData) {
                BtnReset($this)
                alertErrors(responseData.responseJSON.errors);


            }
        });
    }


    function hitNextBtn(nextUrl, symbol, from, to) {

        var $this = $("#nextBtn");
        BtnLoading($this);

        $.ajax({
            url: '/api/symbol-data',
            type: 'post',
            dataType: 'json',
            data: {
                "page": nextUrl.replace('/?page=', ''),
                "symbol": symbol,
                "from": from,
                "to": to,
                'withChartData': 'false',
                'sendEmail': 'false'
            },
            success: function(responseData) {
                BtnReset($this)
                let getDataView = "<h1> Price Quotes </h1>";
                getDataView += responseData.view;
                $("#symbol-quotes-table").html(getDataView);
            },
            error: function(responseData) {
                BtnReset($this)
                alertErrors(responseData.responseJSON.errors);

            }
        });
    }

    function renderChart(labels, open_data, close_data) {
        let chartStatus = Chart.getChart("myChart");
        if (chartStatus != undefined) {
            chartStatus.destroy();
        }
        const ctx = document.getElementById('myChart');
        const myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Open Prices',
                        data: open_data,
                        borderColor: "rgb(255, 99, 132)",
                        backgroundColor: "rgb(255, 99, 132)",
                        yAxisID: 'y',
                    },
                    {
                        label: 'Close Prices',
                        data: close_data,
                        borderColor: "rgb(54, 162, 235)",
                        backgroundColor: "rgb(54, 162, 235)",
                        yAxisID: 'y1',
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                stacked: false,
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',

                        // grid line settings
                        grid: {
                            drawOnChartArea: false,
                        },
                    },
                }

            }
        });


    }

    $(document).ready(function() {
        $('#company-symbol').select2();

    });
</script>
