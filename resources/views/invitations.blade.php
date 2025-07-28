@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Invitations</h1>
        <p>Here you can manage your invitations.</p>

        @if($invitations->isEmpty())
            <p>No invitations found.</p>
        @else
            <ul>
                @foreach($invitations as $invitation)
                    <li>
                        {{ $invitation->email }}
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif

    </div>
@endsection
