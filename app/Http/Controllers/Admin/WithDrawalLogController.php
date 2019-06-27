<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateWithDrawalLogRequest;
use App\Http\Requests\UpdateWithDrawalLogRequest;
use App\Repositories\WithDrawalLogRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class WithDrawalLogController extends AppBaseController
{
    /** @var  WithDrawalLogRepository */
    private $withDrawalLogRepository;

    public function __construct(WithDrawalLogRepository $withDrawalLogRepo)
    {
        $this->withDrawalLogRepository = $withDrawalLogRepo;
    }

    /**
     * Display a listing of the WithDrawalLog.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->withDrawalLogRepository->pushCriteria(new RequestCriteria($request));
        $withDrawalLogs = $this->descAndPaginateToShow($this->withDrawalLogRepository);
        return view('admin.with_drawal_logs.index')
            ->with('withDrawalLogs', $withDrawalLogs);
    }

    /**
     * Show the form for creating a new WithDrawalLog.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.with_drawal_logs.create');
    }

    /**
     * Store a newly created WithDrawalLog in storage.
     *
     * @param CreateWithDrawalLogRequest $request
     *
     * @return Response
     */
    public function store(CreateWithDrawalLogRequest $request)
    {
        $input = $request->all();

        $withDrawalLog = $this->withDrawalLogRepository->create($input);

        Flash::success('With Drawal Log saved successfully.');

        return redirect(route('withDrawalLogs.index'));
    }

    /**
     * Display the specified WithDrawalLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $withDrawalLog = $this->withDrawalLogRepository->findWithoutFail($id);

        if (empty($withDrawalLog)) {
            Flash::error('没有找到该提现记录!');

            return redirect(route('withDrawalLogs.index'));
        }

        return view('admin.with_drawal_logs.show')->with('withDrawalLog', $withDrawalLog);
    }

    /**
     * Show the form for editing the specified WithDrawalLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $withDrawalLog = $this->withDrawalLogRepository->findWithoutFail($id);

        if (empty($withDrawalLog)) {
            Flash::error('没有找到该提现记录!');

            return redirect(route('withDrawalLogs.index'));
        }

        return view('admin.with_drawal_logs.edit')->with('withDrawalLog', $withDrawalLog);
    }

    /**
     * Update the specified WithDrawalLog in storage.
     *
     * @param  int              $id
     * @param UpdateWithDrawalLogRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateWithDrawalLogRequest $request)
    {
        $withDrawalLog = $this->withDrawalLogRepository->findWithoutFail($id);

        if (empty($withDrawalLog)) {
            Flash::error('没有找到该提现记录!');

            return redirect(route('withDrawalLogs.index'));
        }

        $withDrawalLog = $this->withDrawalLogRepository->update($request->all(), $id);

        Flash::success('更新成功.');

        return redirect(route('withDrawalLogs.index'));
    }

    /**
     * Remove the specified WithDrawalLog from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $withDrawalLog = $this->withDrawalLogRepository->findWithoutFail($id);

        if (empty($withDrawalLog)) {
            Flash::error('没有找到该提现记录!');

            return redirect(route('withDrawalLogs.index'));
        }

        $this->withDrawalLogRepository->delete($id);

        Flash::success('删除成功.');

        return redirect(route('withDrawalLogs.index'));
    }
}
