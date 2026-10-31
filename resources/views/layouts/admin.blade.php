@extends('layouts.app')

@section('content')
@php
    $courses = $courses ?? \App\Models\Course::all();
@endphp
<div class="h-full min-h-screen flex flex-row overflow-hidden relative bg-slate-50 text-slate-900" 
     x-data="{ 
        sidebarOpen: false,
        isModalOpen: false, 
        isPlaylistModalOpen: false, 
        isEditModalOpen: false 
     }" 
     @open-import-modal.window="isModalOpen = true" 
     @close-import-modal.window="isModalOpen = false"
     @open-playlist-modal.window="isPlaylistModalOpen = true"
     @close-playlist-modal.window="isPlaylistModalOpen = false">

    <!-- Side Navigation Bar -->
    @include('components.sidebar-admin')

    <!-- Main Content Wrapper -->
    <main class="ml-0 md:ml-64 flex-1 h-screen overflow-y-auto flex flex-col bg-slate-50 w-full min-w-0 transition-all duration-300">
        @include('components.header-admin')

        <div class="p-4 sm:p-6 lg:p-8 space-y-6 max-w-7xl w-full mx-auto">
            @yield('admin_content')
        </div>
    </main>

    <!-- Modal Admin Input & Live Preview YouTube Link -->
    @include('admin.partials.modal-import-lesson')

    <!-- Modal Sinkronisasi Playlist YouTube -->
    @include('admin.partials.modal-sync-playlist')

    <!-- Modal Edit Video Pelajaran -->
    @include('admin.partials.modal-edit-lesson')
</div>
@endsection
