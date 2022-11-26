@extends(Theme::getThemeNamespace() . '::views.ecommerce.customers.master')
@section('content')
  @php Theme::set('pageName', __('Requests from Fans')) @endphp

  <div class="card">
    <div class="card-header">
      <h3>{{ __('Video Requests') }}</h3>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>{{ __('Requested By') }}</th>
              <th>{{ __('Date') }}</th>
              <th>{{ __('Amount') }}</th>
              <th>{{ __('Note') }}</th>
              <th class="text-right">{{ __('Status') }}</th>
              {{-- <th>{{ __('Actions') }}</th> --}}
            </tr>
          </thead>
          <tbody>
            @if (count($withdrawals) > 0)
              @foreach ($withdrawals as $withdrawal)
                <tr>
                  <td>
                    <a class="">
                        {{ $withdrawal->talent->name }}
                    </a>
                  </td>
                  <td>
                      {{ $withdrawal->note }}
                  </td>
                  <td>{{ $withdrawal->created_at->format('h:m d M Y') }} <br>
                    <small class="text-muted">{{ $withdrawal->created_at->diffForHumans() }}</small>
                  </td>
                  <td>{{ format_price($withdrawal->amount) }}</td>
                  <td class="text-right">{!! $withdrawal->status->toHtml() !!}</td>
                  {{-- <td>
                    <a class="btn btn-dark btn-sm"
                      href="{{ route('customer.withdrawals.view', $withdrawal->id) }}">{{ __('View') }}</a>
                  </td> --}}
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="5" class="text-center">{{ __('You have no transactions at this time!') }}</td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>
      <div class="mt-3 justify-content-center pagination_style1">
        {!! $withdrawals->links() !!}
      </div>
    </div>
  </div>
@endsection
