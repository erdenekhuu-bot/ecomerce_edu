@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Membership')}} {{isset($membership) ? 'засах' : 'нэмэх'}}</h5>
            </div>
            <div class="card-body">
                <form id="add_form" class="form-horizontal" action="{{isset($membership) ? route('membership.update', $membership->id) : route('membership.store') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">
                            {{translate('Membership Title')}}
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{translate('Membership Title')}}" onkeyup="makeSlug(this.value)" id="title" name="title" class="form-control"
                             value="{{isset($membership) ? $membership->name : ''}}"
                            required>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">
                            {{translate('Description')}}
                        </label>
                        <div class="col-md-9">
                            <textarea class="aiz-text-editor" name="description">{{isset($membership) ? $membership->description : ''}}</textarea>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Percent')}}</label>
                        <div class="col-md-9">
                            <input type="number" class="form-control" name="percent" placeholder="{{translate('Percent')}}" value="{{isset($membership) ? $membership->percent : ''}}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Amount')}}</label>
                        <div class="col-md-9">
                            <input type="number" class="form-control" name="amount" placeholder="{{translate('Amount')}}" value="{{isset($membership) ? $membership->value : ''}}">
                        </div>
                    </div>

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">
                            {{translate('Save')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function makeSlug(val) {
        let str = val;
        let output = str.replace(/\s+/g, '-').toLowerCase();
        $('#slug').val(output);
    }
</script>
@endsection
