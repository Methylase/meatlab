# 🥩 Meatlab

**Meatlab** is a mobile-responsive meat marketplace web application. Customers search for meat shops by location, browse a shop's products, and order and pay online, while meat shop owners manage their own storefront, products and orders from a dedicated dashboard.

Built with the [Trongate](https://trongate.io) PHP framework and integrated with [Paystack](https://paystack.com) for secure payments.

---

## Table of Contents

- [Features](#features)
- [User Roles](#user-roles)
- [How It Works](#how-it-works)
- [Tech Stack](#tech-stack)
- [Getting Started](#getting-started)
- [Configuration](#configuration)
- [Project Structure](#project-structure)
- [Design Guidelines](#design-guidelines)
- [Roadmap](#roadmap)
- [Contributing](#contributing)
- [License](#license)

---

## Features

### For Customers
- Search for meat shops by location or address
- Order from any listed shop, with no account required
- Choose products by custom quantity or custom amount
- Browse products by category
- Cart, checkout and secure online payment via Paystack
- Order updates by SMS or email when an order is accepted/processing and when it is ready or on the way

### For Meat Shop Owners
- Log in with phone number or email (with "Remember me" and "Forgot password")
- Upload and update the shop banner
- Add and update products and prices
- View only their own products and orders
- Accept orders for processing
- See which orders have been paid out to them, with date and time

### For Admins
- Set up and manage shops
- View all products from all shops
- View all orders per shop
- Mark orders as paid out to a shop (with date and time stamp) and notify the shop

### Platform
- Fully mobile responsive
- Consistent header, footer, colors and spacing across the site and dashboards
- Each shop has its own public page

---

## User Roles

| Role | Access |
|------|--------|
| **Customer** | Public site only: search shops, view shop pages, cart, checkout, pay. No login required. |
| **Shop Owner** | Login to the shop owner dashboard: manage own banner, products, prices and orders. |
| **Admin** | Login to the admin dashboard: manage shops, view all products and orders, record shop payouts. |

After login, admins and shop owners are redirected automatically to their respective dashboards.

---

## How It Works

1. **Search**: A customer lands on the homepage and searches for meat shops by location or address.
2. **Browse**: They open a shop page, which features a hero carousel (banner plus the shop's top products), product listings and categories.
3. **Order**: They add items to the cart, specifying quantity or amount, and proceed to checkout.
4. **Pay**: Payment is completed securely through Paystack.
5. **Fulfil**: The shop owner accepts the order and processes it. The customer is notified by SMS or email at each stage.
6. **Payout**: The admin records payment to the shop for each order. The shop sees this in its transactions table.

---

## Tech Stack

- **Framework:** Trongate (PHP, MVC)
- **Database:** MySQL / MariaDB
- **Payments:** Paystack
- **Frontend:** HTML, CSS, JavaScript (responsive, mobile-first)
- **Notifications:** SMS and email

---

## Getting Started

### Prerequisites
- PHP 8.0 or higher
- MySQL or MariaDB
- Apache or Nginx with URL rewriting enabled
- A [Paystack](https://paystack.com) account (public and secret API keys)
- An SMS provider account and SMTP credentials for notifications

### demo test
Go ahead and open  `meatlab.codeden.com.ng` in your browser.
information to login and test will be provided on request


---

## Project Structure

Meatlab follows the standard Trongate layout:

```
meatlab/
├── config/        # App, database and environment configuration
├── engine/        # Trongate core framework
├── modules/       # Feature modules (controllers, models, views)
├── public/        # CSS, JavaScript, images (logo, banners)
└── index.php      # Application entry point
```

Feature modules cover areas such as shops, products, cart/checkout, orders, payments, users/authentication and dashboards.

---

## Design Guidelines

- **Style:** Red, black and white, in a clean meat-market look
- **Logo:** Logo icon and the "Meatlab" text sit side by side
- **Homepage:** Header with logo on the left and login on the right, hero banners with random shop carousels, shop search, features section, footer
- **Shop pages:** Three-slide hero carousel, product display, category section, cart and checkout
- **Dashboards:** Reuse the site's logo, colors, spacing and feel
- **Footer:** Identical on every page

---

## Roadmap

- [ ] Customer order tracking page
- [ ] Ratings and reviews for shops
- [ ] Delivery fee and distance calculation
- [ ] Sales reports and analytics for shop owners
- [ ] Multi-city and multi-currency support

*(Adjust this list to match your real plans.)*

---

## License

Distributed under the MIT License. See `LICENSE` for details. *(Replace with your chosen license.)*

---

## Contact

**Meatlab**: fresh meat, ordered easily.
Questions or support: `methyl2007@yahoo.com`

