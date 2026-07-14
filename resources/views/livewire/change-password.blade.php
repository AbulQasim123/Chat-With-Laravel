@if (session()->has('success'))
    <div class="alert alert-success">

        {{ session('success') }}

    </div>
@endif

<form wire:submit="updatePassword">

    <div class="mb-3">

        <label>Current Password</label>

        <input type="password" wire:model="current_password" class="form-control">

        @error('current_password')
            <small class="text-danger">

                {{ $message }}

            </small>
        @enderror

    </div>

    <div class="mb-3">

        <label>New Password</label>

        <input type="password" wire:model="new_password" class="form-control">

        @error('new_password')
            <small class="text-danger">

                {{ $message }}

            </small>
        @enderror

    </div>

    <div class="mb-3">

        <label>Confirm Password</label>

        <input type="password" wire:model="confirm_password" class="form-control">

        @error('confirm_password')
            <small class="text-danger">

                {{ $message }}

            </small>
        @enderror

    </div>

    <button class="btn btn-success">

        Update Password

    </button>

</form>
