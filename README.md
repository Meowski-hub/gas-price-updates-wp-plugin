# Gas Price Updates WordPress Plugin

A WordPress plugin for displaying and managing gas price updates with CSV upload functionality.

## Features

- CSV upload interface for gas price data
- Admin dashboard for managing gas prices
- Shortcode `[gas_prices_table]` for displaying prices on any page
- Responsive design
- Clean and modern UI

## Installation

1. Download the plugin zip file
2. Go to WordPress admin > Plugins > Add New > Upload Plugin
3. Upload the zip file and activate the plugin
4. Navigate to Gas Prices in the admin menu to start managing prices

## CSV Format

Your CSV file should include the following columns:

- station_name
- price
- address

Example:
```csv
station_name,price,address
"Gas Station A",1.99,"123 Main St"
"Gas Station B",2.05,"456 Oak Ave"
```

## Usage

1. Upload your gas prices CSV file in the admin dashboard
2. Use the shortcode `[gas_prices_table]` to display the prices table on any page or post
3. The table will automatically update when you upload new data

## Support

For support or feature requests, please open an issue on GitHub.

## License

GPL v2 or later