@extends('backend.layouts.app')

@section('content')
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Pickup Point Information') }}</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('pickup-point.update', $pickup_point) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ translate('Point Name') }} <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{ translate('Name') }}" id="name" name="name"
                                value="{{ $pickup_point->name }}" class="form-control" required>
                            @error('name')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ translate('Manager') }} <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <select class="select2 form-control aiz-selectpicker" name="user_id"
                                value="{{ $pickup_point->name }}" data-title="{{ translate('Select Manager') }}"
                                data-live-search="true" data-max-options="100" data-selected-text-format="count" required>
                                @foreach ($staffs as $staff)
                                    <option value="{{ $staff->id }}" @selected($pickup_point->user_id == $staff->id)>{{ $staff->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small
                                class="text-muted">{{ translate('Please choose manager from the added staffs for this pick up point. To add a new staff') }} <a href="{{ route('staffs.create') }}">{{ translate('click here') }}</a> </small>
                            @error('user_id')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ translate('Phone Number') }} <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="tel" placeholder="{{ translate('Phone Number') }}" id="name"
                                name="phone" value="{{ $pickup_point->phone }}" class="form-control" required>
                            @error('phone')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ translate('Status') }} <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <select class="select2 form-control aiz-selectpicker" name="status"
                                value="{{ old('status') }}" data-title="{{ translate('Select Status') }}"
                                data-live-search="true" data-max-options="100" data-selected-text-format="count" required>

                                <option value="1" @selected($pickup_point->status == 1)>{{ translate('Active') }}</option>
                                <option value="0" @selected($pickup_point->status == 0)>{{ translate('Inactive') }}</option>
                            </select>
                            @error('status')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{ translate('Location') }} <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <textarea name="location" required rows="8" class="form-control">{{ $pickup_point->location }}</textarea>
                            @error('location')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>


                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
