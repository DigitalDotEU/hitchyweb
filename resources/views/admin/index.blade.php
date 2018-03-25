@extends('layouts/app')

@section('content')
<div class="row admin">
    <div class="col-sm-10 col-sm-offset-1 adminColumn">
        <h1>Admin Page</h1>

        
        @foreach($res as $post)
            @if($post['submittedByAdmin'] == 0)
                <div class="well">
                    <p>Id: {{$post['id']}}</p>
                    <p>Name: {{$post['name']}}</p>
                    <p>Description: {{$post['description']}}</p>
                    <p>Author: {{$post['author']}}</p>

                    <a href="/adminSubmit/{{$post['id']}}">
                        <div class="btn btn-default" value="{{$post['id']}}">Publish</div>
                    </a>
                </div>
            @endif
        @endforeach

    </div>
</div>
@endsection