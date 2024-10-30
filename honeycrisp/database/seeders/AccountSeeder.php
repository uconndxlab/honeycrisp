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
         * "ACCOUNT_NBR","ACCOUNT_NM","ACCT_FSC_OFC_UID","FISCAL_OFFCR_NM","ACCT_SPVSR_UNVL_ID","ACCT_SPVSR_NM","ORG_CD","ORG_NM","ACCT_TYP_CD","ACCT_TYP_NM","FUND_SID_CD","EVERIFY_IND","EQUIPMENT_OWNER_CD","GRAD_INDICATOR_CD","SUB_FUND_GRP_CD","ACCT_FRNG_BNFT_CD","LBR_BEN_RT_CAT_CD","FRNG_BNFT_ACCT_NBR","FIN_HGH_ED_FUNC_CD","ACCT_CREATE_DT","ACCT_EFFECT_DT","ACCT_EXPIRATION_DT","CONT_ACCOUNT_NBR","CONTR_CTRLACCT_NBR","ACCT_ICR_TYP_CD","FIN_SERIES_ID","ICR_ACCOUNT_NBR","CG_CFDA_NBR","ACCT_OFF_CMP_IND","ACCT_CLOSED_IND","CG_ACCT_RESP_ID"
         */
        $kfsPath = base_path('database/kfs_acct_list.csv');
        $kfsAccounts = array_map('str_getcsv', file($kfsPath));
        array_shift($kfsAccounts); // Remove header row
        
        foreach($kfsAccounts as $data) {
        
            $fiscal_officer_netid = $data[2];
            $fiscal_officer_name = $data[3];
        
            $account_supervisor_netid = $data[4];
            $account_supervisor_name = $data[5];
        
            $account_name = $data[1];
            $account_number = $data[0];
        
            $original_expiration_date = $data[21];
        
            $account_type_cd = $data[8];
        
            // Convert expiration date, default to '2099-12-31' if empty
            $expiration_date = !empty($original_expiration_date) ? date('Y-m-d', strtotime($original_expiration_date)) : '2099-12-31';
        
            // Skip accounts with past expiration dates
            if ($expiration_date < date('Y-m-d')) {
                continue;
            }
        
            DB::transaction(function () use ($fiscal_officer_netid, $fiscal_officer_name, $account_supervisor_netid, $account_supervisor_name, $account_name, $account_number, $expiration_date, $account_type_cd) {
                // Update or create fiscal officer user
                $fiscal_officer = User::updateOrCreate(
                    ['netid' => $fiscal_officer_netid], // Data to search by
                    [
                        'name' => $fiscal_officer_name,
                        'email' => $fiscal_officer_netid . '@uconn.edu',
                        'price_group' => 'internal'
                    ] // Data to update or create with
                );
        
                // Update or create account supervisor user
                $account_supervisor = User::updateOrCreate(
                    ['netid' => $account_supervisor_netid], // Data to search by
                    [
                        'name' => $account_supervisor_name,
                        'email' => $account_supervisor_netid . '@uconn.edu',
                        'price_group' => 'internal'
                    ] // Data to update or create with
                );
        
                // Update or create payment account
                $paymentAccount = PaymentAccount::updateOrCreate(
                    [
                        'account_number' => $account_number,
                        'account_type' => 'kfs'
                    ],
                    [
                        'account_name' => $account_name,
                        'expiration_date' => $expiration_date,
                        'account_category' => $account_type_cd

                    ]
                );
        
                // Sync users to the payment account
                $paymentAccount->users()->wherePivotNotIn('role', ['authorized_user'])->sync([
                    $account_supervisor->id => ['role' => 'owner'],
                    $fiscal_officer->id => ['role' => 'fiscal_officer']
                ]);
        
            });
        
            Log::info('Imported or updated KFS Account: ' . $account_name);
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
        
            $banner_category = $data[10];
        
            DB::transaction(function() use ($pi_netid, $pi_banner_name, $da_netid, $da_banner_name, $account_name, $account_number, $banner_category) {
                // First, handle the users
                $pi = User::updateOrCreate(
                    ['netid' => $pi_netid],
                    [
                        'name' => $pi_banner_name,
                        'email' => $pi_netid . '@uconn.edu',
                        'price_group' => 'internal'
                    ]
                );
        
                $da = User::updateOrCreate(
                    ['netid' => $da_netid],
                    [
                        'name' => $da_banner_name,
                        'email' => $da_netid . '@uconn.edu',
                        'price_group' => 'internal'
                    ]
                );
        
                // Now, update the payment account if it exists, or create a new one
                $paymentAccount = PaymentAccount::updateOrCreate(
                    [
                        'account_number' => $account_number,
                        'account_type' => 'uch'
                    ],
                    [
                        'account_name' => $account_name,
                        'expiration_date' => '2099-12-31',
                        'account_category' => $banner_category
                    ]
                );
        
                // Sync the users' roles
                $paymentAccount->users()->wherePivotNotIn('role', ['authorized_user'])->sync([
                    $pi->id => ['role' => 'owner'],
                    $da->id => ['role' => 'fiscal_officer']
                ]);
            });
        
            Log::info('Imported or updated Banner Account: ' . $account_name);
        }        

    }
}
