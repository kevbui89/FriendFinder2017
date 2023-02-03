@extends('layouts.app')


<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Manage Friends</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>
<body>
@section('content')
    <div class="container">
        <h1> Manage Friends </h1>
        <div class="navbar-header">
            <!--Side bar nav-->
            <ul class="nav navbar-nav">
                <li><a href="/">Home</a></li>
                <li><a href="/manageCourses">Manage Courses</a></li>
                <li><a href="/findFriendBreaks">Find Friend Breaks</a></li>
            </ul>
        </div>
        <br/><br/><br/>

        <div id="block">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 id="boldText"> Friends </h3>
                </div>
                <div class="panel-body">

                    @if(isset($friendNames) && count($friendNames) > 0 && isset($friendStatus) && count($friendStatus) > 0)
                        @for($i = 0; $i < count($friendStatus); $i++)
                            <h4 id="boldText">
                                {{ $friendNames[$i]->name.' '.$friendNames[$i]->program.' '.$friendStatus[$i]->status }}
                                @if($friendStatus[$i]->status === 'Request Received')
                                    <form method="post" action="">
                                        {{ csrf_field() }}
                                        <button id="addButton" type="submit" name="declineRequest"
                                                value="{{$friendNames[$i]->email}}">
                                            Decline
                                        </button>
                                        <button id="addButton" type="submit" name="acceptRequest"
                                                value="{{$friendNames[$i]->email}}">
                                            Accept
                                        </button>
                                    </form>
                                @elseif($friendStatus[$i]->status === 'Confirmed')
                                    <form method="post" action="">
                                        {{ csrf_field() }}
                                        <button id="addButton" type="submit" name="declineRequest"
                                                value="{{$friendNames[$i]->email}}">
                                            Delete
                                        </button>
                                    </form>

                            </h4>
                                @endif
                        @endfor
                    @else
                        <h4 id="boldText">
                            No friends
                        </h4>
                    @endif
                </div>
            </div>
        </div>

        <div id="block">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <form action="" method="post">
                        {{ csrf_field() }}
                        <h3 id="boldText">Search for friends:</h3>
                        <input id="textSearch" type="text" name="name">
                        <input type="submit" name="submitFriendSearch" value="Search">
                    </form>
                </div>

                <div class="panel-body">

                    @if(isset($searchNames) && count($searchNames) > 0)
                        @foreach($searchNames as $names)
                            <form method="post" action="">
                                {{ csrf_field() }}
                                <h4 id="boldText">
                                    {{ $names->name. ' ' .$names->program }}
                                    <button id="addButton" type="submit" name="addFriendBtn"
                                            value="{{$names->email}}">
                                        Add Friend
                                    </button>
                                </h4>
                            </form>
                        @endforeach
                        {{ $searchNames->appends(request()->input())->links() }}
                    @else
                        <p>No users with that name!</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</body>
@endsection
