@extends('layouts.app')

        <!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>
<body>
@section('content')
    <div class="container">
        <h1> Find Friend Breaks </h1>
        <div class="navbar-header">
            <!--Side bar nav-->
            <ul class="nav navbar-nav">
                <li><a href="/">Home</a></li>
                <li><a href="/manageCourses">Manage Courses</a></li>
                <li><a href="/manageFriends">Manage Friends</a></li>
            </ul>
        </div>
        <br/><br/><br/>

        <div id="block">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 id="boldText"> Friends on breaks </h3>
                    </div>
                    <div class="panel-body">

                        @if(isset($friendNames) && count($friendNames) > 0)
                            @foreach($friendNames as $name)
                                <h3>{{ $name }}</h3>
                                <br/>
                            @endforeach
                        @endif

                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div id="block">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <form id="breakForm" action="" method="post">
                        {{ csrf_field() }}
                        <h3 id="boldText">Search for friends with breaks:</h3>
                        <h3 id="boldText">Day:</h3>
                        <Select name="dayName">
                            <option value="monday">Monday</option>
                            <option value="tuesday">Tuesday</option>
                            <option value="wednesday">Wednesday</option>
                            <option value="thursday">Thursday</option>
                            <option value="friday">Friday</option>
                        </Select>

                        <h3 id="boldText">Start Time:</h3>
                        <Select name="startName">
                            @for($i = 1000; $i < 1700;)
                                <option value={{$i}}>{{ $i }}</option>
                                {{$i += 30}}
                                <option value={{$i}}>{{ $i }}</option>
                                {{$i += 70}}
                            @endfor
                            <option value={{$i}}>{{ $i }}</option>
                        </Select>

                        <h3 id="boldText">End Time:</h3>
                        <Select name="endName">
                            @for($i = 1000; $i < 1700;)
                                <option value={{$i}}>{{ $i }}</option>
                                {{$i += 30}}
                                <option value={{$i}}>{{ $i }}</option>
                                {{$i += 70}}
                            @endfor
                            <option value={{$i}}>{{ $i }}</option>
                        </Select>
                        <br/><br/>
                        <input type="submit" name="submitSearchBreaks" value="Search">
                    </form>
                </div>


            </div>
        </div>
    </div>
</body>
</html>
@endsection