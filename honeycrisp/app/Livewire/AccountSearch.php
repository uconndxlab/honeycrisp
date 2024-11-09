<?php

namespace App\Livewire;


use Livewire\Component;

class AccountSearch extends Component
{
    public $search = '';
    public $accounts = [];
    public $selectedAccount = null;
    public $inputId;
    public $inputName;
    public $og_accounts = [];

    public function mount($accounts = [], $inputId = 'payment_account_id', $inputName = 'payment_account_id', $selectedAccount = null)
    {
        $this->inputId = $inputId;
        $this->inputName = $inputName;
        $this->selectedAccount = $selectedAccount;
        if ($selectedAccount) {
            $this->search = $selectedAccount->account_name . ' (' . $selectedAccount->formatted() . ')';
        }
        // Initialize accounts
        $this->accounts = $accounts;
        $this->og_accounts = $accounts;
    }

    public function selectAccount($accountId)
    {
        $selectedAccount = collect($this->accounts)
            ->firstWhere('id', $accountId);
        $this->search = $selectedAccount->account_name . ' (' . $selectedAccount->formatted() . ')';
        $this->selectedAccount = $selectedAccount;
    }

    public function removeAccount()
    {
        $this->selectedAccount = null;
        $this->search = '';
        $this->accounts = $this->og_accounts;

        
    }

    public function render()
    {
        // Filter accounts based on search input
        $filteredAccounts = collect($this->accounts)
            ->filter(function ($account) {
                return str_contains(strtolower($account['account_name']), strtolower($this->search)) ||
                       str_contains($account['account_number'], $this->search);
            });

        return view('livewire.account-search', [
            'filteredAccounts' => $filteredAccounts,
        ]);
    }
}
