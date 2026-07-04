@extends('layouts.app')

@section('title', 'Profile - JudgeMate')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold tracking-tight text-white">Profile Settings</h1>
    </div>

    <div class="space-y-6">
        <div class="p-6 sm:p-8 border border-slate-800 bg-slate-900/20 shadow-xl backdrop-blur-sm rounded-2xl">
            <div class="max-w-xl text-slate-100">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-6 sm:p-8 border border-slate-800 bg-slate-900/20 shadow-xl backdrop-blur-sm rounded-2xl">
            <div class="max-w-xl text-slate-100">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="p-6 sm:p-8 border border-slate-800 bg-slate-900/20 shadow-xl backdrop-blur-sm rounded-2xl">
            <div class="max-w-xl text-slate-100">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection
