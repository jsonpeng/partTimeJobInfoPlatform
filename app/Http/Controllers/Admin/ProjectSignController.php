<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateProjectSignRequest;
use App\Http\Requests\UpdateProjectSignRequest;
use App\Repositories\ProjectSignRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class ProjectSignController extends AppBaseController
{
    /** @var  ProjectSignRepository */
    private $projectSignRepository;

    public function __construct(ProjectSignRepository $projectSignRepo)
    {
        $this->projectSignRepository = $projectSignRepo;
    }

    /**
     * Display a listing of the ProjectSign.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->projectSignRepository->pushCriteria(new RequestCriteria($request));
        $projectSigns = $this->descAndPaginateToShow($this->projectSignRepository);
        return view('admin.project_signs.index')
            ->with('projectSigns', $projectSigns);
    }

    /**
     * Show the form for creating a new ProjectSign.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.project_signs.create');
    }

    /**
     * Store a newly created ProjectSign in storage.
     *
     * @param CreateProjectSignRequest $request
     *
     * @return Response
     */
    public function store(CreateProjectSignRequest $request)
    {
        $input = $request->all();

        $projectSign = $this->projectSignRepository->create($input);

        Flash::success('Project Sign saved successfully.');

        return redirect(route('projectSigns.index'));
    }

    /**
     * Display the specified ProjectSign.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $projectSign = $this->projectSignRepository->findWithoutFail($id);

        if (empty($projectSign)) {
            Flash::error('没有找到该报名详情');

            return redirect(route('projectSigns.index'));
        }

        return view('project_signs.show')->with('projectSign', $projectSign);
    }

    /**
     * Show the form for editing the specified ProjectSign.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $projectSign = $this->projectSignRepository->findWithoutFail($id);

        if (empty($projectSign)) {
            Flash::error('没有找到该报名详情');

            return redirect(route('projectSigns.index'));
        }

        return view('admin.project_signs.edit')->with('projectSign', $projectSign);
    }

    /**
     * Update the specified ProjectSign in storage.
     *
     * @param  int              $id
     * @param UpdateProjectSignRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProjectSignRequest $request)
    {
        $projectSign = $this->projectSignRepository->findWithoutFail($id);

        if (empty($projectSign)) {
            Flash::error('没有找到该报名详情');

            return redirect(route('projectSigns.index'));
        }

        $projectSign = $this->projectSignRepository->update($request->all(), $id);

        Flash::success('更新成功.');

        return redirect(route('projectSigns.index'));
    }

    /**
     * Remove the specified ProjectSign from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $projectSign = $this->projectSignRepository->findWithoutFail($id);

        if (empty($projectSign)) {
            Flash::error('没有找到该报名详情');

            return redirect(route('projectSigns.index'));
        }

        $this->projectSignRepository->delete($id);

        Flash::success('删除成功.');

        return redirect(route('projectSigns.index'));
    }
}
