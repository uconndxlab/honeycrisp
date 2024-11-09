<div>

    <div class="input-group">
        <input id="payment_account_search" type="text" wire:model.live.debounce.300ms="search" placeholder="Select Payment Account..." class="form-control" />
        @if($selectedAccount)
            <div class="input-group-append">
                <a class="btn btn-danger" wire:click.prevent="removeAccount({{ $selectedAccount->id }})">Remove</a>
            </div>
        @endif
    </div>
    <input id="{{$inputId}}" type="hidden" name="{{ $inputName }}" value="{{ $selectedAccount ? $selectedAccount->id : '' }}">

        <div class="mt-2" style="max-height: 250px; overflow-y: auto;">
            @if(($filteredAccounts->count() && $selectedAccount == null))
                <ul class="list-group">
                    @foreach($filteredAccounts as $account)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{$account->account_name}} ({{$account->formatted()}})</span>

                            @if($selectedAccount && $selectedAccount->id == $account->id)
                                <a href="javascript:void(0);" class="btn btn-danger btn-sm" wire:click="removeAccount({{ $account->id }})">Remove</a>
                            @else
                                <a href="javascript:void(0)" class="btn btn-primary btn-sm" wire:click="selectAccount({{ $account->id }})">Select</a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
            @if (($filteredAccounts->count() == 0) && ($selectedAccount == null))
                <div class="alert alert-warning mt-2">No accounts found.</div>
            @endif
        </div>
</div>
