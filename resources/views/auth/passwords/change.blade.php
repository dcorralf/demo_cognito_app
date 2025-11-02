@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Change Password') }}</div>

                <div class="card-body">
                    <form method="POST"
{{--                          action="{{ route('cognito.form.change.password') }}">--}}
                        action="@auth
                                {{ route('cognito.form.change.password') }}
                            @else
                                {{ route('cognito.form.change.without.auth') }}
                            @endauth
                        ">

                        @csrf

                        @if(session('status') === 'error')
                            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                <ul class="p-0 m-0" style="list-style: none;">
                                    <li>{{ session('message')  }}</li>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @guest
                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end"></label>
                                    <div class="col-md-6">
                                        <input id="email" type="hidden" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ session('force_email') }}" autocomplete="email" required />

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                            </div>
                        @endguest

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Existing Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                                    autocomplete="password" required autofocus />

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="new_password" class="col-md-4 col-form-label text-md-end">{{ __('New Password') }}</label>

                            <div class="col-md-6">
                                <input id="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror"
                                    name="new_password" required autocomplete="new_password" />

                                @error('new_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="new_password_confirmation" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="new_password_confirmation" type="password" class="form-control"
                                    name="new_password_confirmation" required autocomplete="new_password_confirmation" />
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Save') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
