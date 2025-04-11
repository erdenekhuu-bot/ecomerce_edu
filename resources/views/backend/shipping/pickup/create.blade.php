@extends('backend.layouts.app')

@section('content')
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Pickup Point Information') }}</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal"  method="POST" action="{{route('pickup-point.store')}}"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ translate('Point Name') }} <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{ translate('Name') }}" id="name" name="name"
                                value="{{ old('name') }}" class="form-control" required>
                            @error('name')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ translate('Manager') }} <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <select class="select2 form-control aiz-selectpicker" name="user_id" required
                                value="{{ old('user_id') }}" data-title="{{ translate('Select Manager') }}"
                                data-live-search="true" data-max-options="100" data-selected-text-format="count" >
                                @foreach ($staffs as $staff)
                                    <option value="{{ $staff->id }}">{{ $staff->name }}</option>
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
                                name="phone" value="{{ old('phone') }}" class="form-control" required>
                            @error('phone')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ translate('Status') }} <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <select class="select2 form-control aiz-selectpicker" name="status" required
                                value="{{ old('status') }}" data-title="{{ translate('Select Status') }}"
                                data-live-search="true" data-max-options="100" data-selected-text-format="count" >

                                <option value="1">{{ translate('Active') }}</option>
                                <option value="0">{{ translate('Inactive') }}</option>
                            </select>

                            @error('status')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{ translate('Location') }} <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <textarea name="location" required  rows="8" class="form-control"></textarea>

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

@section('script')
    <script type="text/javascript">
   
        // Send request using ajax
        // $(document).ready(function() {
        //     $('#pickupForm').on('submit', function(e) {
        //         e.preventDefault();
                
        //         $('#nameError').text('');
        //         $('#user_idError').text('');
        //         $('#phoneError').text('');
        //         $('#statusError').text('');
        //         $('#locationError').text('');

        //         let formData = {
        //             name: $("input[name=name]").val(),
        //             user_id: $("select[name=user_id]").val(),
        //             phone: $("input[name=phone]").val(),
        //             status: $("select[name=status]").val(),
        //             location: $("textarea[name=location]").val(),
        //             _token: $('meta[name="csrf-token"]').attr('content')
        //         };
        //        

        //         $.ajax({
        //             url: '{{ route('pickup-point.store') }}',
        //             method: 'POST',
        //             data: formData,
        //             success: function(response) {
        //                 if(response.success) {
                          
        //                     location.reload();
        //                     AIZ.plugins.notify('success', '{{ translate('Featured categories updated successfully') }}');
        //                     // toastr.success(response.message);
        //                 }
        //             },
        //             error: function(response) {
        //                 let errors = response.responseJSON.errors;
        //                 if(errors.name) {
        //                     $('#nameError').text(errors.name[0]);
        //                 }
        //                 if(errors.meta_title) {
        //                     $('#user_idError').text(errors.name[0]);
        //                 }
        //                 if(errors.logo) {
        //                     $('#phoneError').text(errors.phone[0]);
        //                 }
        //                 if(errors.logo) {
        //                     $('#statusError').text(errors.status[0]);
        //                 }
        //                 if(errors.logo) {
        //                     $('#locationError').text(errors.location[0]);
        //                 }
        //             }
        //         });
        //     });
        // });
    </script>
@endsection
