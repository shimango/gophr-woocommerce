# Gophr PHP Client Library

A comprehensive PHP client for the Gophr Courier Service API, enabling seamless integration of courier services into your PHP applications.

[![Build Status](https://github.com/shimango/gophr/actions/workflows/tests.yml/badge.svg?branch=master)](https://github.com/shimango/gophr/actions/workflows/tests.yml?query=branch%3Amaster)
[![Latest Stable Version](http://poser.pugx.org/shimango/gophr/v)](https://packagist.org/packages/shimango/gophr)
[![Total Downloads](http://poser.pugx.org/shimango/gophr/downloads)](https://packagist.org/packages/shimango/gophr)
[![License](http://poser.pugx.org/shimango/gophr/license)](https://packagist.org/packages/shimango/gophr)
[![PHP Version Require](http://poser.pugx.org/shimango/gophr/require/php)](https://packagist.org/packages/shimango/gophr)

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Configuration](#configuration)
- [Core Concepts](#core-concepts)
- [Usage Examples](#usage-examples)
  - [Working with Jobs](#working-with-jobs)
  - [Working with Deliveries](#working-with-deliveries)
  - [Working with Parcels](#working-with-parcels)
- [Response Handling](#response-handling)
- [Error Handling](#error-handling)
- [Testing](#testing)
- [API Reference](#api-reference)

## Requirements

- PHP 8.0 or higher
- Composer
- A Gophr API key (obtain from [Gophr Authorization](https://developers.gophr.com/docs/authorisation))

## Installation

### Using Composer (Recommended)

```bash
composer require shimango/gophr
```

### Manual Installation

1. Download this package from GitHub
2. Run `composer install` in the package directory
3. Require the autoloader in your project:

```php
require_once 'vendor/autoload.php';
```

## Quick Start

Here's a simple example to get you started:

```php
<?php
require_once 'vendor/autoload.php';

use Shimango\Gophr\Client;
use Shimango\Gophr\Common\Configuration;

// Initialize the client
$config = new Configuration('YOUR_API_KEY');
$gophr = new Client($config);

// List all jobs
$response = $gophr->listJobs();
$jobs = $response->getContentsArray();

foreach ($jobs['data'] as $job) {
    echo "Job ID: {$job['id']} - Status: {$job['status']}\n";
}
```

## Configuration

The `Configuration` class manages all settings for API communication.

### Constructor Parameters

```php
$config = new Configuration(
    $apiKey,           // Required: Your Gophr API key
    $isSandbox,        // Optional: Use sandbox environment (default: false)
    $apiVersion,       // Optional: API version (default: "v2")
    $proxyPort,        // Optional: Proxy port if needed (default: null)
    $proxyVerifySSL    // Optional: Verify SSL for proxy (default: false)
);
```

### Configuration Examples

**Production Environment:**
```php
$config = new Configuration('live_api_key_here');
```

**Sandbox/Testing Environment:**
```php
$config = new Configuration('test_api_key_here', true);
```

**Using a Proxy:**
```php
$config = new Configuration(
    'your_api_key',
    false,              // Production
    'v2',
    8080,              // Proxy port
    true               // Verify SSL
);
```

## Core Concepts

### Jobs
A **Job** represents a complete courier assignment that may include multiple deliveries. All deliveries within a job are completed by the same courier in a single trip.

### Deliveries
A **Delivery** consists of:
- One pickup location
- One dropoff location
- Associated parcels to be transported

### Parcels
**Parcels** are the individual packages within a delivery. A delivery can contain multiple parcels.

### Workflow
1. Create a Job with a pickup, deliveries, and parcels. This can be done in two ways:
    - **All at once**: Create and confirm the Job in one go.
    - **As a draft**: Create the Job as a draft and edit it (e.g., add/remove/edit deliveries and parcels) until it is ready to be confirmed.
2. Confirm the Job to dispatch the courier.

**Note:** Once a job is confirmed, deliveries and parcels cannot be modified.

## Usage Examples

### Working with Jobs

#### Creating a Job

```php
use Shimango\Gophr\Client;
use Shimango\Gophr\Common\Configuration;

$config = new Configuration('YOUR_API_KEY', true); // Sandbox mode
$gophr = new Client($config);

$jobData = [
    'external_id' => '2112',
    'is_confirmed' => 1,
    'pickups' => [
        [
            'earliest_pickup_time' => '2026-08-12T06:17:00+00:00',
            'pickup_deadline' => '2026-08-12T07:13:00+00:00',
            'pickup_address1' => '221B Baker Street',
            'pickup_city' => 'London',
            'pickup_postcode' => 'NW1 6TS',
            'pickup_country_code' => 'GB',
            'pickup_company_name' => 'Elementary Investigations',
            'pickup_person_name' => 'Sherlock Holmes',
            'pickup_email' => 'itiselementary@test.com',
            'pickup_mobile_number' => '07700000000',
            'parcels' => [
                [
                    'parcel_external_id' => '001',
                    'parcel_reference_number' => '0fc35278-60a1-4fb3-9791-4f45c492e120',
                    'parcel_description' => 'Sunglasses',
                    'width' => 10,
                    'length' => 15,
                    'height' => 5,
                    'weight' => 0.5,
                ],
                [
                    'parcel_external_id' => '002',
                    'parcel_reference_number' => '0fc35278-60a1-4fb3-9791-4f45c492e120',
                    'parcel_description' => 'Folding chair',
                    'width' => 50,
                    'length' => 80,
                    'height' => 5,
                    'weight' => 1.5,
                ],
            ],
        ],
    ],
    'dropoffs' => [
        [
            'dropoff_company_name' => 'The Prime Minister\'s Office',
            'dropoff_address1' => '10 Downing Street',
            'dropoff_city' => 'London',
            'dropoff_postcode' => 'SW1A 0AA',
            'dropoff_country_code' => 'GB',
            'dropoff_person_name' => 'The prime minister',
            'dropoff_email' => 'pm@test.com',
            'dropoff_mobile_number' => '07588000000',
            'dropoff_deadline' => '2026-08-12T08:00:00+00:00',
            'parcels' => [
                [
                    'parcel_external_id' => '001',
                ],
                [
                    'parcel_external_id' => '002',
                ],
            ],
        ],
    ],
];

$response = $gophr->createJob($jobData);
$job = $response->getContentsObject();

echo "Created Job ID: {$job->id}\n";
```

#### Retrieving a Job

```php
$jobId = 'job_abc123';
$response = $gophr->getJob($jobId);
$job = $response->getContentsArray();

print_r($job);
```

#### Updating a Job (Confirming)

```php
$jobId = 'job_abc123';
$updateData = [
    'status' => 'confirmed'
];

$response = $gophr->updateJob($jobId, $updateData);
$updatedJob = $response->getContentsObject();

echo "Job status: {$updatedJob->status}\n";
```

#### Cancelling a Job

```php
$jobId = 'job_abc123';
$cancelData = [
    'reason' => 'Customer requested cancellation'
];

$response = $gophr->cancelJob($jobId, $cancelData);
echo "Job cancelled successfully\n";
```

#### Listing Jobs with Pagination

```php
// Get page 2, with 20 jobs per page, including finished jobs
$page = 2;
$count = 20;
$includeFinished = true;

$response = $gophr->listJobs($page, $count, $includeFinished);
$jobs = $response->getContentsArray();

echo "Total jobs: {$jobs['total']}\n";
echo "Current page: {$jobs['current_page']}\n";

foreach ($jobs['data'] as $job) {
    echo "- Job {$job['id']}: {$job['status']}\n";
}
```

### Working with Deliveries

#### Adding a Delivery to a Job

```php
$jobId = 'job_abc123';

$deliveryData = [
    'pickup' => [
        'earliest_pickup_time' => '2026-08-12T06:17:00+00:00',
        'pickup_deadline' => '2026-08-12T07:13:00+00:00',
        'pickup_address1' => '221B Baker Street',
        'pickup_city' => 'London',
        'pickup_postcode' => 'NW1 6TS',
        'pickup_country_code' => 'GB',
        'pickup_company_name' => 'Gophr',
        'pickup_person_name' => 'John Smith',
        'pickup_email' => 'john@test.com',
        'pickup_mobile_number' => '07700000000',
    ],
    'dropoff' => [
        'dropoff_company_name' => 'Buckingham Palace',
        'dropoff_address1' => 'Buckingham Palace',
        'dropoff_city' => 'London',
        'dropoff_postcode' => 'SW1A 1AA',
        'dropoff_country_code' => 'GB',
        'dropoff_person_name' => 'The Royal Household',
        'dropoff_email' => 'yourmajesty@test.com',
        'dropoff_mobile_number' => '07700000000',
        'dropoff_deadline' => '2026-08-12T08:00:00+00:00',
    ],
    'parcels' => [
        [
            'parcel_external_id' => '003',
            'parcel_reference_number' => '0fc35278-60a1-4fb3-9791-4f45c492e120',
            'parcel_description' => 'Sunglass',
            'width' => 10,
            'length' => 15,
            'height' => 5,
            'weight' => 0.5,
        ],
        [
            'parcel_external_id' => '004',
            'parcel_reference_number' => '0fc35278-60a1-4fb3-9791-4f45c492e120',
            'parcel_description' => 'Folding chair',
            'width' => 50,
            'length' => 80,
            'height' => 5,
            'weight' => 1.5,
        ],
    ],
];

$response = $gophr->createDelivery($jobId, $deliveryData);
$delivery = $response->getContentsObject();

echo "Created Delivery ID: {$delivery->id}\n";
```

#### Retrieving a Delivery

```php
$jobId = 'job_abc123';
$deliveryId = 'delivery_xyz789';

$response = $gophr->getDelivery($jobId, $deliveryId);
$delivery = $response->getContentsArray();

print_r($delivery);
```

#### Updating a Delivery

```php
$jobId = 'job_abc123';
$deliveryId = 'delivery_xyz789';

$updateData = [
    'dropoff' => [
        'dropoff_phone_number' => '+44 20 7999 8888'  // Update contact phone
    ]
];

$response = $gophr->updateDelivery($jobId, $deliveryId, $updateData);
echo "Delivery updated successfully\n";
```

#### Listing Deliveries for a Job

```php
$jobId = 'job_abc123';

$response = $gophr->listDeliveries($jobId);
$deliveries = $response->getContentsArray();

foreach ($deliveries as $delivery) {
    echo "Delivery {$delivery['id']}: {$delivery['status']}\n";
}
```

#### Cancelling a Delivery

```php
$jobId = 'job_abc123';
$deliveryId = 'delivery_xyz789';

$cancelData = [
    'reason' => 'Address incorrect'
];

$response = $gophr->cancelDelivery($jobId, $deliveryId, $cancelData);
echo "Delivery cancelled\n";
```

### Working with Parcels

#### Adding a Parcel to a Delivery

```php
$jobId = 'job_abc123';
$deliveryId = 'delivery_xyz789';

$parcelData = [
    'parcel_external_id' => '005',
    'description' => 'Electronics',
    'weight' => 2.5,        // kg
    'length' => 40,         // cm
    'width' => 30,          // cm
    'height' => 20,         // cm
    'is_fragile' => 1
];

$response = $gophr->createParcel($jobId, $deliveryId, $parcelData);
$parcel = $response->getContentsObject();

echo "Created Parcel ID: {$parcel->id}\n";
```

#### Retrieving a Parcel

```php
$jobId = 'job_abc123';
$deliveryId = 'delivery_xyz789';
$parcelId = 'parcel_def456';

$response = $gophr->getParcel($jobId, $deliveryId, $parcelId);
$parcel = $response->getContentsArray();

print_r($parcel);
```

#### Updating a Parcel

```php
$jobId = 'job_abc123';
$deliveryId = 'delivery_xyz789';
$parcelId = 'parcel_def456';

$updateData = [
    'weight' => 3.0,  // Update weight
    'is_not_rotatable' => 1 // Mark as non-rotatable
];

$response = $gophr->updateParcel($jobId, $deliveryId, $parcelId, $updateData);
echo "Parcel updated\n";
```

#### Listing Parcels in a Delivery

```php
$jobId = 'job_abc123';
$deliveryId = 'delivery_xyz789';

$response = $gophr->listParcels($jobId, $deliveryId);
$parcels = $response->getContentsArray();

foreach ($parcels as $parcel) {
    echo "Parcel {$parcel['id']}: {$parcel['description']} - {$parcel['weight']}kg\n";
}
```

#### Deleting a Parcel

```php
$jobId = 'job_abc123';
$deliveryId = 'delivery_xyz789';
$parcelId = 'parcel_def456';

$response = $gophr->deleteParcel($jobId, $deliveryId, $parcelId);
echo "Parcel deleted\n";

// Note: If this was the last parcel, the delivery will be automatically cancelled
```

## Response Handling

All API calls return a `GophrResponse` object that is PSR-7 compliant.

### Getting Response Data

```php
// As an array
$data = $response->getContentsArray();

// As an object
$data = $response->getContentsObject();

// Access specific fields
$jobId = $data['id'];          // Array access
$jobStatus = $data->status;    // Object access

// Get raw response
$rawBody = $response->getBody()->getContents();

// Get status code
$statusCode = $response->getStatusCode();
```

### Response Example

```php
$response = $gophr->getJob('job_abc123');

// Check if successful
if ($response->getStatusCode() === 200) {
    $job = $response->getContentsObject();
    
    echo "Job ID: {$job->id}\n";
    echo "Status: {$job->status}\n";
    echo "Created: {$job->created_at}\n";
    
    // Access nested data
    foreach ($job->deliveries as $delivery) {
        echo "  Delivery: {$delivery->id}\n";
    }
}
```

## Error Handling

Always wrap API calls in try-catch blocks to handle potential errors gracefully.

```php
use Shimango\Gophr\Client;
use Shimango\Gophr\Common\Configuration;

$config = new Configuration('YOUR_API_KEY', true);
$gophr = new Client($config);

try {
    $response = $gophr->getJob('invalid_job_id');
    $job = $response->getContentsArray();
    
} catch (\GuzzleHttp\Exception\ClientException $e) {
    // 4xx errors (client errors)
    $statusCode = $e->getResponse()->getStatusCode();
    $errorBody = $e->getResponse()->getBody()->getContents();
    
    echo "Client Error ({$statusCode}): {$errorBody}\n";
    
} catch (\GuzzleHttp\Exception\ServerException $e) {
    // 5xx errors (server errors)
    echo "Server Error: " . $e->getMessage() . "\n";
    
} catch (\Exception $e) {
    // Other errors
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Common Error Scenarios

```php
// Validation error example
try {
    $jobData = [
        'pickup' => [
            // Missing required fields
        ]
    ];
    
    $response = $gophr->createJob($jobData);
    
} catch (\GuzzleHttp\Exception\ClientException $e) {
    if ($e->getResponse()->getStatusCode() === 422) {
        $errors = json_decode($e->getResponse()->getBody(), true);
        echo "Validation errors:\n";
        print_r($errors['errors']);
    }
}
```

## Testing

Run the test suite:

```bash
composer test
```

Run tests with coverage:

```bash
composer test -- --coverage-html coverage
```

## API Reference

For detailed API documentation, request/response schemas, and additional features, visit:

- [Gophr API Documentation](https://developers.gophr.com/docs)
- [Gophr API Reference](https://developers.gophr.com/reference)

## Best Practices

1. **Always use sandbox mode during development:**
   ```php
   $config = new Configuration('test_api_key', true);
   ```

2. **Implement proper error handling:**
   Wrap all API calls in try-catch blocks

3. **Validate data before sending:**
   Ensure all required fields are present before making API calls

4. **Store job IDs:**
   Keep track of job IDs in your database for future reference

5. **Don't modify confirmed jobs:**
   Remember that jobs, deliveries, and parcels cannot be modified after confirmation

6. **Use pagination for lists:**
   When retrieving lists of jobs, use pagination to manage large datasets

## Support and Resources

- **API Documentation:** https://developers.gophr.com/docs
- **Issue Tracker:** https://github.com/shimango/gophr/issues
- **Packagist:** https://packagist.org/packages/shimango/gophr

## License

This library is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for version history and changes.
