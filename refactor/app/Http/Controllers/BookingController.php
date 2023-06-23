<?php

namespace DTApi\Http\Controllers;

use DTApi\Models\Job;
use DTApi\Http\Requests;
use DTApi\Models\Distance;
use Illuminate\Http\Request;
use DTApi\Repository\BookingRepository;


//! code inside every function should be in try catch block

/**
 * Class BookingController
 * @package DTApi\Http\Controllers
 */
class BookingController extends Controller
{

    /**
     * @var BookingRepository
     */
    protected $repository;

    /**
     * BookingController constructor.
     * @param BookingRepository $bookingRepository
     */
    public function __construct(BookingRepository $bookingRepository)
    {
        $this->repository = $bookingRepository;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {

        try {

            $user_id = Auth::id();

            // It looks like we've ADMIN_ROLE_ID and SUPERADMIN_ROLE_ID in integer format
            // thats not a good practice as it looks like we are managing roles not in propery way
            // we've some good packages like spatie laravel permissions to handle role and permission
            // and need to implmeent this or these kind of official package for the roles and permission 
            // management

            // BTW here I'm integrating php in_array method
            if (in_array($user_id, [env('ADMIN_ROLE_ID'), env('SUPERADMIN_ROLE_ID')])) {
                $response = $this->repository->getAll($request);
            } else {
                $response = $this->repository->getUsersJobs($user_id);
            }
            //! this response should be dynamice
            return response()->json($response, 200);
        } catch (\Exception $e) {
            //! this response should be dynamice
            return response()->json(array('message' => $e->getMessage()), 500);
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $job = $this->repository->findWithRelationsTranslatorJobRelUser($id);
        if (is_null($job)) {
            //! this response should be dynamice
            return response()->json(array('message' => 'no record found.'), 404);
        }
        //! this response should be dynamice
        return response()->json($job, 200);
        try {
        } catch (\Exception $e) {
            //! this response should be dynamice
            return response()->json(array('message' => $e->getMessage()), 500);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        try {
            $response   = $this->repository->store($request->__authenticatedUser, $request->all());
            $status     = $response['status'] == 'fail' ? 400 : 200;
            //! this response should be dynamice
            return response()->json($response, $status);
        } catch (\Exception $e) {
            //! this response should be dynamice
            return response()->json(array('message' => $e->getMessage()), 500);
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update($id, Request $request)
    {
        try {
            $response   = $this->repository->updateJob($id, array_except($request->all(), ['_token', 'submit']), $request->__authenticatedUser);
            //! this response should be dynamice
            return response()->json($response, 200);
        } catch (\Exception $e) {
            //! this response should be dynamice
            return response()->json(array('message', $e->getMessage()), 500);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function immediateJobEmail(Request $request)
    {
        try {
            // no use of $adminSenderEmail found
            // $adminSenderEmail   = config('app.adminemail');
            $response           = $this->repository->storeJobEmail();
            //! this response should be dynamice
            return response()->json($response, 200);
        } catch (\Exception $e) {
            //! this response should be dynamice
            return response()->json(array('message' => $e->getMessage()), 500);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getHistory(Request $request)
    {

        try {

            $user_id = $request->has('user_id');

            if ($user_id) {
                $response = $this->repository->getUsersJobsHistory($request->user_id, $request);
                //! this response should be dynamice
                return response()->json($response, 200);
            } else {
                //! this response should be dynamice
                return response()->json(array('message' => 'Unauthorized'), 401);
            }
        } catch (\Exception $e) {
            //! this response should be dynamice
            return response()->json(array('message' => $e->getMessage()), 500);
        }

        return null;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function acceptJob(Request $request)
    {
        try {
            $response = $this->repository->acceptJob($request->all(), $request->__authenticatedUser);
            //! this response should be dynamice
            return response()->json($response, 200);
        } catch (\Exception $e) {
            //! this response should be dynamice
            return response()->json(array('message' => $e->getMessage()), 500);
        }
    }

    public function acceptJobWithId(Request $request)
    {
        try {
            $jobId = $request->input('job_id');
            $user = $request->__authenticatedUser;

            $response = $this->repository->acceptJobWithId($jobId, $user);

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function cancelJob(Request $request)
    {
        try {
            $response = $this->repository->cancelJobAjax($request->all(), $user);
            //! this response should be dynamice
            return response()->json($response, 200);
        } catch (\Exception $e) {
            //! this response should be dynamice
            return response()->json(array('message' => $e->getMessage()), 500);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */

    //!  Every function code shous be in try catch bloc 
    public function endJob(Request $request)
    {
        try {

            $response = $this->repository->endJob($request->all());
            //! this response should be dynamice
            return response()->json($response, 200);
        } catch (\Exception $e) {
            //! this response should be dynamice
            return response()->json(array('message' => $e->getMessage()), 500);
        }
    }

    public function customerNotCall(Request $request)
    {
        try {
            $response = $this->repository->customerNotCall($request->all());
            //! this response should be dynamice
            return response()->json($response, 200);
        } catch (\Exception $e) {
            //! this response should be dynamice
            return response()->json(array('message' => $e->getMessage()), 500);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getPotentialJobs(Request $request)
    {
        try {
            // $data variable is not in use
            // $data = $request->all();

            $response = $this->repository->getPotentialJobs($request->__authenticatedUser);

            //! this response should be dynamice
            return response()->json($response, 200);
        } catch (\Exception $e) {
            //! this response should be dynamice
            return response()->json(array('message' => $e->getMessage()), 500);
        }
    }

    public function distanceFeed(Request $request)
    {
        try {
            $data = $request->all();

            $distance = isset($data['distance']) && $data['distance'] != "" ? $data['distance'] : "";
            $time = isset($data['time']) && $data['time'] != "" ? $data['time'] : "";
            $jobid = isset($data['jobid']) && $data['jobid'] != "" ? $data['jobid'] : "";
            $session = isset($data['session_time']) && $data['session_time'] != "" ? $data['session_time'] : "";
            $admincomment = isset($data['admincomment']) && $data['admincomment'] != "" ? $data['admincomment'] : "";

            $flagged = $data['flagged'] == 'true' ? 'yes' : 'no';
            $manually_handled = $data['manually_handled'] == 'true' ? 'yes' : 'no';
            $by_admin = $data['by_admin'] == 'true' ? 'yes' : 'no';

            if (
                $time || $distance
            ) {
                $affectedRows = Distance::where('job_id', '=', $jobid)->update(['distance' => $distance, 'time' => $time]);
            }

            if ($admincomment || $session || $flagged || $manually_handled || $by_admin) {
                $updatedData = [
                    'admin_comments' => $admincomment,
                    'flagged' => $flagged,
                    'session_time' => $session,
                    'manually_handled' => $manually_handled,
                    'by_admin' => $by_admin
                ];
                $affectedRows1 = Job::where('id', '=', $jobid)->update($updatedData);
            }

            return response('Record updated!');
        } catch (\Exception $e) {
            //! this response should be dynamice
            return response()->json(array('message' => $e->getMessage()), 500);
        }
    }

    public function reopen(Request $request)
    {
        try {
            $response = $this->repository->reopen($request->all());
            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function resendNotifications(Request $request)
    {
        try {
            $jobId = $request->input('job_id');
            $job = $this->repository->find($jobId);

            if ($job) {
                $job_data = $this->repository->jobToData($job);
                $this->repository->sendNotificationTranslator($job, $job_data, '*');

                return response()->json(['success' => 'Push sent'], 200);
            } else {
                return response()->json(['error' => 'Job not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Sends SMS to Translator
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function resendSMSNotifications(Request $request)
    {
        try {
            $jobId = $request->input('jobid');
            $job = $this->repository->find($jobId);

            if ($job) {
                $job_data = $this->repository->jobToData($job);
                $this->repository->sendSMSNotificationToTranslator($job);

                return response()->json(['success' => 'SMS sent'], 200);
            } else {
                return response()->json(['error' => 'Job not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
