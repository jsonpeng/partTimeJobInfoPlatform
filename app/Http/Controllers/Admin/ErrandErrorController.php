<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateErrandErrorRequest;
use App\Http\Requests\UpdateErrandErrorRequest;
use App\Repositories\ErrandErrorRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class ErrandErrorController extends AppBaseController
{
    /** @var  ErrandErrorRepository */
    private $errandErrorRepository;

    public function __construct(ErrandErrorRepository $errandErrorRepo)
    {
        $this->errandErrorRepository = $errandErrorRepo;
    }

    /**
     * Display a listing of the ErrandError.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->errandErrorRepository->pushCriteria(new RequestCriteria($request));
     
        $errandErrors=$this->defaultSearchState($this->errandErrorRepository->model())->where('send_type','发起');        
     
        $input=$request->all();
        $input =array_filter( $input, function($v, $k) {
            return $v != '';
        }, ARRAY_FILTER_USE_BOTH );

        $tools=$this->varifyTools($input);

        $errandErrors = $this->descAndPaginateToShow($errandErrors);
        
        return view('admin.errand_errors.index')
            ->with('errandErrors', $errandErrors)
            ->with('tools',$tools)
            ->with('input',$input);
    }

    /**
     * Show the form for creating a new ErrandError.
     *
     * @return Response
     */
    public function create()
    {
        return view('errand_errors.create');
    }

    /**
     * Store a newly created ErrandError in storage.
     *
     * @param CreateErrandErrorRequest $request
     *
     * @return Response
     */
    public function store(CreateErrandErrorRequest $request)
    {
        $input = $request->all();

        $errandError = $this->errandErrorRepository->create($input);

        Flash::success('Errand Error saved successfully.');

        return redirect(route('errandErrors.index'));
    }

    /**
     * Display the specified ErrandError.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $errandError = $this->errandErrorRepository->findWithoutFail($id);

        if (empty($errandError)) {
            Flash::error('没有找到该投诉');

            return redirect(route('errandErrors.index'));
        }

        return view('errand_errors.show')->with('errandError', $errandError);
    }

    /**
     * Show the form for editing the specified ErrandError.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $errandError = $this->errandErrorRepository->findWithoutFail($id);

        if (empty($errandError)) {
            Flash::error('没有找到该投诉');

            return redirect(route('errandErrors.index'));
        }

        return view('errand_errors.edit')->with('errandError', $errandError);
    }

    /**
     * Update the specified ErrandError in storage.
     *
     * @param  int              $id
     * @param UpdateErrandErrorRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateErrandErrorRequest $request)
    {
        $errandError = $this->errandErrorRepository->findWithoutFail($id);

        if (empty($errandError)) {
            Flash::error('没有找到该投诉');

            return redirect(route('errandErrors.index'));
        }

        $errandError = $this->errandErrorRepository->update($request->all(), $id);

          if($errandError->status == '已通过'){
            $del_credits = empty(getSettingValueByKey('error_del_credits')) ? 0 : getSettingValueByKey('error_del_credits');
               $errorTask = $this->errandErrorRepository->model()::where('task_id',$errandError->task_id)->where('send_type','收到')->first();
               if(!empty($errorTask)){
                   $user = user_by_id($errorTask->user_id);
                   if(!empty($user)){
                        $user->update(['credits'=>$user->credits-$del_credits]);
                        app('zcjy')->creaditsLogRepo()->create([
                                'user_id'=>$user->id,
                                'num'   => $del_credits,
                                'reason' => $errandError->type,
                                'reason_des' => $errandError->reason,
                                'project_error_id' => $id
                            ]);
                        $errorTask->update(['status'=>'已通过']);
                   }
            }
        }
        Flash::success('更新成功.');

        return redirect(route('errandErrors.index'));
    }

    /**
     * Remove the specified ErrandError from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $errandError = $this->errandErrorRepository->findWithoutFail($id);

        if (empty($errandError)) {
            Flash::error('没有找到该投诉');

            return redirect(route('errandErrors.index'));
        }

        $this->errandErrorRepository->delete($id);

        Flash::success('删除成功.');

        return redirect(route('errandErrors.index'));
    }
}
