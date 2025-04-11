@extends('addon:multivendor::seller.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <form class="" id="sort_customers" action="" method="GET">
                    <div class="card-header row gutters-5">
                        <div class="col">
                            <h5 class="mb-0 h6">{{ translate('All Notifications') }}</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                           
                            <x-notification :notifications="$notifications" is_linkable/>
                            
                        </ul>
                        <div class="aiz-pagination">
                            {{ $notifications->appends(request()->input())->links() }}
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
