# Admin Setup Guide

## 1. Theme Settings

Go to `Goody Green -> Theme Settings`.

Important reservation fields:

- `Reservation Section Title`
- `Reservation Page Title`
- `Reservation Custom URL`
- `Reservation Button Text`
- `Reservation Status Page URL`
- `Reservation Success Message`
- `Booking Intro Notice`
- `Pickup Warning Message`
- `Delivery Warning Message`
- `Cash Payment Warning Message`
- `Advance Payment Percentage`
- `Advance Booking Limit (Days)`
- `Default Cut-off Time (Minutes)`
- `Global Free Delivery Minimum`

## 2. Menu Items

Go to `Menu Items`.

For every item set:

- Title
- Featured image
- Price
- Short description
- Available toggle
- Unit type
- Unit label
- Minimum quantity
- Maximum quantity if needed
- Quantity step
- Stock tracking if needed
- Stock quantity
- Slot capacity unit
- Capacity per quantity
- Add-ons in `Name|Price` format

Examples:

- `Extra Salad|120`
- `Spice Rub|90`
- `Chef Carving Service|800`

## 3. Booking Dates

Go to `Goody Green -> Booking Dates`.

For each date configure:

- Service date
- Active status
- Optional note
- Time slots

Each slot supports:

- Time
- Label
- Person capacity
- KG capacity
- Allowed order types
- Slot cut-off minutes

Allowed order type values:

- `dine_in`
- `pickup`
- `delivery`
- `dine_in,pickup,delivery`

## 4. Delivery Zones

Go to `Goody Green -> Delivery Zones`.

For each zone set:

- Zone title
- Covered areas, one per line
- Delivery charge
- Free delivery minimum
- Minimum order
- Warning message
- Active status

## 5. Reservations

Go to `Goody Green -> Reservations`.

What admins can do:

- view reservation details
- check linked WooCommerce order
- change reservation status
- print order

Current statuses:

- Pending payment
- Confirmed
- Preparing
- Ready
- Completed
- Cancelled

## 6. Reservation Tools

Go to `Goody Green -> Reservation Tools`.

Available tools:

- Create / Update Pages
- Import Demo Reservation Data
- Export Reservations CSV

## 7. Testing Flow

Test these flows before launch:

1. Dine-in with full payment
2. Pickup with advance payment
3. Delivery with zone charge
4. Cash booking
5. Over-capacity slot prevention
6. Out-of-stock item prevention
7. Reservation status lookup
