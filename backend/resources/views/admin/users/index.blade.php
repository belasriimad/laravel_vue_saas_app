@extends('admin.layouts.app')

@section('title')
    Users
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
                        Users ({{ $users->count() }})
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
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Created</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $key => $user)
                                    <tr>
                                        <td>{{ $key += 1 }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            {{ $user->created_at->diffForHumans() }}
                                        </td>
                                        <td>
                                            <a href="#" 
                                                onclick="deleteItem({{$user->id}})"
                                                class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <form id="{{$user->id}}" action="{{route('admin.users.destroy',$user->id)}}" method="post">
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