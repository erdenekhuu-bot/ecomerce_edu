@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-4">
                <h1 class="h3">{{ translate('All Pickup Points') }}</h1>
            </div>
            <div class="col-md-8 text-md-right">
                <a href="{{ route('pickup-point.create') }}" class="btn btn-primary">
                    <span>{{ translate('Add New Pickup Point') }}</span>
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('Point Name') }}</th>
                        <th>{{ translate('Manager') }}</th>
                        <th>{{ translate('Phone') }}</th>
                        <th>{{ translate('Location') }}</th>
                        <th>{{ translate('Status') }}</th>
                        <th data-breakpoints="md" class="text-right">{{ translate('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pickup_points as $key => $pickup_point)
                        <tr>
                            <td>{{ $key + 1 + ($pickup_points->currentPage() - 1) * $pickup_points->perPage() }}</td>
                            <td>
                                {{ $pickup_point->name }}
                            </td>
                            <td>
                                {{ $pickup_point->user->name }}
                            </td>
                            <td>
                                {{ $pickup_point->phone }}
                            </td>
                            <td>
                                {{ $pickup_point->location }}
                            </td>
                            <td>
                                @if ($pickup_point->status == 1)
                                    <span class="badge badge-inline badge-success">{{ translate('Active') }}</span>
                                @else
                                    <span class="badge badge-inline badge-warning">{{ translate('Inactive') }}</span>
                                @endif

                            </td>
                            <td class="text-right">
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                    href="{{ route('pickup-point.edit', $pickup_point->id) }}"
                                    title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                    data-href="{{ route('pickup-point.delete', $pickup_point->id) }}"
                                    title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $pickup_points->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @include('backend.inc.delete_modal')
@endsection
