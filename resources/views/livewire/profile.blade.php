@if (session()->has('success'))
    <div class="alert alert-success">

        {{ session('success') }}

    </div>
@endif

<form wire:submit="updateProfile">

    <div class="mb-3">

        <label>Name</label>

        <input type="text" class="form-control" wire:model="name">

        @error('name')
            <small class="text-danger">

                {{ $message }}

            </small>
        @enderror

    </div>

    <div class="mb-3">

        <label>Email</label>

        <input type="email" class="form-control" wire:model="email">

        @error('email')
            <small class="text-danger">

                {{ $message }}

            </small>
        @enderror

    </div>

    <div class="mb-3">

        <label>Username</label>

        <input type="text" class="form-control" wire:model="username">

        @error('username')
            <small class="text-danger">

                {{ $message }}

            </small>
        @enderror

    </div>

    <button class="btn btn-primary">

        Save Changes

    </button>

</form>
