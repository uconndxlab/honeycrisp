<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentAccount;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * TODO: Make this much faster.
         */
        $kfsPath = base_path('database/kfs_acct_list.csv');
        $kfsAccounts = array_map('str_getcsv', file($kfsPath));
        array_shift($kfsAccounts);
        foreach($kfsAccounts as $data) {
            
            $fiscal_officer_netid = $data[2];
            $fiscal_officer_name = $data[3];

            $account_supervisor_netid = $data[4];
            $account_supervisor_name = $data[5];

            $account_name = $data[1];
            $account_number = $data[0];

            $expiration_date = $data[21];

            DB::transaction(function () use ($fiscal_officer_netid, $fiscal_officer_name, $account_supervisor_netid, $account_supervisor_name, $account_name, $account_number, $expiration_date) {
                $fiscal_officer = User::firstOrCreate(
                    ['netid' => $fiscal_officer_netid], // Data to search by
                    [
                        'name' => $fiscal_officer_name,
                        'email' => $fiscal_officer_netid . '@uconn.edu',
                        'password' => Hash::make('password')
                    ] // Data to create with
                );

                $account_supervisor = User::firstOrCreate(
                    ['netid' => $account_supervisor_netid], // Data to search by
                    [
                        'name' => $account_supervisor_name,
                        'email' => $account_supervisor_netid . '@uconn.edu',
                        'password' => Hash::make('password')
                    ] // Data to create with
                );

                $paymentAccount = PaymentAccount::firstOrCreate(
                    [
                        'account_number' => $account_number,
                        'account_type' => 'kfs'
                    ],
                    [
                        'account_name' => $account_name,
                        'expiration_date' => $expiration_date
                    ]
                );

                $paymentAccount->users()->wherePivotNotIn('role', ['authorized_user'])->sync([
                    $account_supervisor->id => ['role' => 'owner'],
                    $fiscal_officer->id => ['role' => 'fiscal_officer']
                ]);

                
            });
            Log::info('Imported KFS Account: ' . $account_name);
        }

        unset($kfsAccounts);

        $bannerCsv = base_path('database/banner_acct_list.csv');
        $bannerUserCsv = base_path('database/ext_uch_people.csv');
        $bannerAccounts = array_map('str_getcsv', file($bannerCsv));
        $bannerUsers = array_map('str_getcsv', file($bannerUserCsv));

        // Remove header rows
        array_shift($bannerAccounts);
        array_shift($bannerUsers);

        foreach($bannerAccounts as $data) {
            $account_name = $data[2];
            $account_number = $data[1];
            
            $pi_banner_id = $data[5];
            $pi_banner_name = $data[6];
            $pi = array_search($pi_banner_id, array_column($bannerUsers, 9));
            $pi_netid = $bannerUsers[$pi][1];

            $da_banner_id = $data[7];
            $da_banner_name = $data[8];
            $da = array_search($da_banner_id, array_column($bannerUsers, 9));
            $da_netid = $bannerUsers[$da][1];

            DB::transaction(function() use ($pi_netid, $pi_banner_name, $da_netid, $da_banner_name, $account_name, $account_number) {
                $pi = User::firstOrCreate(
                    ['netid' => $pi_netid],
                    [
                        'name' => $pi_banner_name,
                        'email' => $pi_netid . '@uconn.edu',
                        'password' => Hash::make('password')
                    ]
                );
    
                $da = User::firstOrCreate(
                    ['netid' => $da_netid],
                    [
                        'name' => $da_banner_name,
                        'email' => $da_netid . '@uconn.edu',
                        'password' => Hash::make('password')
                    ]
                );
    
                $paymentAccount = PaymentAccount::firstOrCreate(
                    [
                        'account_number' => $account_number,
                        'account_type' => 'uch'
                    ],
                    [
                        'account_name' => $account_name,
                        'expiration_date' => '2099-12-31'
                    ]
                );

                $paymentAccount->users()->wherePivotNotIn('role', ['authorized_user'])->sync([
                    $pi->id => ['role' => 'owner'],
                    $da->id => ['role' => 'fiscal_officer']
                ]);

                
            });
            Log::info('Imported Banner Account: ' . $account_name);
            
        }

    }
}
