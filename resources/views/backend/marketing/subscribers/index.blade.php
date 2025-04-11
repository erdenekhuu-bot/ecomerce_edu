@extends('backend.layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('All Subscribers')}}</h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Email')}}</th>
                    <th>{{translate('Date')}}</th>
                    <th>{{translate('Action')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subscribers as $key => $subscriber)
                  <tr>
                      <td>{{ ($key+1) + ($subscribers->currentPage() - 1)*$subscribers->perPage() }}</td>
											<td>{{ $subscriber->email }}</td>
                      <td>{{ date('d-m-Y', strtotime($subscriber->created_at)) }}</td>
                      <td class="">
                       
                       
                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('subscriber.delete', $subscriber->id)}}" title="{{ translate('Delete') }}">
                                <i class="las la-trash"></i>
                            </a>
                      
                    </td>
                  </tr>
                @endforeach
            </tbody>
        </table>
        <div class="clearfix">
            <div class="pull-right">
                {{ $subscribers->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
@section('modal')
    @include('backend.inc.delete_modal')
@endsection