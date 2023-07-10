<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\ImportScriptHistory;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use App\Repositories\PartsDBAPIRepository;

class SyncProductImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:product-image';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'There are fetching product image data from partdb api';

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

        echo "Start : Import Product-image mapping \n" . Carbon::now() . "\n";
        $this->importProductImageMapping();
        echo "End : Import Product-image mapping \n" . Carbon::now() . "\n";
        $import_script->product_images = 1;
        $import_script->end = Carbon::now();
        $import_script->duration = $import_script->end->diffForHumans($import_script->start);
        $import_script->save();
        echo $import_script->end->diffForHumans($import_script->start);
    }

    // Done by min
    protected function importProductImageMapping()
    {

        $product_image_array = [];

        $location = 0;
        $exist_image = 0;
        $products = Product::selectRaw(DB::raw('CONCAT(product_nr, "_", brand_id) as product_brand, id'))->pluck('product_brand', 'id')->toArray();
        $stored_images = ProductImage::selectRaw(DB::raw('CONCAT(product_id, "_", image) as product_image'))->pluck('product_image')->toArray();

        foreach ($products as $product_id => $product) {
            list($product_nr, $brand_id) = explode("_", $product);
            $product_images =  $this->partsdbapirepository->getProductsImages($brand_id, $product_nr);

            foreach ($product_images as  $product_image) {
                try {
                    if (isset($product_image->ImagesLocation) && !empty($product_image->ImagesLocation)) {
                        $path = $this->PRODUCTS_PATH . $product_image->FileName;
                        $exist_image = Storage::disk('public')->exists($path);

                        $ext = $product_image->Extension;

                        if ($ext != "PDF") {
                            if (!$exist_image) {
                                $contents = file_get_contents($product_image->ImagesLocation);
                                $location = Storage::disk('public')->put($path, $contents);
                            }

                            if ($location || !in_array($product_id . "_" . $product_image->FileName, $stored_images)) {
                                $product_image_array[] = [
                                    'product_id' => $product_id,
                                    'image' =>  $product_image->FileName
                                ];

                                $stored_images[] = $product_id . "_" . $product_image->FileName;
                            }
                        }
                        echo " Uploading to Disk \n";
                    }
                } catch (\Exception $e) {

                    echo $e->getMessage() . "\n";
                }
            }

            if (count($product_image_array) >= 100) {
                echo "\n Images Imported : " . count($product_image_array) . "\n";
                ProductImage::insert($product_image_array);
                $product_image_array = [];
            }
        }

        if (count($product_image_array) > 0) {
            echo "\n Images Imported : " . count($product_image_array) . "\n";
            ProductImage::insert($product_image_array);
            $product_image_array = [];
        }
    }
}
