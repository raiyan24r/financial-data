<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Date</th>
            <th>Open</th>
            <th>High</th>
            <th>Low</th>
            <th>Close</th>
            <th>Volume</th>


    </thead>
    <tbody>
        @if (isset($paginatedSymbolPrices))

            @foreach ($paginatedSymbolPrices['data'] as $rowData)
                <tr>
                    <td>

                        {{ $loop->iteration }}
                    </td>
                    <td>

                        {{ $rowData['formattedDate'] }}
                    </td>
                    <td>

                        {{ $rowData['open'] }}
                    </td>

                    <td>

                        {{ $rowData['high'] }}
                    </td>
                    <td>

                        {{ $rowData['low'] }}
                    </td>
                    <td>

                        {{ $rowData['close'] }}
                    </td>
                    <td>

                        {{ $rowData['volume'] }}
                    </td>

                </tr>
            @endforeach

    </tbody>
</table>
@if ($paginatedSymbolPrices['total'] > $paginatedSymbolPrices['per_page'])
    <ul class="pager row flex justify-content-center">
        <li class=" p-3 list-unstyled">
            <div class="form-group">
                @if ($paginatedSymbolPrices['prev_page_url'] == '')
                    <button class="btn btn-default btn-trade page-item disabled" disabled>←Previous</button>
                @else
                    <button class="btn btn-warning btn-trade page-item" id="previousBtn"
                        onClick="hitPreviousBtn('{!! $paginatedSymbolPrices['prev_page_url'] !!}','{!! $symbol !!}','{!! $from !!}','{!! $to !!}')">←
                        Previous</button>
                @endif

            </div>
        </li>
        <li class="p-3 list-unstyled">
            <div class="form-group">
                @if ($paginatedSymbolPrices['next_page_url'] == '')
                    <button class="btn btn-default btn-trade page-item disabled" id="nextBtn">Next→</button>
                @else
                    <button class="btn btn-trade btn-success page-item" id="nextBtn"
                        onClick="hitNextBtn('{!! $paginatedSymbolPrices['next_page_url'] !!}','{!! $symbol !!}','{!! $from !!}','{!! $to !!}')">Next
                        →</button>
                @endif

            </div>
        </li>

    </ul>

    <div class="form-group border-bottom">
        <p class="text-center">Showing <span class="bd-highlight"> {{ count($paginatedSymbolPrices['data']) }} items
                per
                page</span> of Total <span class="bd-highlight">{{ $paginatedSymbolPrices['total'] }} </span> items
        </p>
    </div>
@endif
@endif
