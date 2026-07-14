<?php

$modelsPath = __DIR__ . '/app/Models';

$relationships = [
    'User' => "
    public function addresses() { return \$this->hasMany(UserAddress::class); }
    public function shop() { return \$this->hasOne(Shop::class); }
    public function orders() { return \$this->hasMany(Order::class); }
    public function wishlists() { return \$this->hasMany(Wishlist::class); }
    public function supportTickets() { return \$this->hasMany(SupportTicket::class); }
    public function socialLogins() { return \$this->hasMany(SocialLogin::class); }
    public function affiliateUser() { return \$this->hasOne(AffiliateUser::class); }
    public function classifiedProducts() { return \$this->hasMany(ClassifiedProduct::class); }
",
    'SocialLogin' => "
    public function user() { return \$this->belongsTo(User::class); }
",
    'UserAddress' => "
    public function user() { return \$this->belongsTo(User::class); }
",
    'Shop' => "
    public function user() { return \$this->belongsTo(User::class); }
    public function products() { return \$this->hasMany(Product::class); }
    public function subOrders() { return \$this->hasMany(SubOrder::class); }
",
    'SellerSubscription' => "
    public function payments() { return \$this->hasMany(SellerSubscriptionPayment::class, 'subscription_id'); }
",
    'SellerSubscriptionPayment' => "
    public function user() { return \$this->belongsTo(User::class); }
    public function subscription() { return \$this->belongsTo(SellerSubscription::class, 'subscription_id'); }
",
    'Category' => "
    public function products() { return \$this->hasMany(Product::class); }
    public function subcategories() { return \$this->hasMany(Category::class, 'parent_id'); }
    public function parent() { return \$this->belongsTo(Category::class, 'parent_id'); }
",
    'Brand' => "
    public function products() { return \$this->hasMany(Product::class); }
",
    'Attribute' => "
    public function values() { return \$this->hasMany(AttributeValue::class); }
",
    'AttributeValue' => "
    public function attribute() { return \$this->belongsTo(Attribute::class); }
",
    'Product' => "
    public function shop() { return \$this->belongsTo(Shop::class); }
    public function category() { return \$this->belongsTo(Category::class); }
    public function brand() { return \$this->belongsTo(Brand::class); }
    public function variants() { return \$this->hasMany(ProductVariant::class); }
    public function wholesalePrices() { return \$this->hasMany(WholesalePrice::class); }
    public function digitalAssets() { return \$this->hasMany(DigitalAsset::class); }
    public function auctions() { return \$this->hasMany(Auction::class); }
",
    'ProductVariant' => "
    public function product() { return \$this->belongsTo(Product::class); }
",
    'DigitalAsset' => "
    public function product() { return \$this->belongsTo(Product::class); }
",
    'FlashDeal' => "
    public function products() { return \$this->hasMany(FlashDealProduct::class); }
",
    'FlashDealProduct' => "
    public function flashDeal() { return \$this->belongsTo(FlashDeal::class); }
    public function product() { return \$this->belongsTo(Product::class); }
",
    'WholesalePrice' => "
    public function product() { return \$this->belongsTo(Product::class); }
",
    'Auction' => "
    public function product() { return \$this->belongsTo(Product::class); }
    public function bids() { return \$this->hasMany(Bid::class); }
",
    'Bid' => "
    public function auction() { return \$this->belongsTo(Auction::class); }
    public function user() { return \$this->belongsTo(User::class); }
",
    'ClassifiedProduct' => "
    public function user() { return \$this->belongsTo(User::class); }
    public function category() { return \$this->belongsTo(Category::class); }
",
    'Order' => "
    public function user() { return \$this->belongsTo(User::class); }
    public function address() { return \$this->belongsTo(UserAddress::class, 'shipping_address_id'); }
    public function subOrders() { return \$this->hasMany(SubOrder::class); }
",
    'SubOrder' => "
    public function order() { return \$this->belongsTo(Order::class); }
    public function shop() { return \$this->belongsTo(Shop::class); }
    public function items() { return \$this->hasMany(OrderItem::class); }
    public function tracking() { return \$this->hasMany(OrderTracking::class); }
",
    'OrderItem' => "
    public function subOrder() { return \$this->belongsTo(SubOrder::class); }
    public function product() { return \$this->belongsTo(Product::class); }
    public function variant() { return \$this->belongsTo(ProductVariant::class, 'product_variant_id'); }
    public function refundRequest() { return \$this->hasOne(RefundRequest::class); }
",
    'RefundRequest' => "
    public function orderItem() { return \$this->belongsTo(OrderItem::class); }
    public function user() { return \$this->belongsTo(User::class); }
",
    'OrderTracking' => "
    public function subOrder() { return \$this->belongsTo(SubOrder::class); }
",
    'Wishlist' => "
    public function user() { return \$this->belongsTo(User::class); }
    public function product() { return \$this->belongsTo(Product::class); }
",
    'Compare' => "
    public function user() { return \$this->belongsTo(User::class); }
    public function product() { return \$this->belongsTo(Product::class); }
",
    'Conversation' => "
    public function sender() { return \$this->belongsTo(User::class, 'sender_id'); }
    public function receiver() { return \$this->belongsTo(User::class, 'receiver_id'); }
    public function messages() { return \$this->hasMany(Message::class); }
",
    'Message' => "
    public function conversation() { return \$this->belongsTo(Conversation::class); }
",
    'SupportTicket' => "
    public function user() { return \$this->belongsTo(User::class); }
    public function replies() { return \$this->hasMany(TicketReply::class); }
",
    'TicketReply' => "
    public function ticket() { return \$this->belongsTo(SupportTicket::class, 'support_ticket_id'); }
    public function user() { return \$this->belongsTo(User::class); }
",
    'AffiliateUser' => "
    public function user() { return \$this->belongsTo(User::class); }
    public function links() { return \$this->hasMany(AffiliateLink::class); }
    public function earnings() { return \$this->hasMany(AffiliateEarnings::class); }
",
    'AffiliateLink' => "
    public function affiliateUser() { return \$this->belongsTo(AffiliateUser::class); }
    public function product() { return \$this->belongsTo(Product::class); }
",
    'AffiliateEarnings' => "
    public function affiliateUser() { return \$this->belongsTo(AffiliateUser::class); }
    public function order() { return \$this->belongsTo(Order::class); }
",
    'Translation' => "
    public function translatable() { return \$this->morphTo(); }
"
];

$files = scandir($modelsPath);
$processedCount = 0;

foreach ($files as $file) {
    if (strpos($file, '.php') !== false) {
        $modelName = str_replace('.php', '', $file);
        
        if (array_key_exists($modelName, $relationships)) {
            $filePath = $modelsPath . '/' . $file;
            $content = file_get_contents($filePath);
            
            // Check if we haven't already injected relationships (avoid duplicates)
            if (strpos($content, $relationships[$modelName]) === false) {
                // Find the closing brace of the class
                $pos = strrpos($content, '}');
                if ($pos !== false) {
                    $newContent = substr_replace($content, $relationships[$modelName] . "\n}", $pos, 1);
                    // For the mass assignment capability, let's also add guarded = [] to everything
                    $guardedProp = "\n    protected \$guarded = [];\n";
                    if (strpos($newContent, 'protected $guarded') === false && strpos($newContent, 'protected $fillable') === false) {
                         // Insert guarded array right after the use HasFactory; (if it exists) or opening brace
                         if (strpos($newContent, 'use HasFactory;') !== false) {
                             $newContent = str_replace('use HasFactory;', 'use HasFactory;' . $guardedProp, $newContent);
                         }
                    }
                    file_put_contents($filePath, $newContent);
                    echo "Injected relationships for Model: {$modelName}\n";
                    $processedCount++;
                }
            }
        }
    }
}

echo "Finished! Injected relationships into {$processedCount} models.\n";
