<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRefundLogRequest;
use App\Http\Requests\UpdateRefundLogRequest;
use App\Repositories\RefundLogRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class RefundLogController extends AppBaseController
{
    /** @var  RefundLogRepository */
    private $refundLogRepository;

    public function __construct(RefundLogRepository $refundLogRepo)
    {
        $this->refundLogRepository = $refundLogRepo;
    }

    /**
     * Display a listing of the RefundLog.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->refundLogRepository->pushCriteria(new RequestCriteria($request));
        $refundLogs = $this->refundLogRepository->all();

        return view('refund_logs.index')
            ->with('refundLogs', $refundLogs);
    }

    /**
     * Show the form for creating a new RefundLog.
     *
     * @return Response
     */
    public function create()
    {
        return view('refund_logs.create');
    }

    /**
     * Store a newly created RefundLog in storage.
     *
     * @param CreateRefundLogRequest $request
     *
     * @return Response
     */
    public function store(CreateRefundLogRequest $request)
    {
        $input = $request->all();

        $refundLog = $this->refundLogRepository->create($input);

        Flash::success('Refund Log saved successfully.');

        return redirect(route('refundLogs.index'));
    }

    /**
     * Display the specified RefundLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $refundLog = $this->refundLogRepository->findWithoutFail($id);

        if (empty($refundLog)) {
            Flash::error('Refund Log not found');

            return redirect(route('refundLogs.index'));
        }

        return view('refund_logs.show')->with('refundLog', $refundLog);
    }

    /**
     * Show the form for editing the specified RefundLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $refundLog = $this->refundLogRepository->findWithoutFail($id);

        if (empty($refundLog)) {
            Flash::error('Refund Log not found');

            return redirect(route('refundLogs.index'));
        }

        return view('refund_logs.edit')->with('refundLog', $refundLog);
    }

    /**
     * Update the specified RefundLog in storage.
     *
     * @param  int              $id
     * @param UpdateRefundLogRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRefundLogRequest $request)
    {
        $refundLog = $this->refundLogRepository->findWithoutFail($id);

        if (empty($refundLog)) {
            Flash::error('Refund Log not found');

            return redirect(route('refundLogs.index'));
        }

        $refundLog = $this->refundLogRepository->update($request->all(), $id);

        Flash::success('Refund Log updated successfully.');

        return redirect(route('refundLogs.index'));
    }

    /**
     * Remove the specified RefundLog from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $refundLog = $this->refundLogRepository->findWithoutFail($id);

        if (empty($refundLog)) {
            Flash::error('Refund Log not found');

            return redirect(route('refundLogs.index'));
        }

        $this->refundLogRepository->delete($id);

        Flash::success('Refund Log deleted successfully.');

        return redirect(route('refundLogs.index'));
    }
}
