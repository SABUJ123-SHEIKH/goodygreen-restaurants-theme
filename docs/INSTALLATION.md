# Installation Guide

1. Upload or place the theme in `wp-content/themes/goody-green-theme`.
2. Activate the theme from `Appearance -> Themes`.
3. Install and activate `WooCommerce`.
4. Make sure WooCommerce pages exist:
   - Shop
   - Cart
   - Checkout
   - My Account
5. Go to `Goody Green -> Reservation Tools`.
6. Click `Create / Update Pages`.
7. Go to `Settings -> Reading` and set the homepage if needed.
8. Go to `Appearance -> Menus` and assign the Primary menu.
9. Go to `Goody Green -> Theme Settings` and complete:
   - General branding and colors
   - Hero content
   - Reservation settings
   - Contact details
   - Footer details
10. If you want sample data, go back to `Goody Green -> Reservation Tools` and click `Import Demo Reservation Data`.

# Required Theme Pages

The Reservation Tools screen can auto-create these pages:

- Reservation
- Menu
- Order Status
- About
- Contact
- Privacy Policy
- Terms

Shortcode pages:

- `Reservation` page: `[reservation_booking]`
- `Menu` page: `[reservation_menu]`
- `Order Status` page: `[reservation_order_status]`

# WooCommerce Payment Setup

1. Go to `WooCommerce -> Settings -> Payments`.
2. Enable the gateways you want to use.
3. Test at least:
   - Full payment
   - Advance payment
   - Cash flow
4. Use a real gateway plugin if you need local Bangladeshi payments.

# Final Launch Checklist

- Add real menu item images and prices
- Add real booking dates and time slots
- Add real delivery zones and charges
- Set legal pages
- Configure payment gateways
- Test mobile booking flow
- Test one successful payment
- Test one cash booking
- Test reservation status lookup
