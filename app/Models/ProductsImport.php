<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;
use Auth;
use Carbon\Carbon;
use Storage;

//class ProductsImport implements ToModel, WithHeadingRow, WithValidation
class ProductsImport implements ToCollection, WithHeadingRow, WithValidation, ToModel
{
    private $rows = 0;

    public function collection(Collection $rows)
    {
        $canImport = true;
        $user = Auth::user();
        if ($user->user_type == 'seller' && addon_is_activated('seller_subscription')) {
            if ((count($rows) + $user->products()->count()) > $user->shop->product_upload_limit
                || $user->shop->package_invalid_at == null
                || Carbon::now()->diffInDays(Carbon::parse($user->shop->package_invalid_at), false) < 0
            ) {
                $canImport = false;
                flash(translate('Please upgrade your package.'))->warning();
            }
        }

        if ($canImport) {
            foreach ($rows as $row) {
                $productId = [];
                $checked = Product::where('master_sku', $row['master_sku'])->first();
                if ($checked) {
                    $productId = $checked;
                    $productId->is_variant = 1;
                    $productId->save();
                }else{
                    $productId = Product::create([
                        'name' => $row['name'],
                        'description' => $row['description'],
                        'shop_id' => $row['shop_id'],
                        'main_category' => $row['category_id'],
                        'brand_id' => $row['brand_id'],
                        'lowest_price' => $row['price'],
                        'highest_price' => $row['price'],
                        'meta_title' => $row['meta_title'],
                        'meta_description' => $row['meta_description'],
                        'slug' => preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', strtolower($row['slug']))) . '-' . Str::random(5),
                        'master_sku' => $row['master_sku'],
                    ]);
                }
                
                ProductCategory::create([
                    'product_id' => $productId->id,
                    'category_id' => $row['category_id'],
                ]);
                $variationId = ProductVariation::create([
                    'product_id' => $productId->id,
                    'stock' => $row['stock'],
                    'sku' => $row['sku'],
                    'price' => $row['price'],
                    'code' => $row['code'],
                ]);

                $string = $row['code'];

                // Remove the trailing slash
                $string = rtrim($string, '/');
                
                // Split the string into an array by ':'
                $parts = explode(":", $string);
                
                // Convert the parts into integers
                $intList = array_map('intval', $parts);

                $p_variation_comb                         = new ProductVariationCombination;
                $p_variation_comb->product_id             = $productId->id;
                $p_variation_comb->product_variation_id   = $variationId->id;
                $p_variation_comb->attribute_id           = $intList[0];
                $p_variation_comb->attribute_value_id     = $intList[1];
                $p_variation_comb->save();
            }

            flash(translate('Products imported successfully'))->success();
        }
    }

    public function model(array $row)
    {
        ++$this->rows;
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }

    public function rules(): array
    {
        return [
            // Can also use callback validation rules
            'price' => function ($attribute, $value, $onFailure) {
                if (!is_numeric($value)) {
                    $onFailure('Unit price is not numeric');
                }
            }
        ];
    }

    public function downloadThumbnail($url)
    {
        try {
            $upload = new Upload;
            $upload->external_link = $url;
            $upload->type = 'image';
            $upload->save();

            return $upload->id;
        } catch (\Exception $e) {
        }
        return null;
    }

    public function downloadGalleryImages($urls)
    {
        $data = array();
        foreach (explode(',', str_replace(' ', '', $urls)) as $url) {
            $data[] = $this->downloadThumbnail($url);
        }
        return implode(',', $data);
    }
}
