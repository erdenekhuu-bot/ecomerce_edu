<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ReviewCollection;
use App\Models\Product;
use App\Models\Review;
use App\Models\Upload;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index($product_id, Request $request)
    {
        $reviews = Review::where('product_id', $product_id)->with('user');

        if ($request->has('filterby')) {
            switch ($request->filterby) {
                case '5':
                    $reviews->where('rating', '5');
                    break;
                case '4':
                    $reviews->where('rating', '4');
                    break;
                case '3':
                    $reviews->where('rating', '3');
                    break;
                case '2':
                    $reviews->where('rating', '2');
                    break;
                case '1':
                    $reviews->where('rating', '1');
                    break;
                default:
                    break;
            }
        }
        if ($request->has('sortby')) {
            switch ($request->sortby) {
                case 'oldest':
                    $reviews->orderBy('created_at', 'asc');
                    break;
                case 'higer_rating':
                    $reviews->orderBy('rating', 'desc');
                    break;
                case 'lower_rating':
                    $reviews->orderBy('rating', 'asc');
                    break;
                default:
                    $reviews->latest();
                    break;
            }
        }

        return new ReviewCollection($reviews->paginate(5));
    }

    public function check_review_status($product_id)
    {
        $product = Product::find($product_id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => translate('Product not found')
            ], 200);
        }

        $old_review = Review::where('user_id', auth('api')->user()->id)->where('product_id', $product_id)->first();

        return response()->json([
            'success' => true,
            'product' => [
                'name' => $product->getTranslation('name'),
                'thumbnail_image' => api_asset($product->thumbnail_img),
            ],
            'oldReview' => [
                'rating' => $old_review->rating ?? null,
                'comment' => $old_review->comment ?? null,
                'image' => uploaded_asset($old_review->image ?? null),
            ],
        ], 200);
    }

    public function submit_review(Request $request)
    {
        $user = User::find(auth('api')->user()->id);
        $old_review = Review::where('user_id', auth('api')->user()->id)->where('product_id', $request->product_id)->first();

        if ($old_review && $old_review->status == 0) {
            return response()->json([
                'success' => false,
                'message' => translate('Your review has been disabled.')
            ], 200);
        }

        $product = Product::find($request->product_id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => translate('This product is not avilable.')
            ], 200);
        }

        // image upload 
        if ($request->hasFile('avatar')) {
            $upload = new Upload();
            $upload->file_original_name = null;
            $arr = explode('.', $request->file('avatar')->getClientOriginalName());

            for ($i = 0; $i < count($arr) - 1; $i++) {
                if ($i == 0) {
                    $upload->file_original_name .= $arr[$i];
                } else {
                    $upload->file_original_name .= "." . $arr[$i];
                }
            }

            $upload->file_name = $request->file('avatar')->store('uploads/all');
            $upload->user_id = $user->id;
            $upload->extension = $request->file('avatar')->getClientOriginalExtension();
            $upload->type = 'image';
            $upload->file_size = $request->file('avatar')->getSize();
            $upload->save();
        }
        // end of image upload
        $rt = Review::updateOrCreate(
            ['user_id' => auth('api')->user()->id, 'product_id' => $request->product_id],
            ['rating' => $request->rating, 'shop_id' => $product->shop_id, 'comment' => $request->comment, 'image' => $upload->id ?? null]
        );
        $product->rating = $product->reviews()->avg('rating');
        $product->save();

        $shop = $product->shop;
        if ($shop) {
            $shop->rating = $shop->reviews()->avg('rating');
            $shop->save();
        }

        cache_clear();

        return response()->json([
            'success' => true,
            'message' => translate('Your review has been submitted.')
        ], 200);
    }
}
