<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateTaskTemRequest;
use App\Http\Requests\UpdateTaskTemRequest;
use App\Repositories\TaskTemRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class TaskTemController extends AppBaseController
{
    /** @var  TaskTemRepository */
    private $taskTemRepository;

    public function __construct(TaskTemRepository $taskTemRepo)
    {
        $this->taskTemRepository = $taskTemRepo;
    }

    /**
     * Display a listing of the TaskTem.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->taskTemRepository->pushCriteria(new RequestCriteria($request));
        $taskTems = $this->descAndPaginateToShow($this->taskTemRepository);
        return view('admin.task_tems.index')
            ->with('taskTems', $taskTems);
    }

    /**
     * Show the form for creating a new TaskTem.
     *
     * @return Response
     */
    public function create()
    {
        $model_required = modelRequiredParam($this->taskTemRepository->model()); 
        return view('admin.task_tems.create')
            ->with('model_required',$model_required);
    }



    /**
     * Store a newly created TaskTem in storage.
     *
     * @param CreateTaskTemRequest $request
     *
     * @return Response
     */
    public function store(CreateTaskTemRequest $request)
    {
        $input = $request->all();

        $status = $this->varifyInputRule($input);

        if($status){
            return redirect(route('taskTems.create'))
                    ->withErrors($status)
                    ->withInput($input);
        }

        $taskTem = $this->taskTemRepository->create($input);

        Flash::success('添加成功.');

        return redirect(route('taskTems.index'));
    }

    private function varifyInputRule($input){
        $status = false;
        if(array_key_exists('content',$input) && array_key_exists('tag',$input)){
            if(strpos($input['content'],'__') === false){
                $status = '任务描述输入格式错误,必须包含匹配'.$input['tag'];
            }
        }
        return $status;
    }

    /**
     * Display the specified TaskTem.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $taskTem = $this->taskTemRepository->findWithoutFail($id);

        if (empty($taskTem)) {
            Flash::error('没有找到该任务模板');

            return redirect(route('taskTems.index'));
        }

        return view('admin.task_tems.show')->with('taskTem', $taskTem);
    }

    /**
     * Show the form for editing the specified TaskTem.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $taskTem = $this->taskTemRepository->findWithoutFail($id);

        if (empty($taskTem)) {
            Flash::error('没有找到该任务模板');

            return redirect(route('taskTems.index'));
        }
        $model_required = modelRequiredParam($this->taskTemRepository->model()); 
        return view('admin.task_tems.edit')
        ->with('taskTem', $taskTem)
        ->with('model_required',$model_required);
    }

    /**
     * Update the specified TaskTem in storage.
     *
     * @param  int              $id
     * @param UpdateTaskTemRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTaskTemRequest $request)
    {
        $taskTem = $this->taskTemRepository->findWithoutFail($id);

        if (empty($taskTem)) {
            Flash::error('没有找到该任务模板');

            return redirect(route('taskTems.index'));
        }

        $input = $request->all();
        $status = $this->varifyInputRule($input);

        if($status){
            return redirect(route('taskTems.edit',$id))
                    ->withErrors($status)
                    ->withInput($input);
        }

        $taskTem = $this->taskTemRepository->update($input, $id);

        Flash::success('更新成功..');

        return redirect(route('taskTems.index'));
    }

    /**
     * Remove the specified TaskTem from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $taskTem = $this->taskTemRepository->findWithoutFail($id);

        if (empty($taskTem)) {
            Flash::error('没有找到该任务模板');

            return redirect(route('taskTems.index'));
        }

        $this->taskTemRepository->delete($id);

        Flash::success('删除成功.');

        return redirect(route('taskTems.index'));
    }
}
