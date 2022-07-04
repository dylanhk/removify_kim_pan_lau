@if (count($data) > 0)
    @foreach ($data as $dataKey => $dataValue)
        <div>
            "{{ data_get($dataValue, 'id', 'n/a') }}", "{{ data_get($dataValue, 'name', 'n/a') }}",
            "{{ data_get($dataValue, 'title', 'n/a') }}", "{{ data_get($dataValue, 'company_name', 'n/a') }}"
        </div>
    @endforeach

    @if (count($companies) < 1)
        <strong><i>Company info not found</i></strong>
    @endif
@else
    <div>
        Data not found
    </div>
@endif
