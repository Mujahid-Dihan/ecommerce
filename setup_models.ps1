# Run this script in the root of your project to generate the Models and Migrations

# 1. User & Access Management Module
php artisan make:model SocialLogin -m
php artisan make:model Otp -m
php artisan make:model UserAddress -m

# 2. Vendor & Subscription Module
php artisan make:model Shop -m
php artisan make:model SellerSubscription -m
php artisan make:model SellerSubscriptionPayment -m

# 3. Catalog & Product Module
php artisan make:model Category -m
php artisan make:model Brand -m
php artisan make:model Attribute -m
php artisan make:model AttributeValue -m
php artisan make:model Product -m
php artisan make:model ProductVariant -m
php artisan make:model DigitalAsset -m

# 4. Sales, Pricing & Auctions Module
php artisan make:model FlashDeal -m
php artisan make:model FlashDealProduct -m
php artisan make:model WholesalePrice -m
php artisan make:model Auction -m
php artisan make:model Bid -m
php artisan make:model ClassifiedProduct -m

# 5. Order, Shipping & POS Module
php artisan make:model Order -m
php artisan make:model SubOrder -m
php artisan make:model OrderItem -m
php artisan make:model ShippingMethod -m
php artisan make:model PickupPoint -m
php artisan make:model RefundRequest -m
php artisan make:model OrderTracking -m

# 6. Interactions & Support Module
php artisan make:model Wishlist -m
php artisan make:model Compare -m
php artisan make:model Conversation -m
php artisan make:model Message -m
php artisan make:model SupportTicket -m
php artisan make:model TicketReply -m

# 7. Affiliate System Module
php artisan make:model AffiliateUser -m
php artisan make:model AffiliateLink -m
php artisan make:model AffiliateEarnings -m

# 8. Localization & Dynamic CMS Module
php artisan make:model Language -m
php artisan make:model Translation -m
php artisan make:model Currency -m
php artisan make:model Page -m
php artisan make:model ThemeSetting -m

Write-Host "All Models and Migrations generated successfully!"
