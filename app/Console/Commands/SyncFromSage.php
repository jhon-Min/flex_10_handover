<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\SageAPIRepository;
use Carbon\Carbon;

class SyncFromSage extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:sageapi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will import and update the customers info in the local database from the sage web services';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public $sageapirepository;

    public function __construct(SageAPIRepository $sageapirepository)
    {
        $this->sageapirepository = $sageapirepository;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('memory_limit', '-1');
        $current_time = Carbon::now();
        //Get Customer details
        echo "Start : Import Customer details \n";
        $this->customerInformation();
        echo "End : Import Customer details \n\n";

        //Get customer default address
        echo "Start : Import  customer default address \n";
        $this->customerDefaultAddress();
        echo "End : Import customer default address \n\n";
    }

    protected function customerInformation()
    {
        $type = 'YBPCINFO';
        if ($this->users->count() > 0) {
            foreach ($this->users as $key => $user) {
                $customer_info = $this->sageapirepository->getCustomerInformation($user, $type);
                print_r($customer_info);
                exit;
            }
        }
    }

    protected function customerDefaultAddress()
    {
        $type = 'YBPALST';
        if ($this->users->count() > 0) {
            foreach ($this->users as $key => $user) {
                $customer_info = $this->sageapirepository->getCustomerInformation($user, $type);
                print_r($customer_info);
                exit;
            }
        }
    }
}
