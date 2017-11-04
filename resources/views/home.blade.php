@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <h2>Yours gnomes <a href="{{ route('gnome_create') }}"><span class="label label-success">Create NEW</span></a></h2>

                    @if (count($gnomes))
                        @foreach ($gnomes as $gnome)
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="col-md-3">
                                        <a href="{{ route('gnome_view', $gnome) }}" class="thumbnail" style="margin-bottom: 0 !important;">
                                          <img src="/avatars/{{ $gnome->getAvatarFileName() }}" alt="{{ $gnome->getName() }}">
                                        </a>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <h3>Name: {{ $gnome->getName() }}</h3>
                                            <h3>Strength: {{ $gnome->getStrength() }}</h3>
                                            <h3>Age: {{ $gnome->getAge() }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p>I'm sorry, you do not have any gnome! :(</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
