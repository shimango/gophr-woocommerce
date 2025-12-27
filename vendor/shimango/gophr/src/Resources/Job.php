<?php

namespace Shimango\Gophr\Resources;

use Shimango\Gophr\Exceptions\InvalidReturnTypeException;
use Shimango\Gophr\Http\Responses\Jobs\CancelJobResponse;
use Shimango\Gophr\Http\Responses\Jobs\CreateJobResponse;
use Shimango\Gophr\Http\Responses\Jobs\GetJobResponse;
use Shimango\Gophr\Http\Responses\Jobs\GetQuoteResponse;
use Shimango\Gophr\Http\Responses\Jobs\ListJobsResponse;
use Shimango\Gophr\Http\Responses\Jobs\UpdateJobResponse;

/**
 * A job resource represents a collection of deliveries as well as the metadata associated with them.
 * A Job will have a pickup and many dropoffs.
 * @package Shimango\Gophr\Resources
 */
final class Job extends AbstractResource
{
    /**
     * Creates a job
     * @throws InvalidReturnTypeException
     */
    public function createJob(array $jobData): CreateJobResponse
    {
        $apiEndPoint = $this->gophrRequest->getEndpoint() . "/jobs" . $this->generateUrlParameters();
        /** @var CreateJobResponse $response */
        $response = $this->create($apiEndPoint, $jobData, CreateJobResponse::class);
        return $response;
    }

    /**
     * Gets a job
     * @throws InvalidReturnTypeException
     */
    public function getJob(string $jobId): GetJobResponse
    {
        $apiEndPoint = $this->gophrRequest->getEndpoint() . sprintf('/jobs/%s', $jobId) . $this->generateUrlParameters();
        /** @var GetJobResponse $response */
        $response = $this->read($apiEndPoint, GetJobResponse::class);
        return $response;
    }

    /**
     * Updates a job
     * @throws InvalidReturnTypeException
     */
    public function updateJob(string $jobId, array $jobData): UpdateJobResponse
    {
        $apiEndPoint = $this->gophrRequest->getEndpoint() . sprintf('/jobs/%s', $jobId) . $this->generateUrlParameters();
        /** @var UpdateJobResponse $gophrResponse */
        $gophrResponse = $this->update($apiEndPoint, $jobData, UpdateJobResponse::class);
        return $gophrResponse;
    }

    /**
     * Cancels a job
     * @throws InvalidReturnTypeException
     */
    public function CancelJob(string $jobId, array $jobData): CancelJobResponse
    {
        $apiEndPoint = $this->gophrRequest->getEndpoint() . sprintf('/jobs/%s/cancel', $jobId) . $this->generateUrlParameters();
        /** @var CancelJobResponse $response */
        $response = $this->create($apiEndPoint, $jobData, CancelJobResponse::class);
        return $response;
    }

    /**
     * Lists jobs
     * @throws InvalidReturnTypeException
     */
    public function listJobs(int $page = 1 ,int $count = 15, bool $include_finished = false): ListJobsResponse
    {
        $include_finished = (int)$include_finished;
        $apiEndPoint = $this->gophrRequest->getEndpoint() . "/jobs" . $this->generateUrlParameters([
                'page'=> $page,
                'count' => $count,
                'include-finished' => $include_finished,
            ]) ;
        /** @var ListJobsResponse $response */
        $response = $this->read($apiEndPoint, ListJobsResponse::class);
        return $response;
    }

    /**
     * Gets a quote
     * @throws InvalidReturnTypeException
     */
    public function getQuote(array $jobData): GetQuoteResponse
    {
        $apiEndPoint = $this->gophrRequest->getEndpoint() . "/quotes" . $this->generateUrlParameters();
        /** @var GetQuoteResponse $response */
        $response = $this->create($apiEndPoint, $jobData, GetQuoteResponse::class);
        return $response;
    }
}
