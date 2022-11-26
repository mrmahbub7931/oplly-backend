@extends('core/base::layouts.master')
@section('content')
{!! Form::open(['url' => route('mobileapp.settings'), 'class' => 'main-setting-form']) !!}
<div class="max-width-1200">
    <div class="flexbox-annotated-section">
        <div class="flexbox-annotated-section-annotation">
            <div class="annotated-section-title pd-all-20">
                <h2>Mobile App Settings</h2>
            </div>
            <div class="annotated-section-description pd-all-20 p-none-t">
                <p class="color-note">Manage the content settings here</p>
            </div>
        </div>
        <div class="flexbox-annotated-section-content">
            <div class="wrapper-content pd-all-20">
                <label class="next-label">Version</label>
                <p class="type-subdued">Current Version for the app (for content validation)</p>
                <div class="form-group row">
                    <div class="col-sm-6 p-none-l">
                        <label class="text-title-field" for="version">Version</label>
                        <input type="text" class="next-input" name="version" id="version" value="{{ $setting->version ?? '1.0.0' }}">
                    </div>
                    <div class="col-sm-6 p-none-l">
                        <label class="text-title-field" for="platform">Platform</label>
                        <input type="text" class="next-input" name="platform" id="platform" value="{{ $setting->platform ?? 'all' }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flexbox-annotated-section">
        <div class="flexbox-annotated-section-annotation">
            <div class="annotated-section-title pd-all-20">
                <h2>Dashboard Content</h2>
            </div>
            <div class="annotated-section-description pd-all-20 p-none-t">
                <p class="color-note">Provide configuration for the home screen layout and content</p>
            </div>
        </div>
        <div class="flexbox-annotated-section-content">
            <div class="wrapper-content pd-all-20">
                <div class="form-group">
                    <label class="text-title-field" for="shopping_cart_enabled">Customer home screen
                    </label>
                    <textarea name="homepage" id="homepage" class="form-control"
                              style="overflow-y:scroll; height: 500px;">{{ $setting->homepage ?? '[]'}}</textarea>
                </div>
            </div>
        </div>
        <div class="flexbox-annotated-section-content">
            <div class="wrapper-content pd-all-20">
                <div class="form-group">
                    <label class="text-title-field" for="shopping_cart_enabled">Talent home screen
                    </label>
                    <textarea name="homepage_talent" id="homepage_talent" class="form-control"
                              style="overflow-y:scroll; height: 500px;">{{ $setting->homepage_talent ?? '[]' }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="flexbox-annotated-section">
        <div class="flexbox-annotated-section-annotation">
            <div class="annotated-section-title pd-all-20">
                <h2>Manage general app functionality</h2>
            </div>
            <div class="annotated-section-description pd-all-20 p-none-t">
                <p class="color-note"></p>
            </div>
        </div>
        <div class="flexbox-annotated-section-content">
            <div class="wrapper-content pd-all-20">
                <div class="form-group">
                    <label class="text-title-field" for="allow_push">Enable Push Notifications
                    </label>
                    <label class="hrv-label">
                        <input type="radio" name="allow_push" class="hrv-radio" value="1" @if ($setting && $setting->allow_push) checked @endif>{{ trans('core/setting::setting.general.yes') }}
                    </label>
                    <label class="hrv-label">
                        <input type="radio" name="allow_push" class="hrv-radio" value="0" @if ($setting && !$setting->allow_push) checked @endif>{{ trans('core/setting::setting.general.no') }}
                    </label>
                </div>
            </div>
        </div>
        <div class="flexbox-annotated-section-content">
            <div class="wrapper-content pd-all-20">
                <div class="form-group">
                    <label class="text-title-field" for="allow_feed">Enable Feed
                    </label>
                    <label class="hrv-label">
                        <input type="radio" name="allow_feed" class="hrv-radio" value="1" @if ($setting && $setting->allow_feed) checked @endif>{{ trans('core/setting::setting.general.yes') }}
                    </label>
                    <label class="hrv-label">
                        <input type="radio" name="allow_feed" class="hrv-radio" value="0" @if ($setting && !$setting->allow_feed) checked @endif>{{ trans('core/setting::setting.general.no') }}
                    </label>
                </div>
            </div>
        </div>
        <div class="flexbox-annotated-section-content">
            <div class="wrapper-content pd-all-20">
                <div class="form-group">
                    <label class="text-title-field" for="allow_live">Enable Book Live
                    </label>
                    <label class="hrv-label">
                        <input type="radio"
                               name="allow_live"
                               class="hrv-radio"
                               value="1"
                               @if ($setting && $setting->allow_live) checked @endif>{{ trans('core/setting::setting.general.yes') }}
                    </label>
                    <label class="hrv-label">
                        <input type="radio"
                               name="allow_live"
                               class="hrv-radio"
                               value="0"
                               @if ($setting && !$setting->allow_live) checked @endif>{{ trans('core/setting::setting.general.no') }}
                    </label>
                </div>
            </div>
        </div>
        <div class="flexbox-annotated-section-content">
            <div class="wrapper-content pd-all-20">
                <div class="form-group">
                    <label class="text-title-field" for="allow_causes">Enable Causes
                    </label>
                    <label class="hrv-label">
                        <input type="radio"
                               name="allow_causes"
                               class="hrv-radio"
                               value="1"
                               @if ($setting && $setting->allow_causes) checked @endif>{{ trans('core/setting::setting.general.yes') }}
                    </label>
                    <label class="hrv-label">
                        <input type="radio"
                               name="allow_causes"
                               class="hrv-radio"
                               value="0"
                               @if ($setting && !$setting->allow_causes) checked @endif>{{ trans('core/setting::setting.general.no') }}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="flexbox-annotated-section" style="border: none">
        <div class="flexbox-annotated-section-annotation">
            &nbsp;
        </div>
        <div class="flexbox-annotated-section-content">
            <button class="btn btn-info" type="submit">Save</button>
        </div>
    </div>
</div>
{!! Form::close() !!}
@endsection

@push('footer')
<script type="application/javascript">

        Canopy.initCodeEditor('homepage');
        Canopy.initCodeEditor('homepage_talent');

</script>
@endpush
