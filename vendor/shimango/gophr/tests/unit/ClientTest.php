<?php
namespace Shimango\Gophr\Tests\Unit;

use Shimango\Gophr\Exceptions\GophrException;
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
use Shimango\Gophr\Tests\Unit\Base\BaseTest;
use Shimango\Gophr\Tests\Unit\Base\Payloads;

class ClientTest extends BaseTest
{
    /**
     * @return void
     * @throws GophrException
     */
    public function testGetQuote(): void
    {
        $jobData = Payloads::getCreateJobPayload();
        $client = $this->getClientForJobTest('create', GetQuoteResponse::class, "/v2-commercial-api/quotes", $jobData);
        $response = $client->getQuote($jobData);
        $this->assertInstanceOf(GetQuoteResponse::class, $response);
    }

    /**
     * @return void
     * @throws GophrException
     */
    public function testCreateJob(): void
    {
        $jobData = Payloads::getCreateJobPayload();
        $client = $this->getClientForJobTest('create', CreateJobResponse::class, "/v2-commercial-api/jobs", $jobData);
        $response = $client->createJob($jobData);
        $this->assertInstanceOf(CreateJobResponse::class, $response);
    }

    /**
     * @return void
     * @throws GophrException
     */
    public function testGetJob(): void
    {
        $jobId = '1a2b3c';
        $client = $this->getClientForJobTest('read', GetJobResponse::class, "/v2-commercial-api/jobs/{$jobId}");
        $response = $client->getJob($jobId);
        $this->assertInstanceOf(GetJobResponse::class, $response);
    }

    /**
     * @return void
     * @throws GophrException
     */
    public function testUpdateJob(): void
    {
        $jobId = '123abc';
        $jobData = Payloads::getUpdateJobPayload();
        $client = $this->getClientForJobTest('update', UpdateJobResponse::class, "/v2-commercial-api/jobs/{$jobId}", $jobData);
        $response = $client->updateJob($jobId, $jobData);
        $this->assertInstanceOf(UpdateJobResponse::class, $response);
    }

    /**
     * @return void
     * @throws GophrException
     */
    public function testCancelJob(): void
    {
        $jobId = 'abc123';
        $jobData = Payloads::getCancelJobPayload();
        $client = $this->getClientForJobTest('create', CancelJobResponse::class, "/v2-commercial-api/jobs/{$jobId}/cancel", $jobData);
        $response = $client->cancelJob($jobId, $jobData);
        $this->assertInstanceOf(CancelJobResponse::class, $response);
    }

    /**
     * @return void
     * @throws GophrException
     */
    public function testListJobsWithDefaultParameters(): void
    {
        $client = $this->getClientForJobTest('read', ListJobsResponse::class, "/v2-commercial-api/jobs?page=1&count=15&include-finished=0");
        $response = $client->listJobs();
        $this->assertInstanceOf(ListJobsResponse::class, $response);
    }

    /**
     * @return void
     * @throws GophrException
     */
    public function testListJobsWithSpecificParameters(): void
    {
        $client = $this->getClientForJobTest('read', ListJobsResponse::class, "/v2-commercial-api/jobs?page=5&count=50&include-finished=1");
        $response = $client->listJobs(5, 50, 1);
        $this->assertInstanceOf(ListJobsResponse::class, $response);
    }

    /** ************************************************************************************************************* */

    /**
     * @return void
     * @throws GophrException
     */
    public function testCreateDelivery(): void
    {
        $jobId = '12ab3c';
        $deliveryData = Payloads::getCreateDeliveryPayload();
        $client = $this->getClientForDeliveryTest('create', CreateDeliveryResponse::class, "/v2-commercial-api/jobs/{$jobId}/deliveries", $deliveryData);
        $response = $client->createDelivery($jobId, $deliveryData);
        $this->assertInstanceOf(CreateDeliveryResponse::class, $response);
    }

    /**
     * @return void
     * @throws GophrException
     */
    public function testGetDelivery(): void
    {
        $jobId = '12ab3c';
        $deliveryId = '456def';
        $client = $this->getClientForDeliveryTest('read', GetDeliveryResponse::class, "/v2-commercial-api/jobs/{$jobId}/deliveries/{$deliveryId}");
        $response = $client->getDelivery($jobId, $deliveryId);
        $this->assertInstanceOf(GetDeliveryResponse::class, $response);
    }

    /**
     * @return void
     * @throws GophrException
     */
    public function testUpdateDelivery(): void
    {
        $jobId = '12ab3c';
        $deliveryId = 'def456';
        $deliveryData = Payloads::getUpdateDeliveryPayload();
        $client = $this->getClientForDeliveryTest('read', UpdateDeliveryResponse::class, "/v2-commercial-api/jobs/{$jobId}/deliveries/{$deliveryId}", $deliveryData);
        $response = $client->updateDelivery($jobId, $deliveryId, $deliveryData);
        $this->assertInstanceOf(UpdateDeliveryResponse::class, $response);
    }

    /**
     * @return void
     * @throws GophrException
     */
    public function testCancelDelivery(): void
    {
        $jobId = '12ab3c';
        $deliveryId = 'def456';
        $deliveryData = Payloads::getCancelDeliveryPayload();
        $client = $this->getClientForDeliveryTest('create', CancelDeliveryResponse::class, "/v2-commercial-api/jobs/{$jobId}/deliveries/{$deliveryId}/cancel", $deliveryData);
        $response = $client->cancelDelivery($jobId, $deliveryId, $deliveryData);
        $this->assertInstanceOf(CancelDeliveryResponse::class, $response);
    }

    /**
     * @return void
     * @throws GophrException
     */
    public function testListDeliveries(): void
    {
        $jobId = '12ab3c';
        $client = $this->getClientForDeliveryTest('read', ListDeliveriesResponse::class, "/v2-commercial-api/jobs/{$jobId}/deliveries");
        $response = $client->listDeliveries($jobId);
        $this->assertInstanceOf(ListDeliveriesResponse::class, $response);
    }

    /**
     * @return void
     * @throws GophrException
     */
    public function testProgressDeliveryStatus(): void
    {
        $jobId = '12ab3c';
        $deliveryId = 'def456';
        $client = $this->getClientForDeliveryTest('create', ProgressDeliveryStatusResponse::class, "/v2-commercial-api/jobs/{$jobId}/deliveries/{$deliveryId}/progress");
        $response = $client->progressDeliveryStatus($jobId, $deliveryId);
        $this->assertInstanceOf(ProgressDeliveryStatusResponse::class, $response);
    }

    /** ************************************************************************************************************* */

    /**
     * @return void
     * @throws GophrException
     */
    public function testCreateParcel(): void
    {
        $jobId = '12ab3c';
        $deliveryId = 'def456';
        $parcelData = Payloads::getCreateParcelPayload();
        $client = $this->getClientForParcelTest('create', CreateParcelResponse::class, "/v2-commercial-api/jobs/{$jobId}/deliveries/{$deliveryId}/parcels", $parcelData);
        $response = $client->createParcel($jobId, $deliveryId, $parcelData);
        $this->assertInstanceOf(CreateParcelResponse::class, $response);
    }

    /**
     * @return void
     * @throws GophrException
     */
    public function testGetParcel(): void
    {
        $jobId = '12ab3c';
        $deliveryId = 'def456';
        $parcelId = 'ghi789';
        $client = $this->getClientForParcelTest('read', GetParcelResponse::class, "/v2-commercial-api/jobs/{$jobId}/deliveries/{$deliveryId}/parcels/{$parcelId}");
        $response = $client->getParcel($jobId, $deliveryId, $parcelId);
        $this->assertInstanceOf(GetParcelResponse::class, $response);
    }

    /**
     * @return void
     * @throws GophrException
     */
    public function testUpdateParcel(): void
    {
        $jobId = '12ab3c';
        $deliveryId = 'def456';
        $parcelId = 'ghi789';
        $parcelData = Payloads::getUpdateParcelPayload();
        $client = $this->getClientForParcelTest('read', UpdateParcelResponse::class, "/v2-commercial-api/jobs/{$jobId}/deliveries/{$deliveryId}/parcels/{$parcelId}", $parcelData);
        $response = $client->updateParcel($jobId, $deliveryId, $parcelId, $parcelData);
        $this->assertInstanceOf(UpdateParcelResponse::class, $response);
    }

    /**
     * @return void
     * @throws GophrException
     */
    public function testDeleteParcel(): void
    {
        $jobId = '12ab3c';
        $deliveryId = 'def456';
        $parcelId = 'ghi789';
        $client = $this->getClientForParcelTest('read', DeleteParcelResponse::class, "/v2-commercial-api/jobs/{$jobId}/deliveries/{$deliveryId}/parcels/{$parcelId}");
        $response = $client->deleteParcel($jobId, $deliveryId, $parcelId);
        $this->assertInstanceOf(DeleteParcelResponse::class, $response);
    }

    /**
     * @return void
     * @throws GophrException
     */
    public function testListParcels(): void
    {
        $jobId = '12ab3c';
        $deliveryId = 'def456';
        $client = $this->getClientForParcelTest('read', ListParcelsResponse::class, "/v2-commercial-api/jobs/{$jobId}/deliveries/{$deliveryId}/parcels");
        $response = $client->listParcels($jobId, $deliveryId);
        $this->assertInstanceOf(ListParcelsResponse::class, $response);
    }
}
