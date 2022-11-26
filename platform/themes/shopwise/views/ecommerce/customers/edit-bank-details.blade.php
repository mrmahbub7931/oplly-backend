@extends(Theme::getThemeNamespace() . '::views.ecommerce.customers.master')
@section('content')
  @php Theme::set('pageName', __('Account details')) @endphp
  {{--<div class="card" style="min-height: 0">
    <div class="card-header">
      <h3>Wallet</h3>
    </div>

    <div class="card-body">
        <div class="d-flex justify-content-between" style="max-width: 500px; margin: 0 auto">
            <div>
                <p>Balance: <span style="font-size: 1.4em">£1000</span></p>
            </div>
            <div>
                <button class="btn btn-sm btn-primary">Request a Withdrawal</button>
            </div>
        </div>
    </div>
  </div>--}}
  <div class="card">
    <div class="card-header">
      <h3>{{ __('My Bank information') }}</h3>
    </div>

    <div class="card-body">
      {!! Form::open(['route' => 'talent.edit-bank-details']) !!}
      <div class="form-group nice--input">
        <label for="bank_account_name">{{ __('Account Holder Name') }}:</label>
        <input id="bank_account_name" type="text" class="form-control" name="bank_account_name"
          value="{{ auth('customer')->user()->talent->bank_account_name }}">
      </div>
      {!! Form::error('bank_account_name', $errors) !!}

      <div class="form-group nice--input">
        <label for="branch_name">{{ __('Branch Name') }}:</label>
        <input id="branch_name" type="text" class="form-control" name="branch_name"
          value="{{ auth('customer')->user()->talent->branch_name }}">
      </div>
      {!! Form::error('branch_name', $errors) !!}

      <div class="form-group nice--input">
        <label for="bank_name">{{ __('Bank Name') }}:</label>
        <input id="bank_name" type="text" class="form-control" name="bank_name"
          value="{{ auth('customer')->user()->talent->bank_name }}">
      </div>
      {!! Form::error('bank_name', $errors) !!}

      <div class="form-group nice--input">
        <label for="bank_country">{{ __('Bank Country') }}:</label>
        <input id="bank_country" type="text" class="form-control" name="bank_country"
          value="{{ auth('customer')->user()->talent->bank_country }}">
      </div>
      {!! Form::error('bank_country', $errors) !!}

      <div class="form-group nice--input">
        <label for="bank_account_no">{{ __('Bank Account No') }}:</label>
        <input id="bank_account_no" type="text" class="form-control" name="bank_account_no"
          value="{{ auth('customer')->user()->talent->bank_account_no }}">
      </div>
      {!! Form::error('bank_account_no', $errors) !!}

      <div class="form-group nice--input">
        <label for="bank_iban">{{ __('IBAN') }}:</label>
        <input id="bank_iban" type="text" class="form-control" name="bank_iban"
          value="{{ auth('customer')->user()->talent->bank_iban }}">
      </div>
      {!! Form::error('bank_iban', $errors) !!}


      <div class="form-group nice--input">
        <label for="bank_swift">{{ __('SWIFT/BIC') }}:</label>
        <input id="bank_swift" type="text" class="form-control" name="bank_swift"
          value="{{ auth('customer')->user()->talent->bank_swift }}">
      </div>
      {!! Form::error('bank_swift', $errors) !!}




      <div class="form-group  nice--input text-center mt-4">
        <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
@endsection
