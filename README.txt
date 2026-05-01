Goody Green Restaurant Theme
============================

What This Theme Includes
------------------------
- Custom WordPress restaurant theme
- WooCommerce-compatible reservation / pre-order flow
- Dashboard-managed menu items, booking dates, delivery zones, and reservations
- Shortcodes:
  - [reservation_booking]
  - [reservation_menu]
  - [reservation_order_status]
- Built-in Reservation Tools page for:
  - creating default pages
  - importing demo booking data
  - exporting reservations CSV
- Bengali/English ready strings through WordPress translation + Polylang/WPML support in the header switcher

Key Admin Areas
---------------
- Goody Green -> Theme Settings
- Goody Green -> Booking Dates
- Goody Green -> Delivery Zones
- Goody Green -> Reservations
- Goody Green -> Reservation Tools
- Menu Items -> each item now has Reservation & Pre-Order Settings

Required Plugins
----------------
- WooCommerce

Strongly Recommended
--------------------
- Polylang or WPML for Bengali + English switching
- Local payment/shipping gateway plugins as needed for your store

Fast Setup
----------
1. Install and activate the theme.
2. Install and activate WooCommerce.
3. Go to Goody Green -> Reservation Tools.
4. Click Create / Update Pages.
5. Click Import Demo Reservation Data if you want sample content.
6. Go to Goody Green -> Theme Settings -> Reservation and set:
   - Reservation Button Text
   - Reservation Custom URL
   - Deposit Percentage
   - Booking notices / warnings
7. Go to Menu Items and complete pricing, stock, unit, add-ons, and capacity fields.
8. Go to Booking Dates and create real service dates + slots.
9. Go to Delivery Zones and add areas with charges / minimums.
10. Test the flow from Reservation page to WooCommerce payment.

Theme Reservation Flow
----------------------
1. Date selection
2. Menu selection
3. Live time slot selection
4. Order type + payment mode
5. Customer details
6. Summary + WooCommerce order/payment redirect

Documentation
-------------
- docs/INSTALLATION.md
- docs/ADMIN-SETUP.md
- demo-data/reservation-demo.json

Developer Notes
---------------
- Main frontend style: assets/css/main.css
- Reservation flow style: assets/css/reservation.css
- Main frontend script: assets/js/main.js
- Reservation flow script: assets/js/reservation.js
- Reservation engine: inc/reservations.php
- SCSS source folders remain in assets/scss/

Verification Notes
------------------
- PHP lint should pass on modified theme files.
- Reservation shortcodes and reservation CPTs should bootstrap under WordPress.
- WooCommerce payment gateways must still be configured from WooCommerce settings for real payments.
