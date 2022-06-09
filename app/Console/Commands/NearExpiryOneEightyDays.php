<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\User;
class NearExpiryOneEightyDays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quote:expiryoneeightydays';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Respectively send an exclusive quote to admin';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }  
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        app()->call('Modules\Notification\Http\Controllers\NotificationController@nearExpirySixtyDays');
        $this->info('Successfully sent quote to admin .');
    }
}