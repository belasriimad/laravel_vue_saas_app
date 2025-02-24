@extends('admin.layouts.app')

@section('title')
    Histories
@endsection

@section('content')
    <div class="row my-5">
        <div class="col-md-3">
            @include('admin.layouts.sidebar')
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-white">
                    <h3 class="mt-2">
                        Histories ({{ $histories->count() }})
                    </h3>
                </div>
                <div class="card-body">
                    <div
                        class="table-responsive"
                    >
                        <table
                            class="table"
                        >
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Product</th>
                                    <th>Checked</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($histories as $key => $history)
                                    <tr>
                                        <td>{{ $key += 1 }}</td>
                                        <td>{{ $history->user->name }}</td>
                                        <td>{{ $history->product->name }}</td>
                                        <td>
                                            {{ $history->created_at->diffForHumans() }}
                                        </td>
                                        <td>
                                            <a href="#" 
                                                onclick="deleteItem({{$history->id}})"
                                                class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <form id="{{$history->id}}" action="{{route('admin.histories.destroy',$history->id)}}" method="post">
                                                @csrf
                                                @method("DELETE")
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@endsection