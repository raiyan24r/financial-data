# Financial Data - Stock Historical Quotes

A web application that allows users to view and analyze historical stock price data for NASDAQ-listed companies. The application fetches real-time financial data from Yahoo Finance API and displays interactive charts with open/close prices for selected date ranges.

## Features

- **Company Selection**: Search and select from NASDAQ-listed companies using a searchable dropdown
- **Date Range Filtering**: Filter historical stock data by custom date ranges with an intuitive date picker
- **Interactive Charts**: Visualize stock price trends with multi-axis line charts showing open and close prices
- **Paginated Data Tables**: View detailed price quotes in a paginated table format
- **Email Notifications**: Receive email notifications with your search parameters via queued jobs

## Tech Stack

### Backend
- **PHP** 8.0+
- **Laravel** 9.x - PHP web application framework
- **Laravel Sanctum** - API authentication
- **Laravel Telescope** - Debug assistant
- **Guzzle HTTP** - HTTP client for API requests

### Frontend
- **Bootstrap 4** - CSS framework
- **jQuery** & **jQuery UI** - JavaScript library and UI components
- **Select2** - Enhanced select boxes
- **Chart.js** - JavaScript charting library
- **Vite** - Frontend build tool

### External APIs
- **Yahoo Finance API** (via RapidAPI) - Historical stock price data
- **DataHub.io** - NASDAQ company listings

### Testing
- **PHPUnit** - Unit and feature testing
- **Laravel Dusk** - Browser automation testing

## Prerequisites

- PHP 8.0 or higher
- Composer
- Node.js and npm
- MySQL database
- RapidAPI account (for Yahoo Finance API access)

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd financial-data
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Create environment file**
   ```bash
   cp .env.example .env
   ```

5. **Generate application key**
   ```bash
   php artisan key:generate
   ```

6. **Configure your database** in the `.env` file:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

7. **Configure API credentials** in the `.env` file:
   ```env
   RAPID_API_KEY=your_rapidapi_key
   RAPID_API_HOST=yh-finance.p.rapidapi.com
   HISTORICAL_DATA_API_URL=https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data
   COMPANY_LIST_API_URL=https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json
   ```

8. **Configure email settings** (optional, for email notifications):
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=your_mail_host
   MAIL_PORT=your_mail_port
   MAIL_USERNAME=your_mail_username
   MAIL_PASSWORD=your_mail_password
   ```

9. **Run database migrations**
   ```bash
   php artisan migrate
   ```

## Running the Application

1. **Start the development server**
   ```bash
   php artisan serve
   ```

2. **Start the queue worker** (required for email notifications)
   ```bash
   php artisan queue:work
   ```

3. **Build frontend assets** (for development)
   ```bash
   npm run dev
   ```

4. **Navigate to the application**
   ```
   http://localhost:8000
   ```

## Running Tests

### Unit and Feature Tests
```bash
php artisan test
```

### Browser Tests (Laravel Dusk)
```bash
php artisan dusk
```

## Project Structure

```
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── HistoricalDataController.php  # Main controller
│   ├── Jobs/
│   │   └── SendEmailJob.php                  # Email queue job
│   ├── Mail/
│   │   └── FormSubmitted.php                 # Email template
│   └── Services/
│       ├── ChartDataService.php              # Chart data preparation
│       ├── CompanyService.php                # Company list fetching
│       ├── HistoricalQuoteService.php        # Stock price data
│       └── UtilityServices.php               # Utility functions
├── resources/
│   └── views/
│       ├── historical-data.blade.php         # Main view
│       └── symbol-historical-quotes-table.blade.php
├── routes/
│   ├── api.php                               # API routes
│   └── web.php                               # Web routes
└── tests/                                    # Test files
```

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/` | Main application page |
| POST | `/api/symbol-data` | Fetch historical data for a symbol |

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
