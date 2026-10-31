@extends('layouts.app')

@section('content')
    <!-- Top Navigation Bar -->
    @include('components.navbar-student')

    <!-- Main Content Body -->
    <div class="flex-1 flex flex-col pb-20 md:pb-0 w-full min-w-0 bg-slate-50">
        @yield('student_content')
    </div>
@endsection
