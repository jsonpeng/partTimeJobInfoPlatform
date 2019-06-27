<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateIndustryRequest;
use App\Http\Requests\UpdateIndustryRequest;
use App\Repositories\IndustryRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class IndustryController extends AppBaseController
{
    /** @var  IndustryRepository */
    private $industryRepository;

    public function __construct(IndustryRepository $industryRepo)
    {
        $this->industryRepository = $industryRepo;
    }

    /**
     * Display a listing of the Industry.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->industryRepository->pushCriteria(new RequestCriteria($request));
        $industries = $this->descAndPaginateToShow($this->industryRepository);
        $input=$request->all();
        return view('admin.industries.index')
            ->with('industries', $industries)
            ->with('input',$input);
    }

    /**
     * Show the form for creating a new Industry.
     *
     * @return Response
     */
    public function create()
    {
        $model_required = modelRequiredParam(app('zcjy')->industryRepo()->model()); 
        return view('admin.industries.create')
            ->with('model_required',$model_required);
    }

    /**
     * Store a newly created Industry in storage.
     *
     * @param CreateIndustryRequest $request
     *
     * @return Response
     */
    public function store(CreateIndustryRequest $request)
    {
        $input = $request->all();

        $industry = $this->industryRepository->create($input);

        Flash::success('创建成功.');

        return redirect(route('industries.index'));
    }

    /**
     * Display the specified Industry.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $industry = $this->industryRepository->findWithoutFail($id);

        if (empty($industry)) {
            Flash::error('Industry not found');

            return redirect(route('industries.index'));
        }

        return view('admin.industries.show')->with('industry', $industry);
    }

    /**
     * Show the form for editing the specified Industry.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $industry = $this->industryRepository->findWithoutFail($id);

        if (empty($industry)) {
            Flash::error('Industry not found');

            return redirect(route('industries.index'));
        }
        
        $model_required = modelRequiredParam(app('zcjy')->industryRepo()->model()); 
        return view('admin.industries.edit')
        ->with('industry', $industry)
        ->with('model_required',$model_required);
    }

    /**
     * Update the specified Industry in storage.
     *
     * @param  int              $id
     * @param UpdateIndustryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateIndustryRequest $request)
    {
        $industry = $this->industryRepository->findWithoutFail($id);

        if (empty($industry)) {
            Flash::error('Industry not found');

            return redirect(route('industries.index'));
        }

        $industry = $this->industryRepository->update($request->all(), $id);

        Flash::success('更新成功.');

        return redirect(route('industries.index'));
    }

    /**
     * Remove the specified Industry from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $industry = $this->industryRepository->findWithoutFail($id);

        if (empty($industry)) {
            Flash::error('Industry not found');

            return redirect(route('industries.index'));
        }

        $this->industryRepository->delete($id);

        Flash::success('删除成功');

        return redirect(route('industries.index'));
    }
}
