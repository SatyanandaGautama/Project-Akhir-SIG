@extends('layouts.app')

@section('content')
    <style>
        :root {
            --color-primary: #3182ce;
            --color-danger: #e53e3e;
            --color-light: #edf2f7;
            --color-dark: #2d3748;
            --color-text: #4a5568;
            --color-background: #ffffff;
            --box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        /* Header Styles */
        .header {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--color-text);
            margin-bottom: 1rem;
        }

        /* Form Section Styles */
        .form-section {
            padding: 1.5rem;
            background-color: var(--color-background);
            border-radius: 0.5rem;
            box-shadow: var(--box-shadow);
            margin-bottom: 1.5rem;
        }

        .form-section.dark {
            background-color: var(--color-dark);
        }

        .form-section h2 {
            font-size: 1.25rem;
            font-weight: bold;
            color: var(--color-text);
            margin-bottom: 1rem;
        }

        .form-section.dark h2 {
            color: var(--color-light);
        }

        .form-section label {
            display: block;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: var(--color-text);
        }

        .form-section input[type="text"],
        .form-section input[type="email"],
        .form-section input[type="password"] {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #cbd5e0;
            border-radius: 0.25rem;
        }

        /* Button Styles */
        .btn-primary {
            background-color: var(--color-primary);
            color: var(--color-light);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #2b6cb0;
        }

        .btn-danger {
            background-color: var(--color-danger);
            color: var(--color-light);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-danger:hover {
            background-color: #c53030;
        }

        /* Confirmation Message */
        .form-section p {
            color: var(--color-text);
            margin-bottom: 1rem;
        }
    </style>

    <div class="py-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Update Profile Information -->
            <div class="form-section">
                <h2 class="header">Profile Information</h2>
                <form method="post" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')

                    <div>
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div>
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                            required>
                    </div>

                    <button class="btn-primary">Save</button>
                </form>
            </div>

            <!-- Update Password -->
            <div class="form-section">
                <h2 class="header">Update Password</h2>
                <form method="post" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <div>
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>

                    <div>
                        <label for="password">New Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <div>
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <button class="btn-primary">Save</button>
                </form>
            </div>

            <!-- Delete Account -->
            <div class="form-section">
                <h2 class="header">Delete Account</h2>
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <p>Are you sure you want to delete your account? This action cannot be undone.</p>
                    <div>
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <button class="btn-danger">Delete Account</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Confirmation for delete button
            document.querySelectorAll('.btn-danger').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    if (!confirm('Are you sure you want to delete your account?')) {
                        e.preventDefault();
                    }
                });
            });
        </script>
    @endpush
@endsection
