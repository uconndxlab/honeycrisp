The `App\Jobs\NightlyKfs` handles the identification of internal orders with status "invoice", creates line items for each order item in those orders, and saves it to a KFS export file.

The Export file is saved as an `App\Models\Export` with type 'kfs'.

The job then saves this export reference to all of the orders in the export, and moves their 'status' to 'sent_to_kfs'.


## Run the Job

The NightlyKfs job depends on a schedule set in the `routes/console.php` file.  The job is scheduled for every night at midnight.

To run the job, it requires you have a scheduler running (to execute laravel to check for crons and schedules), and a queue worker running (to execute the job).

Both can be explained in detail in the [Laravel documentation](https://laravel.com/docs/11.x/scheduling).

But to run the scheduler locally (polls every 1 min):

```bash
php artisan schedule:work
```

To run the queue worker:

```bash
php artisan queue:work
```