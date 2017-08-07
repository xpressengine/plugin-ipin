@section('page_title')
    <h2>I-PIN</h2>
@endsection

@section('page_description')
    setting for I-PIN
@endsection

<div class="panel">
    <div class="panel-body">
        <form method="post" action="{{ route('setting.plugin.ipin') }}">
            {{ csrf_field() }}
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="test" value="true" {{ $config->get('test') === true ? 'checked' : '' }}> TEST 모드
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>상점 코드</label>
                                <input type="text" class="form-control" name="code" value="{{ $config->get('code') ?: Request::old('code') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">{{ xe_trans('xe::save') }}</button>
        </form>
    </div>
</div>
