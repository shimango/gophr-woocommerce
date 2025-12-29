A PHP client for the Gophr Courier Service commercial API
===============================
Documentation regarding the Gophr Restfull API can be found at: https://developers.gophr.com/docs

[![Build Status](https://github.com/shimango/gophr/actions/workflows/tests.yml/badge.svg?branch=master)](https://github.com/shimango/gophr/actions/workflows/tests.yml?query=branch%3Amaster)
[![Latest Stable Version](http://poser.pugx.org/shimango/gophr/v)](https://packagist.org/packages/shimango/gophr)
[![Total Downloads](http://poser.pugx.org/shimango/gophr/downloads)](https://packagist.org/packages/shimango/gophr)
[![License](http://poser.pugx.org/shimango/gophr/license)](https://packagist.org/packages/shimango/gophr)
[![PHP Version Require](http://poser.pugx.org/shimango/gophr/require/php)](https://packagist.org/packages/shimango/gophr)


Requirements
-----
The Gophr Commercial API uses API Key based authentication. In order to use this client you need to obtain an API key 
from Gophr. See how to obtain it here: https://developers.gophr.com/docs/authorisation 


Installation
-----
using composer:
```shell
  composer require shimango/gophr
```
Or add the following to your composer.json file for your project:
```json
{
    "require": {
        "shimango/gophr": "^2.0.2"
    }
}
```
Alternatively, download this package from github, and run `composer install` in the directory containing the 
composer.json file to generate the autoloader, then require the autoloader using
```shell
  require_once "vendor/autoload.php"
```


Usage
-----
The two main classes in this library are `Shimango\Gophr\Common\Configuration` and `Shimango\Gophr\Client`
- The `Configuration` class is used to set all the variables required when making calls to the Rest API. The 
configuration parameters are passed in the constructor of that class or can be set via setter methods. These are:
```php
  @param string   $apiKey - The API key obtained from Gophr
  @param bool     $isSandbox (Default false) - If true the Gophr sandbox environment will be used
  @param string   $apiVersion (Default "v2") - The API version to be used. Currently, only v2 is supported
  @param int|null $proxyPort (Default null) - If the calls are sent via proxy the port number can be set here 
  @param bool     $proxyVerifySSL (Default false) - Sets verify proxy SSL to true if using a proxy is being used
```
- The `Client` is the main class in this library and has functions to allow for easy calls to the Gophr commercial API.
Below are a couple of simple examples of how to query the gophr API for a list of jobs:
```php
use Shimango\Gophr\Client;
use Shimango\Gophr\Common\Configuration;

$config = new Configuration('YOUR_API_KEY');
$gophr = new Client($config);

$response = $gophr->listJobs();
var_dump($response->getContentsArray());
```

```php
use Shimango\Gophr\Client;
use Shimango\Gophr\Common\Configuration;

$isSandbox = true;

$config = new Configuration('YOUR_API_KEY', $isSandbox);
$gophr = new Client($config);

$page = 2; // The page number to be returned
$count = 15; // The number of jobs returned per page
$include_finished = false;

$response = $gophr->listJobs($page, $count, $include_finished);
var_dump($response->getContentsArray());
```

### Jobs
A Job is a collection of deliveries that will be assigned to a courier and completed by them in the same trip. This 
library provides the following methods to interact with Jobs:
```php
// Creates a job with a single pickup point and multiple drop off locations.
createJob(array $jobData)

// Get the details of a job with the given job id.
getJob(string $jobId)

// Allows a job to be updated to confirmed.
updateJob(string $jobId, array $jobData)

// Cancels a job
CancelJob(string $jobId, array $jobData)

// Get all jobs associated with the API key used.
listJobs(int $page = 1 ,int $count = 15, bool $include_finished = false)
````

### Deliveries
A Delivery represents a pickup - dropoff combination along with any parcels that are associated with them. This library 
provides the following methods to interact with Deliveries:
```php
// Creates a delivery for an existing job. The delivery consists of a pickup, dropoff and collection of parcels.
// Note that adding deliveries to a job is not allowed once the job has been confirmed.
createDelivery(string $jobId, array $deliveryData)

// Get the details of a delivery.
getDelivery(string $jobId, string $deliveryId)

// Updates a delivery. Parcels may also be added / edited via this method.
// Note that updating deliveries is not allowed once the job has been confirmed.
updateDelivery(string $jobId, string $deliveryId, array $deliveryData)

// Get a list of deliveries for a given job.
listDeliveries(string $jobId)

// Cancels a delivery. Note that cancelling deliveries is not allowed once the job has been confirmed.
cancelDelivery(string $jobId, string $deliveryId, array $deliveryData)

// Progresses a delivery status. This functionality is not available in production.
progressDeliveryStatus(string $jobId, string $deliveryId)
```

### Parcels
Parcels represent the individual packages within a delivery. A parcel belongs to a delivery and deliveries can have many 
parcels. This library provides the following methods to interact with Parcels:
```php
// Creates a parcel for the given delivery.
// Note that adding parcels to a delivery is not allowed once the job has been confirmed.
createParcel(string $jobId, string $deliveryId, array $parcelData)

// Get the details of a given parcel.
getParcel(string $jobId, string $deliveryId, string $parcelId)

// Updates a parcel. Note that updating parcels is not allowed once the job has been confirmed.
// Note that updating parcels is not allowed once the job has been confirmed.
updateParcel(string $jobId, string $deliveryId, string $parcelId, array $deliveryData)

// Removes a parcel from a delivery. If the parcel count is 0 after deletion then the delivery will be cancelled.
// Note that deleting parcels is not allowed once the job has been confirmed.
deleteParcel(string $jobId, string $deliveryId, string $parcelId)

// Returns a collection of parcels for the given delivery.
listParcels(string $jobId, string $deliveryId)
```

All the methods above are available from the main `\Shimango\Gophr\Client` class. Alternatively, individual resources 
can be used instead. The resource objects will only have the methods that affect that resource directly. These resources 
are:
- `\Shimango\Gophr\Resources\Job`
- `\Shimango\Gophr\Resources\Delivery`
- `\Shimango\Gophr\Resources\Parcel`

All calls to the Gophr API via this library will return a GophrResponse object. This class is PSR-7 compliant and also, 
amongst other common response methods, provides two methods to help get the body of the response. These are:
```php 
getContentsArray() // Returns the response body as an array
getContentsObject() // Returns the response body as an object 
```

### Request and Response payloads
Examples of the request and response payloads for each API call can be found at: https://developers.gophr.com/reference


Test
----
```shell
composer test
```
