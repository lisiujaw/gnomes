@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Gnome info</div>
                <div class="panel-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">Gnome create</div>
                        <div class="panel-body">
                            <form method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon">Name</span>
                                        <input class="form-control" type="text" placeholder="Gnome Name" aria-describedby="sizing-addon2" name="name">
                                    </div><br />

                                    <div class="input-group">
                                        <span class="input-group-addon">Strength</span>
                                        <input class="form-control" placeholder="Gnome Name" aria-describedby="sizing-addon2" name="strength">
                                    </div><br />

                                    <div class="input-group">
                                        <span class="input-group-addon">Age</span>
                                        <input class="form-control" placeholder="Gnome Age" aria-describedby="sizing-addon2" name="age">
                                    </div><br />

                                    <div class="input-group">
                                        <span class="input-group-addon">Avatar</span>
                                        <input id="input-b1" name="avatar" type="file" class="file">
                                    </div><br />
                                    <button type="submit" class="btn btn-success">CREATE</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
