@extends('layouts.app')

@section('content')
    <!-- Top Navigation Bar -->
    @include('components.navbar-student')

    <!-- Main Content Body -->
    <div class="flex-1 flex flex-col">
        @yield('student_content')
    </div>
@endsection
