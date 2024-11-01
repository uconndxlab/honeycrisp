<div>

    {{-- selected users --}}

    @if($selectedUsers)
        <ul class="list-group">
            @foreach($selectedUsers as $userId)
            {{-- skip the first one because it's the customer --}}

                @if($loop->first)
                    @continue
                @endif
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ $users->find($userId)->name }} ({{ $users->find($userId)->netid }})</span>
                    <a class="btn btn-danger btn-sm" wire:click="removeUser({{ $userId }})">Remove</a>
                </li>
            @endforeach
        </ul>
    @endif

    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search Users..." class="form-control" />

    {{-- need a hidden array field of selected users to submit with the form, name it whatever $name and $id are --}}
    @foreach($selectedUsers as $userId)
        <input type="hidden" name="{{ $name }}[]" value="{{ $userId }}">
    @endforeach

    @if ($search)
        <div class="mt-2">
            @if($users->count())
                <ul class="list-group">
                    @foreach($users as $user)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $user->name }} ({{ $user->netid }})</span>

                            <!-- if the user is already selected, show a button that says "Remove"  and wire:click to removeUser -->
                            @if(in_array($user->id, $selectedUsers))
                                <a href="javascript:void(0);" class="btn btn-danger btn-sm" wire:click="removeUser({{ $user->id }})">Remove</a>
                            @else
                                <!-- if the user is not selected, show a button that says "Add" and wire:click to addUser -->
                                <a href="javascript:void(0)" class="btn btn-primary btn-sm" wire:click="addUser({{ $user->id }})">Add To Order</a>
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
