@extends('admin.layouts.layout')

@section('title', 'Ticket')

@section('content')

    <form action="{{route('admin.tickets.update', ['ticket' => $ticket])}}" method="post">
        @method('PUT')
        @csrf
        @if ($ticket->status == \App\Models\Ticket::STATUS_READ)
            <input type="hidden" name="status" value="{{\App\Models\Ticket::STATUS_IN_PROGRESS}}">
            <button type="submit" class="btn" style="background-color: #F59E0B">Move to In Progress</button>
        @elseif($ticket->status == \App\Models\Ticket::STATUS_IN_PROGRESS)
            <input type="hidden" name="status" value="{{\App\Models\Ticket::STATUS_RESOLVED}}">
            <button type="submit" class="btn" style="background-color: #10B981">Resolve</button>
        @elseif($ticket->status == \App\Models\Ticket::STATUS_RESOLVED)
            <input type="hidden" name="status" value="{{\App\Models\Ticket::STATUS_IN_PROGRESS}}">
            <button type="submit" class="btn" style="background-color: #F59E0B">Move To In Progress</button>
        @endif

    </form>

    <div class="container mt-5" style="display: inline-block">
        @foreach(\App\Models\Ticket::$columns as $column)

            <ul class="list-group list-group-horizontal">
                @if($column == 'user_id')
                    <li class="list-group-item list-group-item-light" style="width:50%"><b>{{ $column }}</b></li>
                    <li class="list-group-item" style="width:50%"><a
                            href="{{route('admin.users.show', ['user' => $ticket->user_id])}}">{{$ticket->user->name}}</a>
                    </li>
                @elseif($column == 'status')
                    <li class="list-group-item list-group-item-light" style="width:50%"><b>{{ $column }}</b></li>
                    <li class="list-group-item"
                        style="width:50%">{{\App\Models\Ticket::STATUS_MAP[$ticket->status]}}</li>
                @elseif($column == 'image')
                    <li class="list-group-item list-group-item-light" style="width:50%"><b>{{ $column }}</b></li>
                    <li class="list-group-item" style="width:50%"><img src="{{$ticket->$column}}"
                                                                       alt="{{$ticket->$column}}" class="zoom-image"
                                                                       width="200px"></li>
                @else
                    <li class="list-group-item list-group-item-light" style="width:50%"><b>{{ $column }}</b></li>
                    <li class="list-group-item" style="width:50%">{{$ticket->$column}}</li>
                @endif
            </ul>

        @endforeach

        <h2>User</h2>

        @foreach(\App\Models\User::$columns as $column)
            <ul class="list-group list-group-horizontal">
                <li class="list-group-item list-group-item-light" style="width:50%"><b>{{ $column }}</b></li>
                <li class="list-group-item" style="width:50%">{{$ticket->user->$column}}</li>
            </ul>
        @endforeach
        <h2>Messages</h2>
        <div class="container mt-3 col-12">
            @foreach($ticket->ticketMessages as $ticketMessage)
                <div role="alert"
                    @if($ticketMessage->is_question)
                        class="mt-1 alert alert-light col-6" style="height:auto;word-wrap:break-word;padding-bottom:4px">
                    @else
                        class="mt-1 alert alert-info col-6" style="height:auto;word-wrap:break-word; margin-left:49%;padding-bottom:4px">
                    @endif
                    {{$ticketMessage->message}}

                    @if($ticketMessage->image)
                            <br>
                        <img src="{{$ticketMessage->image}}" alt=""  class="zoom-image" style="max-width: 50%">
                    @endif

                    <p style="padding-bottom:0;margin-bottom:0;font-size:11px"

                       @if($ticketMessage->is_question)
                            align="right"
                        @endif
                    >
                        @if($ticketMessage->is_question)
                            {{$ticketMessage->created_at}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                             @if($ticketMessage->status == \App\Models\TicketMessages::STATUS_NEW)
                             fill="black"
                             @else
                             fill="blue"

                             @endif
                             class="bi bi-check-all" viewBox="0 0 16 16">
                            <path d="M8.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L2.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093L8.95 4.992zm-.92 5.14.92.92a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 1 0-1.091-1.028L9.477 9.417l-.485-.486z"/>
                        </svg></p>

                    @else

                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                             @if($ticketMessage->status == \App\Models\TicketMessages::STATUS_NEW)
                             fill="black"
                             @else
                             fill="blue"

                             @endif
                             class="bi bi-check-all" viewBox="0 0 16 16">
                            <path d="M8.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L2.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093L8.95 4.992zm-.92 5.14.92.92a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 1 0-1.091-1.028L9.477 9.417l-.485-.486z"/>
                        </svg>
                            {{$ticketMessage->created_at}}</p>

                        @endif
                </div>
            @endforeach

                <form action="{{route('admin.ticket-messages.create', ['ticket' => $ticket->id])}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <textarea type="text" placeholder="message" class="form-control" name="message">
                    </textarea>
                    <input type="file" placeholder="message" class="form-control" name="image" accept="image/*">
                    <button class="btn btn-success form-control" type="submit">Send</button>
                </form>
        </div>


    </div>
@endsection
