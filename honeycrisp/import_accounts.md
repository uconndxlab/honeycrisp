To import KFS/Banner accounts into the system, you will run a command that has a few requirements:

## Requirements

```text
database/banner_account_list.csv     ----- Banner Account List
database/kfs_account_list.csv        ----- KFS Account List
database/ext_uch_people.csv          ----- UCH People/Account List
```

## Command

```bash
php artisan db:seed --class="Database\Seeders\AccountSeeder"
```