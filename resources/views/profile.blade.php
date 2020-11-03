@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="profile">
        <div class="container">
            <div class="panel">
                <div class="panel-header">
                    <i class="fas fa-user color-accent header-icon"></i> Profile
                </div>

                @include('partials.message')

                <form action="{{ route('profile.update') }}" method="POST">
                    {{ csrf_field() }}

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-2 text-center">
                                    <img src="{{ $user->avatar_url }}" class="user-avatar">
                                </div>
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="text" name="email" class="form-control" value="{{ $user->email }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Notify By
                                                    <a href="#" class="popover-link" data-toggle="popover" data-trigger="hover" data-content="How to notify you if any issues occur with your bots">
                                                        <i class="far fa-question-circle"></i>
                                                    </a>
                                                </label>
                                                <select name="notify-by" class="form-control" onchange="togglePhoneField(this)">
                                                    <option value="email" {{ $user->notify_by === 'email' ? 'selected' : '' }}>Email</option>
                                                    <option value="text" {{ $user->notify_by === 'text' ? 'selected' : '' }}>Text</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3" id="phone-number">
                                            <div class="form-group">
                                                <label>Phone Number</label>
                                                <input type="text" name="phone-number" class="form-control" value="{{ $user->phone_number }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <button class="btn form-fix pull-right" type="submit">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <i class="fas fa-key color-accent header-icon"></i> Robinhood Credentials
                    <small class="text-muted">Update your Robinhood username & password. Saved credentials are not displayed for security</small>
                </div>
                <form action="{{ route('profile.update.robinhood') }}" method="POST">
                    {{ csrf_field() }}

                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="mfa">MFA Code</label>
                                <input type="text" name="mfa" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-offset-10 col-md-2">
                            <button type="submit" class="btn pull-right">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            togglePhoneField($('select[name=notify-by]'));

            $("input[name='phone-number']").keyup(function() {
                $(this).val($(this).val().replace(/^(\d{3})(\d{3})(\d)+$/, "($1) $2-$3"));
            });
        });

        /**
         * Shows/hides phone number fields depending on user setting
         */
        function togglePhoneField(field) {
            if ($(field).val() === 'text') {
                $('#phone-number').show();
                $("input[name='phone-number']").prop('required', true);

            } else {
                $('#phone-number').hide();
                $("input[name='phone-number']").prop('required', false);
            }
        }
    </script>
@endpush