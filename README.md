# Vehicle App

## Overview

This document provides all necessary information to configure and use the application. Please follow the setup instructions carefully and refer to the usage guide for operating the application.

## Configuration

### System Requirements
- **PHP Version:** 8.2
- **Framework:** Laravel 11.22
- **Database:** MySQL

### Installation

1. **Clone the Repository**
   Clone the project repository by running:
```bash
git clone https://github.com/Benardo07/vehicle_app.git
```

2. **Navigate to Project Directory**
```bash
cd vehicle_app
```

3. **Install Dependencies**
Run the following command to install the necessary dependencies:
```bash
composer install
```

4. **Environment Configuration**
Copy the example environment file and make the necessary configuration adjustments:
(i already provided the Cloud DB env in .env.example, so no need to add own env, just copy it from .env.example)
```bash
cp .env.example .env
```


5. **Generate Application Key**
Generate a new application key with the artisan command:
```bash
php artisan key
```

6. **Run The Website**
```bash
php artisan serve
```
Then open new ternimal, to run the Tailwind
```bash
npm run dev
```
The website will be host at http://127.0.0.1:8000/

### User Credentials

- **Admin Account**
- **Email:** admin@gmail.com
- **Password:** 12345678

- **Manager Account**
- **Email:** benardo188@gmail.com
- **Password:** 12345678

## Usage Guide
1. ""Logging In**
1. **Logging In**
- Access page at `http://127.0.0.1:8000/`, it will direct to login page if not signed In.
- Enter the email and password, use Admin Account to Book a Vehicle
- Click the 'Login' button to access the dashboard.
2. **View List of Vehicle**
- Vehicle Overview: Access the list of available vehicles from the dashboard to view detailed information including type, status, and usage.
3. **Book a Vehicle**
- Booking Process: Select a vehicle and fill out the booking form with details such as date, time,aprrover_id, driver_id and purpose.
- Submission: Submit the booking for approval or confirmation.
4. **Login As Manager, to Approve Booking**
- Managerial Access: Log in with a manager account to view and manage pending bookings.
- Approval Actions: Approve or reject bookings based on criteria such as availability.
5. **Initiating Maintenance**
- Maintenance Request: Initiate maintenance for vehicles requiring service, available when last service date was 90 days ago.
- Service Scheduling: Schedule maintenance to ensure vehicle readiness
6. **Return Vehicle**
- Return Process: Click Button return on the vehicles card that is InUsed.
- Record Details: Enter kilometers driven and any additional vehicle condition notes.
7. **Download Excel**
- Export Data: Download reports of vehicle usage, bookings, and maintenance records in Excel format.
8. **View Usage Detail in Chart**
- Usage Analytics: View visual charts displaying vehicle usage statistics for better management and optimization.

## Troubleshooting

If you encounter issues:
- Confirm all system requirements are met.
- Verify the `.env` file for proper database credentials and settings.
- Update dependencies with `composer update`.
- Clear application cache using `php artisan cache:clear`.
- Review logs in the `storage/logs` directory for specific errors.


## Support

For further assistance, contact the development team at:
- **Email:** benardo188@gmail.com