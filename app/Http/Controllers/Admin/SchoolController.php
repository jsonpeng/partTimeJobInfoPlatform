<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateSchoolRequest;
use App\Http\Requests\UpdateSchoolRequest;
use App\Repositories\SchoolRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class SchoolController extends AppBaseController
{
    /** @var  SchoolRepository */
    private $schoolRepository;

    public function __construct(SchoolRepository $schoolRepo)
    {
        $this->schoolRepository = $schoolRepo;
    }

    /**
     * Display a listing of the School.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->schoolRepository->pushCriteria(new RequestCriteria($request));
        $input=$request->all();
        $input =array_filter( $input, function($v, $k) {
            return $v != '';
        }, ARRAY_FILTER_USE_BOTH );
        $tools=$this->varifyTools($input);
        $schools=$this->defaultSearchState($this->schoolRepository->model());
        $schools=$this->descAndPaginateToShow($schools);
        return view('admin.schools.index')
            ->with('schools', $schools);
    }

    /**
     * Show the form for creating a new School.
     *
     * @return Response
     */
    public function create()
    {
        $model_required = modelRequiredParam($this->schoolRepository->model()); 
        return view('admin.schools.create')
            ->with('model_required',$model_required);
    }

    /**
     * Store a newly created School in storage.
     *
     * @param CreateSchoolRequest $request
     *
     * @return Response
     */
    public function store(CreateSchoolRequest $request)
    {
        $input = $request->all();

        $school = $this->schoolRepository->create($input);

        Flash::success('添加学校成功.');

        return redirect(route('schools.index'));
    }

    /**
     * Display the specified School.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $school = $this->schoolRepository->findWithoutFail($id);

        if (empty($school)) {
            Flash::error('没有找到该学校!');

            return redirect(route('schools.index'));
        }

        return view('admin.schools.show')->with('school', $school);
    }

    /**
     * Show the form for editing the specified School.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $school = $this->schoolRepository->findWithoutFail($id);

        if (empty($school)) {
            Flash::error('没有找到该学校!');

            return redirect(route('schools.index'));
        }
        $model_required = modelRequiredParam($this->schoolRepository->model()); 
        return view('admin.schools.edit')
        ->with('school', $school)
        ->with('model_required',$model_required);
    }

    /**
     * Update the specified School in storage.
     *
     * @param  int              $id
     * @param UpdateSchoolRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSchoolRequest $request)
    {
        $school = $this->schoolRepository->findWithoutFail($id);

        if (empty($school)) {
            Flash::error('没有找到该学校!');

            return redirect(route('schools.index'));
        }

        $school = $this->schoolRepository->update($request->all(), $id);

        Flash::success('更新学校成功.');

        return redirect(route('schools.index'));
    }

    /**
     * Remove the specified School from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $school = $this->schoolRepository->findWithoutFail($id);

        if (empty($school)) {
            Flash::error('没有找到该学校!');

            return redirect(route('schools.index'));
        }

        $this->schoolRepository->delete($id);

        Flash::success('删除成功.');

        return redirect(route('schools.index'));
    }
}
