# NeoTech-Forge

A comprehensive e-commerce platform for custom PC building and pre-built gaming systems.

## Overview

NeoTech-Forge is a full-featured web application that allows users to:
- Browse and purchase pre-built gaming PCs
- Customize and build their own PC configurations
- Manage shopping cart and checkout process
- Track orders
- Secure payment processing with Paytm integration

## Features

- **Custom PC Builder**: Interactive interface for selecting and customizing PC components
- **Pre-built Systems**: Curated selection of ready-to-ship gaming PCs
- **Shopping Cart**: Full cart management with real-time updates
- **Secure Checkout**: Integrated payment processing
- **Order Tracking**: Track your order status
- **User Authentication**: Secure login and registration system
- **Invoice Generation**: Automated PDF invoice creation
- **Email Notifications**: Order confirmation and updates

## Tech Stack

- **Frontend**: HTML5, CSS3, JavaScript
- **Backend**: PHP
- **Database**: MySQL, MongoDB
- **Payment Gateway**: Paytm
- **PDF Generation**: TCPDF library
- **Email**: PHP mail functionality

## Project Structure

```
NeoTech-Forge/
├── config/              # Configuration files
├── DB/                  # Database schemas and setup
├── imgs/                # Product images and assets
├── lib/                 # Third-party libraries
│   ├── paytm/          # Paytm payment integration
│   ├── tcpdf/          # PDF generation library
│   └── send_email.php  # Email utilities
├── index.html          # Homepage
├── customize.html      # PC customization interface
├── prebuilt.html       # Pre-built systems catalog
├── cart.html           # Shopping cart
├── checkout.html       # Checkout process
├── login.html          # User authentication
├── contact.html        # Contact page
├── about.html          # About page
├── track_order.php     # Order tracking
└── styles.css          # Main stylesheet
```

## Setup Instructions

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- MongoDB (optional, for additional features)
- Web server (Apache/Nginx)

### Installation

1. Clone the repository:
```bash
git clone https://github.com/babuthecodemaster/NeoTech-Forge.git
cd NeoTech-Forge
```

2. Configure database connection:
   - Copy `db_connect.example.php` to `db_connect.php`
   - Update `db_connect.php` with your database credentials
   - Import `database.sql` to set up the MySQL database

3. Configure MongoDB (if using):
   - Run `DB/mongo_setup.js` to initialize MongoDB collections

4. Configure payment gateway:
   - Copy `config/paytm_config.example.php` to `config/paytm_config.php`
   - Sign up for a Paytm merchant account at https://business.paytm.com/
   - Update `config/paytm_config.php` with your Paytm credentials

5. Set up web server to point to the project directory

6. Access the application through your web browser

## Configuration

Example configuration files are provided:
- `db_connect.example.php` → Copy to `db_connect.php` and update with your credentials
- `config/paytm_config.example.php` → Copy to `config/paytm_config.php` and update with your credentials

**Security Note**: The actual config files (`db_connect.php` and `config/paytm_config.php`) are gitignored to protect your sensitive credentials. Never commit these files to version control.

## Usage

1. Browse pre-built systems or use the custom PC builder
2. Add items to your cart
3. Proceed to checkout
4. Complete payment through Paytm
5. Receive order confirmation via email
6. Track your order status

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Contact

For questions or support, please use the contact form on the website or open an issue on GitHub.

## Acknowledgments

- TCPDF for PDF generation
- Paytm for payment processing
- All contributors and users of NeoTech-Forge
