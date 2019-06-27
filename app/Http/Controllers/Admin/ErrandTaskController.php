<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateErrandTaskRequest;
use App\Http\Requests\UpdateErrandTaskRequest;
use App\Repositories\ErrandTaskRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class ErrandTaskController extends AppBaseController
{
    /** @var  ErrandTaskRepository */
    private $errandTaskRepository;

    public function __construct(ErrandTaskRepository $errandTaskRepo)
    {
        $this->errandTaskRepository = $errandTaskRepo;
    }

    /**
     * Display a listing of the ErrandTask.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->errandTaskRepository->pushCriteria(new RequestCriteria($request));
        $input=$request->all();
        $input =array_filter( $input, function($v, $k) {
            return $v != '';
        }, ARRAY_FILTER_USE_BOTH );
        $tools=$this->varifyTools($input);
        $errandTasks=$this->defaultSearchState($this->errandTaskRepository->model());
        if(array_key_exists('name',$input)){
            $errandTasks = $errandTasks->where('name', 'like','%'.$input['name'].'%');
        }
        if(array_key_exists('school_name',$input)){
            $errandTasks = $errandTasks->where('school_name', 'like','%'.$input['school_name'].'%');
        }
        if(array_key_exists('status',$input)){
            $errandTasks = $errandTasks->where('status', $input['status']);
        }
        if(array_key_exists('pay_status',$input)){
            $errandTasks = $errandTasks->where('pay_status', $input['pay_status']);
        }

        $errandTasks=$this->descAndPaginateToShow($errandTasks);

        $errandTasks = $this->errandTaskRepository->attachPulisherAndErranderInfo($errandTasks);

        return view('admin.errand_tasks.index')
            ->with('errandTasks', $errandTasks)
            ->with('input',$input)
            ->with('tools',$tools);
    }

    /**
     * Show the form for creating a new ErrandTask.
     *
     * @return Response
     */
    public function create()
    {
        $model_required = modelRequiredParam($this->errandTaskRepository->model()); 
        return view('admin.errand_tasks.create')
                ->with('model_required',$model_required)
                ->with('images',[]);
    }

    /**
     * Store a newly created ErrandTask in storage.
     *
     * @param CreateErrandTaskRequest $request
     *
     * @return Response
     */
    public function store(CreateErrandTaskRequest $request)
    {
        $input = $request->all();

        $errandTask = $this->errandTaskRepository->create($input);
        #添加备注图片
        if(array_key_exists('images',$input)){
            $this->errandTaskRepository->syncImages($input['images'],$errandTask->id);
        }
        Flash::success('添加成功.');

        return redirect(route('errandTasks.index'));
    }

    /**
     * Display the specified ErrandTask.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $errandTask = $this->errandTaskRepository->findWithoutFail($id);

        if (empty($errandTask)) {
            Flash::error('Errand Task not found');

            return redirect(route('errandTasks.index'));
        }

        return view('errand_tasks.show')->with('errandTask', $errandTask);
    }

    /**
     * Show the form for editing the specified ErrandTask.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $errandTask = $this->errandTaskRepository->findWithoutFail($id);

        if (empty($errandTask)) {
            Flash::error('Errand Task not found');

            return redirect(route('errandTasks.index'));
        }

        $model_required = modelRequiredParam($this->errandTaskRepository->model()); 

        $images = $errandTask->images()->get();

        return view('admin.errand_tasks.edit')
        ->with('errandTask', $errandTask)
        ->with('model_required',$model_required)
        ->with('images',$images);
    }

    /**
     * Update the specified ErrandTask in storage.
     *
     * @param  int              $id
     * @param UpdateErrandTaskRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateErrandTaskRequest $request)
    {
        $errandTask = $this->errandTaskRepository->findWithoutFail($id);

        if (empty($errandTask)) {
            Flash::error('Errand Task not found');

            return redirect(route('errandTasks.index'));
        }
        $input = $request->all();
        $errandTask->update($input);
        #添加备注图片
        if(array_key_exists('images',$input)){
            $this->errandTaskRepository->syncImages($input['images'],$errandTask->id,true);
        }
        else{
            $this->errandTaskRepository->clearImages($errandTask->id);
        }
        Flash::success('更新成功.');

        return redirect(route('errandTasks.index'));
    }

    /**
     * Remove the specified ErrandTask from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $errandTask = $this->errandTaskRepository->findWithoutFail($id);

        if (empty($errandTask)) {
            Flash::error('Errand Task not found');

            return redirect(route('errandTasks.index'));
        }

        $this->errandTaskRepository->delete($id);
        
        $this->errandTaskRepository->clearImages($id);

        Flash::success('删除成功.');

        return redirect(route('errandTasks.index'));
    }
}
