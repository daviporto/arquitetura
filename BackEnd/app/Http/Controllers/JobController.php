<?php

namespace App\Http\Controllers;

use App\Domain\Abstract\AbstractRepository;
use App\Domain\Job\JobDomain;
use App\Domain\Job\JobRepository;
use App\Domain\Job\JobService;
use App\Exceptions\Abstract\AbstractFindMeException;
use App\Exceptions\Job\JobIdMustBeAnIntegerException;
use App\Exceptions\Job\JobNotFoundException;
use App\Http\Requests\Job\IndexJobRequest;
use App\Http\Requests\Job\JobRequestHavingId;
use App\Http\Requests\Job\ShowJobRequest;
use App\Http\Requests\Job\StoreJobRequest;
use App\Http\Requests\Job\UpdateJobRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as IluminateResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class JobController extends Controller
{
    public function store(StoreJobRequest $request): JsonResponse|IluminateResponse
    {
        try {
            (new JobService())->store($request->validated(), $request->getLoggedUserId());

            return response(status: Response::HTTP_CREATED);
        } catch (AbstractFindMeException  $exception) {
            return response()->json(
                $exception->render(),
                status: $exception->getHttpCode()
            );
        } catch (Throwable) {
            return response()
                ->json(
                    ['error' => 'Server error'],
                    Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws Throwable
     */
    public function update(UpdateJobRequest $request): JsonResponse|IluminateResponse
    {
        try {
            (new JobService())
                ->update(
                    $request->validated(),
                    $request->getLoggedUserId(),
                    $request->getJobId()
                );

            return response(status: Response::HTTP_NO_CONTENT);
        } catch (AbstractFindMeException  $exception) {
            return response()->json(
                $exception->render(),
                status: $exception->getHttpCode()
            );
        } catch (Throwable) {
            return response()
                ->json(
                    ['error' => 'Server error'],
                    Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws JobNotFoundException
     * @throws JobIdMustBeAnIntegerException
     * @throws Throwable
     */
    public function destroy(JobRequestHavingId $request): JsonResponse|IluminateResponse
    {
        $repository = app(JobRepository::class);

        try {
            $repository->beginTransaction();

            $domain = new JobDomain($repository);
            $domain->setId($request->getJobId())
                ->setUserId($request->getLoggedUserId());

            if (!$domain->exists($domain->getId())) {
                throw new JobNotFoundException($domain->getId());
            }
            $domain->delete();

            $repository->commitTransaction();

            return response()->noContent();
        } catch (AbstractFindMeException  $exception) {
            $this->commonLogLogic($repository, $exception);

            return response()->json(
                $exception->render(),
                status: $exception->getHttpCode()
            );
        } catch (Exception $exception) {
            $this->commonLogLogic($repository, $exception);

            return response()
                ->json(
                    ['error' => 'Server error'],
                    Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param AbstractRepository $repository
     * @param Exception $exception
     * @return void
     * @throws Throwable
     */
    public function commonLogLogic(AbstractRepository $repository, Exception $exception): void
    {
        $repository->rollbackTransaction();

        Log::error($exception);
    }

    public function index(IndexJobRequest $request): JsonResponse|IluminateResponse
    {
        $repository = app(JobRepository::class);
        $domain = new JobDomain($repository);

        try {
            return response()
                ->json([
                    'data' => $domain->jobsWithIncludes($request->getIncludes())
                ]);
        } catch (Exception $exception) {
            Log::error($exception);

            return response()
                ->json(
                    ['error' => 'Server error'],
                    Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws JobNotFoundException
     * @throws JobIdMustBeAnIntegerException
     */
    public function show(ShowJobRequest $request): JsonResponse|IluminateResponse
    {
        try {
        $repository = app(JobRepository::class);

        $domain = new JobDomain($repository);
        $domain->setId($request->getJobId())
            ->setUserId($request->getLoggedUserId());

        if (!$domain->exists($domain->getId())) {
            throw new JobNotFoundException($domain->getId());
        }
            $job = $domain->getJobWithIncludes($request->getIncludes());

            return response()->json([
                'data' => $job
            ]);
        } catch (AbstractFindMeException  $exception) {
            $this->commonLogLogic($repository, $exception);

            return response()->json(
                $exception->render(),
                status: $exception->getHttpCode()
            );
        } catch (Exception $exception) {
            $this->commonLogLogic($repository, $exception);

            return response()
                ->json(
                    ['error' => 'Server error'],
                    Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
