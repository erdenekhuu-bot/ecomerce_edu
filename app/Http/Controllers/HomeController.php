<?php

namespace App\Http\Controllers;

use App\Http\Resources\ManualPaymentResource;
use App\Models\Addon;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Language;
use App\Models\ManualPaymentMethod;
use App\Models\Page;
use App\Models\Product;
use Cache;
use Illuminate\Http\Request;
use Route;

class HomeController extends Controller
{
    public function index(Request $request, $slug = null)
    {
        $meta = [
            'meta_title' => get_setting('meta_title'),
            'meta_description' => get_setting('meta_description'),
            'meta_image' => api_asset(get_setting('meta_image')),
            'meta_keywords' => get_setting('meta_keywords'),
        ];
        $meta['meta_title'] = $meta['meta_title'] ? $meta['meta_title'] : config('app.name');

        if (Route::currentRouteName() == 'product') {
            $product = Product::where('slug', $slug)->first();
            if ($product) {
                $meta['meta_title'] = $product->meta_title ? $product->meta_title : $meta['meta_title'];
                $meta['meta_description'] = $product->meta_description ? $product->meta_description : $meta['meta_description'];
                $meta['meta_image'] = $product->meta_image ? api_asset($product->meta_image) : $meta['meta_image'];
            }
        } elseif (Route::currentRouteName() == 'products.category') {
            $category = Category::where('slug', $slug)->first();
            if ($category) {
                $meta['meta_title'] = $category->meta_title ? $category->meta_title : $meta['meta_title'];
                $meta['meta_description'] = $category->meta_description ? $category->meta_description : $meta['meta_description'];
                $meta['meta_image'] = $category->meta_image ? api_asset($category->meta_image) : $meta['meta_image'];
            }
        } elseif (Route::currentRouteName() == 'blog.details') {
            $blog = Blog::where('slug', $slug)->first();
            if ($blog) {
                $meta['meta_title'] = $blog->meta_title ? $blog->meta_title : $meta['meta_title'];
                $meta['meta_description'] = $blog->meta_description ? $blog->meta_description : $meta['meta_description'];
                $meta['meta_image'] = $blog->meta_image ? api_asset($blog->meta_image) : $meta['meta_image'];
                $meta['meta_keywords'] = $blog->meta_keywords ? $blog->meta_keywords : $meta['meta_keywords'];
            }
        } elseif ($slug) {
            $page = Page::where('slug', $slug)->first();
            if ($page) {
                $meta['meta_title'] = $page->meta_title ? $page->meta_title : $meta['meta_title'];
                $meta['meta_description'] = $page->meta_description ? $page->meta_description : $meta['meta_description'];
                $meta['meta_image'] = $page->meta_image ? api_asset($page->meta_image) : $meta['meta_image'];
                $meta['meta_keywords'] = $page->keywords ? $page->keywords : $meta['meta_keywords'];
            }
        }

        $settings = [
            'appName' => get_setting('site_name'),
            'appMetaTitle' => get_setting('meta_title'),
            'appMetaDescription' => get_setting('meta_description'),
            'appLogo' => get_setting('header_logo') ? api_asset(get_setting('header_logo')) : static_asset('assets/img/logo.svg'),
            'appUrl' => getBaseURL(),
            'demoMode' => env('DEMO_MODE') == "On" ? true : false,
            'cacheVersion' => get_setting('force_cache_clear_version'),
            'appLanguage' => env('DEFAULT_LANGUAGE'),
            'allLanguages' => Language::where('status', 1)->get(['name', 'code', 'flag', 'rtl']),
            // 'allCurrencies' => Currency::all(),
            'availableCountries' => Country::where('status', 1)->pluck('code')->toArray(),
            'primaryColor'=>get_setting('base_color', '#e62d04'),
            'shop_registration_message' => [
                'shop_registration_message_title' => str_replace('&amp;', '&', str_replace('&nbsp;', ' ', strip_tags(get_setting('shop_registration_message_title')))),
                'shop_registration_message_content' => str_replace('&amp;', '&', str_replace('&nbsp;', ' ', strip_tags(get_setting('shop_registration_message_content')))),
            ],
            'cookie_message' => [
                'cookie_title' => str_replace('&amp;', '&', str_replace('&nbsp;', ' ', strip_tags(get_setting('cookie_title')))),
                'cookie_description' => str_replace('&amp;', '&', str_replace('&nbsp;', ' ', strip_tags(get_setting('cookie_description')))),
            ],
            'paymentMethods' => [
                [
                    'status' => get_setting('qpay_payment'),
                    'code' => 'qpay',
                    'name' => 'Qpay',
                    'img' => static_asset("assets/img/cards/qpay.png")
                ],
                [
                    'status' => get_setting('golomt_payment'),
                    'code' => 'golomt',
                    'name' => 'Дансаар төлөх',
                    'img' => static_asset("assets/img/cards/dansaar.png")
                ],
                [
                    'status' => get_setting('storepay_payment'),
                    'code' => 'storepay',
                    'name' => 'Store Pay',
                    'img' => static_asset("assets/img/icons/storepay.svg")
                ],
                [
                    'status' => get_setting('pocket_payment'),
                    'code' => 'pocket',
                    'name' => 'Pocket',
                    'img' => static_asset("assets/img/icons/pocket.svg")
                ],
            ],
            'offlinePaymentMethods' => [],
            'addons' => Cache::remember('web_addons', 86400, function () {
                return Addon::select('unique_identifier', 'version', 'activated')->get();
            }),
            'general_settings' => [
                'product_comparison' => get_setting('product_comparison'),
                'wallet_system' => get_setting('wallet_system'),
                'club_point' => get_setting('club_point'),
                'club_point_convert_rate' => get_setting('club_point_convert_rate'),
                'conversation_system' => get_setting('conversation_system'),
                'sticky_header' => get_setting('sticky_header'),
                'affiliate_system' => get_setting('affiliate_system'),
                'delivery_boy' => get_setting('delivery_boy'),
                'support_chat' => get_setting('support_chat')==1 ? true : false,
                'pickup_point' => get_setting('pickup_point')==1 ? true : false,
                'chat' => [
                    'customer_chat_logo' => api_asset(get_setting('customer_chat_logo')),
                    'customer_chat_name' => get_setting('customer_chat_name'),
                ],
                'social_login' => [
                    'google' => get_setting('google_login'),
                    'facebook' => get_setting('facebook_login'),
                    'twitter' => get_setting('twitter_login'),
                ],
                'currency' => [
                    'code' => Cache::remember('system_default_currency_symbol', 86400, function () {
                        return Currency::find(get_setting('system_default_currency'))->symbol;
                    }),
                    'decimal_separator' => get_setting('decimal_separator'),
                    'symbol_format' => get_setting('symbol_format'),
                    'no_of_decimals' => get_setting('no_of_decimals'),
                    'truncate_price' => get_setting('truncate_price'),
                ]
            ],
            'banners' => [
                "login_page" => [
                    "img" => api_asset(get_setting('login_page_banner')),
                    "link" => get_setting('login_page_banner_link')
                ],
                "delivery_boy_login_page" => [
                    "img" => api_asset(get_setting('delivery_boy_login_page_banner')),
                    "link" => get_setting('delivery_boy_login_page_banner_link')
                ],
                "registration_page" => [
                    "img" => api_asset(get_setting('registration_page_banner')),
                    "link" => get_setting('registration_page_banner_link')
                ],
                "forgot_page" => [
                    "img" => api_asset(get_setting('forgot_page_banner')),
                    "link" => get_setting('forgot_page_banner_link')
                ],
                "listing_page" => [
                    "img" => api_asset(get_setting('listing_page_banner')),
                    "link" => get_setting('listing_page_banner_link')
                ],
                "product_page" => [
                    "img" => api_asset(get_setting('product_page_banner')),
                    "link" => get_setting('product_page_banner_link')
                ],
                "checkout_page" => [
                    "img" => api_asset(get_setting('checkout_page_banner')),
                    "link" => get_setting('checkout_page_banner_link')
                ],
                "dashboard_page_top" => [
                    "img" => api_asset(get_setting('dashboard_page_top_banner')),
                    "link" => get_setting('dashboard_page_top_banner_link')
                ],
                "dashboard_page_bottom" => [
                    "img" => api_asset(get_setting('dashboard_page_bottom_banner')),
                    "link" => get_setting('dashboard_page_bottom_banner_link')
                ],
                "all_shops_page" => [
                    "img" => api_asset(get_setting('all_shops_page_banner')),
                    "link" => get_setting('all_shops_page_banner_link')
                ],
                "shop_registration_page" => [
                    "img" => api_asset(get_setting('shop_registration_page_banner')),
                    "link" => get_setting('shop_registration_page_banner_link')
                ],
            ],
            'refundSettings' => [
                'refund_request_time_period' => get_setting('refund_request_time_period') * 86400,
                'refund_request_order_status' => json_decode(get_setting('refund_request_order_status')),
                'refund_reason_types' => json_decode(get_setting('refund_reason_types'))
            ],
            'authSettings' => [
                'customer_login_with' => get_setting('customer_login_with'),
                'customer_otp_with' => get_setting('customer_otp_with'),
            ]
        ];

        if (get_setting('offline_payment') == 1) {
            $settings['offlinePaymentMethods'] = json_decode(ManualPaymentResource::collection(ManualPaymentMethod::all())->toJson());
        }

        return view('frontend.app', compact('settings', 'meta'));
    }
}
