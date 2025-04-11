@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{translate('Memberships')}}</h1>
        </div>
        @can('add_membership')
            <div class="col text-right">
                <a href="{{ route('membership.create') }}" class="btn btn-circle btn-primary">
                    <span>{{translate('Add New Membership')}}</span>
                </a>
            </div>
        @endcan
    </div>
</div>
<br>

<div class="card">
    <form class="" id="sort_memberships" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col text-center text-md-left">
                <h5 class="mb-md-0 h6">{{ translate('Memberships') }}</h5>
            </div>

            <div class="col-md-2">
                <div class="form-group mb-0">
                    <input type="text" class="form-control form-control-sm" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type & Enter') }}">
                </div>
            </div>
        </div>
    </form>
    <div class="card-body">
        <table class="table mb-0 aiz-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Title')}}</th>
                    <th data-breakpoints="lg">{{translate('Percent')}}</th>
                    <th data-breakpoints="lg">{{translate('Amount')}}</th>
                    <th class="text-right" width="10%">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($memberships as $key => $membership)
                <tr>
                    <td>
                        {{ ($key+1) + ($memberships->currentPage() - 1) * $memberships->perPage() }}
                    </td>
                    <td>
                        {{ $membership->name }}
                    </td>

                    <td>
                        {{ $membership->percent }}%
                    </td>
                    <td>
                        {{ format_price($membership->value) }}
                    </td>
                    <td class="text-right">
                        @can('edit_membership')
                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                href="{{ route('membership.edit', ['id' => $membership->id, 'lang' => env('DEFAULT_LANGUAGE')]) }}"
                                title="{{ translate('Edit') }}">
                                <i class="las la-edit"></i>
                            </a>
                        @endcan
                        @can('delete_membership')
                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('membership.destroy', $membership->id)}}" title="{{ translate('Delete') }}">
                                <i class="las la-trash"></i>
                            </a>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $memberships->links() }}
        </div>
    </div>
</div>

@endsection

@section('modal')
    @include('backend.inc.delete_modal')
@endsection
