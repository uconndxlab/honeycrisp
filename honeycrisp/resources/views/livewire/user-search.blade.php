<div>
    {{-- Selected users --}}
    @if($selectedUsers)
        <ul class="list-group">
            @foreach($selectedUsers as $user)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ $user->name }} ({{ $user->netid}})</span>
                    <a class="btn btn-danger btn-sm" wire:click="removeUser({{ $user['id'] }})">Remove</a>
                </li>
            @endforeach
        </ul>
    @endif

    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search Users..." class="form-control" />

    {{-- Hidden input array of selected users to submit with the form --}}
    @foreach($selectedUsers as $user)
        <input type="hidden" name="{{ $name }}[]" value="{{ $user->id }}">
    @endforeach

    @if ($search)
        <div class="mt-2">
            @if($users->count())
                <ul class="list-group">
                    @foreach($users as $user)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $user->name }} ({{ $user->netid }})</span>

                            @if(in_array($user->id, array_column($selectedUsers, 'id')))
                                <a href="javascript:void(0);" class="btn btn-danger btn-sm" wire:click="removeUser({{ $user->id }})">Remove</a>
                            @else
                                <a href="javascript:void(0)" class="btn btn-primary btn-sm" wire:click="addUser({{ $user->id }})">Select</a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="alert alert-warning mt-2">No users found.</div>
            @endif
        </div>
    @endif
</div>
