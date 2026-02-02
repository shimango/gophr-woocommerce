<?php

namespace Shimango\Gophr;

use Shimango\Gophr\Common\Configuration;
use Shimango\Gophr\Exceptions\GophrException;
use Shimango\Gophr\Exceptions\InvalidReturnTypeException;
use Shimango\Gophr\Factories\ResourceFactory;
use Shimango\Gophr\Http\Responses\Deliveries\CancelDeliveryResponse;
use Shimango\Gophr\Http\Responses\Deliveries\CreateDeliveryResponse;
use Shimango\Gophr\Http\Responses\Deliveries\GetDeliveryResponse;
use Shimango\Gophr\Http\Responses\Deliveries\ListDeliveriesResponse;
use Shimango\Gophr\Http\Responses\Deliveries\ProgressDeliveryStatusResponse;
use Shimango\Gophr\Http\Responses\Deliveries\UpdateDeliveryResponse;
use Shimango\Gophr\Http\Responses\Jobs\CancelJobResponse;
use Shimango\Gophr\Http\Responses\Jobs\CreateJobResponse;
use Shimango\Gophr\Http\Responses\Jobs\GetJobResponse;
use Shimango\Gophr\Http\Responses\Jobs\GetQuoteResponse;
use Shimango\Gophr\Http\Responses\Jobs\ListJobsResponse;
use Shimango\Gophr\Http\Responses\Jobs\UpdateJobResponse;
use Shimango\Gophr\Http\Responses\Parcels\CreateParcelResponse;
use Shimango\Gophr\Http\Responses\Parcels\DeleteParcelResponse;
use Shimango\Gophr\Http\Responses\Parcels\GetParcelResponse;
use Shimango\Gophr\Http\Responses\Parcels\ListParcelsResponse;
use Shimango\Gophr\Http\Responses\Parcels\UpdateParcelResponse;

final class Client
{
    private ResourceFactory $resourceFactory;

    /**
     * Creates a Gophr client object with the given configuration settings.
     */
    public function __construct(private Configuration $configuration)
    {
        $this->resourceFactory = new ResourceFactory();
    }

    /**
     * Gets the base API endpoint URL
     * @return string The full base API endpoint URL
     */
    public function getBaseApiEndpoint(): string
    {
        return $this->configuration->getBaseUrl() . $this->configuration->getApiEndPoint();
    }

    /**
     * Gets a quote
     * @param array $jobData The job data
     * @throws GophrException
     */
    public function getQuote(array $jobData): GetQuoteResponse
    {
        return $this->resourceFactory->makeJobResource($this->configuration)->getQuote($jobData);
    }

    /**
     * Gets a job.
     * @param string $jobId The job id
     * @throws GophrException
     */
    public function getJob(string $jobId): GetJobResponse
    {
        return $this->resourceFactory->makeJobResource($this->configuration)->getJob($jobId);
    }

    /**
     * Creates a job
     * @param array $jobData The job data
     * @throws GophrException
     */
    public function createJob(array $jobData): CreateJobResponse
    {
        return $this->resourceFactory->makeJobResource($this->configuration)->createJob($jobData);
    }

    /**
     * Updates a job
     * @param string $jobId The id of the job
     * @param array $jobData The job data
     * @throws GophrException
     */
    public function updateJob(string $jobId, array $jobData): UpdateJobResponse
    {
        return $this->resourceFactory->makeJobResource($this->configuration)->updateJob($jobId, $jobData);
    }

    /**
     * Cancel a job
     * @param string $jobId The id of the job
     * @param array $jobData The job data
     * @throws GophrException
     */
    public function cancelJob(string $jobId, array $jobData): CancelJobResponse
    {
        return $this->resourceFactory->makeJobResource($this->configuration)->CancelJob($jobId, $jobData);
    }

    /**
     * Lists jobs
     * @throws GophrException
     */
    public function listJobs(int $page = 1 ,int $count = 15, bool $include_finished = false): ListJobsResponse
    {
        return $this->resourceFactory->makeJobResource($this->configuration)->listJobs($page, $count, $include_finished);
    }

    /**
     * Gets a delivery
     * @param string $jobId The id of the parent job
     * @param string $deliveryId The id of the delivery
     * @throws GophrException
     */
    public function getDelivery(string $jobId, string $deliveryId): GetDeliveryResponse
    {
        return $this->resourceFactory->makeDeliveryResource($this->configuration)->getDelivery($jobId, $deliveryId);
    }

    /**
     * Creates a delivery
     * @param string $jobId The id of the parent job
     * @param array $deliveryData The delivery data
     * @throws GophrException
     */
    public function createDelivery(string $jobId, array $deliveryData): CreateDeliveryResponse
    {
        return $this->resourceFactory->makeDeliveryResource($this->configuration)->createDelivery($jobId, $deliveryData);
    }

    /**
     * Updates a delivery
     * @param string $jobId The id of the parent job
     * @param string $deliveryId The id of the delivery
     * @param array $deliveryData The delivery data
     * @throws GophrException
     */
    public function updateDelivery(string $jobId, string $deliveryId, array $deliveryData): UpdateDeliveryResponse
    {
        return $this->resourceFactory->makeDeliveryResource($this->configuration)->updateDelivery($jobId, $deliveryId, $deliveryData);
    }

    /**
     * Lists deliveries
     * @throws GophrException
     */
    public function listDeliveries(string $jobId): ListDeliveriesResponse
    {
        return $this->resourceFactory->makeDeliveryResource($this->configuration)->listDeliveries($jobId);
    }

    /**
     * Cancels a delivery
     * @param string $jobId The id of the parent job
     * @param string $deliveryId The id of the delivery
     * @param array $deliveryData The delivery data
     * @throws GophrException
     */
    public function cancelDelivery(string $jobId, string $deliveryId, array $deliveryData): CancelDeliveryResponse
    {
        return $this->resourceFactory->makeDeliveryResource($this->configuration)->cancelDelivery($jobId, $deliveryId, $deliveryData);
    }

    /**
     * Gets a parcel
     * @param string $jobId The id of the parent job
     * @param string $deliveryId The id of the delivery
     * @param string $parcelId The parcel id
     * @throws GophrException
     * @throws InvalidReturnTypeException
     */
    public function getParcel(string $jobId, string $deliveryId, string $parcelId): GetParcelResponse
    {
        return $this->resourceFactory->makeParcelResource($this->configuration)->getParcel($jobId, $deliveryId, $parcelId);
    }

    /**
     * Creates a parcel
     * @param string $jobId The id of the parent job
     * @param string $deliveryId The id of the delivery
     * @param array $parcelData The parcel data
     * @throws GophrException
     * @throws InvalidReturnTypeException
     */
    public function createParcel(string $jobId, string $deliveryId, array $parcelData): CreateParcelResponse
    {
        return $this->resourceFactory->makeParcelResource($this->configuration)->createParcel($jobId, $deliveryId, $parcelData);
    }

    /**
     * Updates a parcel
     * @param string $jobId The id of the parent job
     * @param string $deliveryId The id of the delivery
     * @param string $parcelId The parcel id
     * @param array $parcelData The parcel data
     * @throws GophrException
     * @throws InvalidReturnTypeException
     */
    public function updateParcel(string $jobId, string $deliveryId, string $parcelId, array $parcelData): UpdateParcelResponse
    {
        return $this->resourceFactory->makeParcelResource($this->configuration)->updateParcel($jobId, $deliveryId, $parcelId, $parcelData);
    }

    /**
     * Lists parcels
     * @param string $jobId The id of the parent job
     * @param string $deliveryId The id of the delivery
     * @throws GophrException
     * @throws InvalidReturnTypeException
     */
    public function listParcels(string $jobId, string $deliveryId): ListParcelsResponse
    {
        return $this->resourceFactory->makeParcelResource($this->configuration)->listParcels($jobId, $deliveryId);
    }

    /**
     * Deletes a parcel
     * @param string $jobId The id of the parent job
     * @param string $deliveryId The id of the delivery
     * @param string $parcelId The parcel id
     * @throws GophrException
     * @throws InvalidReturnTypeException
     */
    public function deleteParcel(string $jobId, string $deliveryId, string $parcelId): DeleteParcelResponse
    {
        return $this->resourceFactory->makeParcelResource($this->configuration)->deleteParcel($jobId, $deliveryId, $parcelId);
    }

    /**
     * Progresses a delivery status. This functionality is not available in production.
     * @param string $jobId The id of the parent job
     * @param string $deliveryId The id of the delivery
     * @return progressDeliveryStatusResponse
     * @throws GophrException
     */
    public function progressDeliveryStatus(string $jobId, string $deliveryId): progressDeliveryStatusResponse
    {
        return $this->resourceFactory->makeDeliveryResource($this->configuration)->progressDeliveryStatus($jobId, $deliveryId);
    }
}
