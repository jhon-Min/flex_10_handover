<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Category;
use Illuminate\Console\Command;
use App\Models\ImportScriptHistory;
use Illuminate\Support\Facades\Config;
use App\Repositories\PartsDBAPIRepository;

class SyncCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:category';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'There are fetching category data from partdb api';

    public $partsdbapirepository, $PRODUCTS_PATH, $last_updated = null;

    public function __construct(PartsDBAPIRepository $partsdbapirepository)
    {
        $this->partsdbapirepository = $partsdbapirepository;
        parent::__construct();

        $this->PRODUCTS_PATH = Config::get('constant.PRODUCTS_PATH');
        $this->last_updated = date('Y-m-d');
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ini_set('memory_limit', '-1');
        $import_script = ImportScriptHistory::create(['start' => Carbon::now()]);

        //Authenticate user with system and obtain the session id and the response for next request.The session will be expired after 30 minutes.
        $this->partsdbapirepository->login();
        $import_script->login = 1;
        $import_script->save();

        //Get CED categories (Complete)
        echo "Start : Import categories \n";
        $this->importCategories();
        echo "End : Import categories \n\n";

        $import_script->categories = 1;
        $import_script->end = Carbon::now();
        $import_script->duration = $import_script->end->diffForHumans($import_script->start);
        $import_script->save();
        echo $import_script->end->diffForHumans($import_script->start);
    }

    protected function importCategories()
    {

        $db_categories = Category::all()->pluck('id')->toArray();
        $categories = $this->partsdbapirepository->getAllCategories();

        $category_array = [];
        foreach ($categories as $category) {

            $category_data = Category::firstOrCreate([
                'id' => $category->CategoryID,
                'name' => $category->CategoryName
            ]);

            $category_data->parent_id = $category->CategoryParentID ?? 0;
            $category_data->description = $category->CategoryDescription;
            $category_data->icon = $category->CategoryIcon;
            $category_data->image = $category->CategoryImage;
            $category_data->save();
        }
    }
}
