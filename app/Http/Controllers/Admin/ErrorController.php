<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateCompanyErrorRequest;
use App\Http\Requests\UpdateCompanyErrorRequest;
use App\Repositories\ProjectErrorRepository;
use App\Repositories\CaompanyRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class ErrorController extends AppBaseController
{
    /** @var  ProjectErrorRepository */
    private $ProjectErrorRepository;
    private $caompanyRepository;
    public function __construct(ProjectErrorRepository $projectErrorRepo,CaompanyRepository $caompanyRepo)
    {
        $this->ProjectErrorRepository = $projectErrorRepo;
        $this->caompanyRepository =$caompanyRepo;
    }

    /**
     * Display a listing of the CompanyError.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->ProjectErrorRepository->pushCriteria(new RequestCriteria($request));
        
        $companyErrors=$this->defaultSearchState($this->ProjectErrorRepository->model())->where('send_type','发起');        
     
        $input=$request->all();
        $input =array_filter( $input, function($v, $k) {
            return $v != '';
        }, ARRAY_FILTER_USE_BOTH );
        $tools=$this->varifyTools($input);

        $companys=$this->caompanyRepository->all();

        #纠错原因
        if(array_key_exists('reason',$input)){
             $companyErrors = $companyErrors->where('reason','like','%'.$input['reason'].'%');
        }

        #企业
        if(array_key_exists('company_id',$input)){
            if(!empty($input['company_id'])){
                $companyErrors = $companyErrors->where('company_id', $input['company_id']);
            }
        }

        #状态
        if(array_key_exists('status',$input)){
            if($input['status']!='-1'){
             $companyErrors = $companyErrors->where('status', $input['status']);
            }
        }

        $companyErrors = $this->descAndPaginateToShow($companyErrors);
        
        return view('admin.company_errors.index')
            ->with('tools',$tools)
            ->with('input',$input)
            ->with('companys',$companys)
            ->with('companyErrors', $companyErrors);
    }

    /**
     * Show the form for creating a new CompanyError.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.company_errors.create');
    }

    /**
     * Store a newly created CompanyError in storage.
     *
     * @param CreateCompanyErrorRequest $request
     *
     * @return Response
     */
    public function store(CreateCompanyErrorRequest $request)
    {
        $input = $request->all();

        $companyError = $this->ProjectErrorRepository->create($input);

        Flash::success('Company Error saved successfully.');

        return redirect(route('companyErrors.index'));
    }

    /**
     * Display the specified CompanyError.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $companyError = $this->ProjectErrorRepository->findWithoutFail($id);

        if (empty($companyError)) {
            Flash::error('没有找到企业纠错信息');

            return redirect(route('companyErrors.index'));
        }

        return view('admin.company_errors.show')->with('companyError', $companyError);
    }

    /**
     * Show the form for editing the specified CompanyError.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $companyError = $this->ProjectErrorRepository->findWithoutFail($id);

        if (empty($companyError)) {
            Flash::error('没有找到企业纠错信息');

            return redirect(route('companyErrors.index'));
        }

        return view('admin.company_errors.edit')->with('companyError', $companyError);
    }

    /**
     * Update the specified CompanyError in storage.
     *
     * @param  int              $id
     * @param UpdateCompanyErrorRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $companyError = $this->ProjectErrorRepository->findWithoutFail($id);

        if (empty($companyError)) {
            Flash::error('没有找到企业纠错信息');

            return redirect(route('companyErrors.index'));
        }

        $input = $request->all();
        $companyError = $this->ProjectErrorRepository->update($input, $id);
        if($companyError->status == '已通过'){
            $del_credits = empty(getSettingValueByKey('error_del_credits')) ? 0 : getSettingValueByKey('error_del_credits');
               $user = user_by_id($companyError->other_user_id);
               if(!empty($user)){
               $user->update(['credits'=>$user->credits-$del_credits]);
               app('zcjy')->creaditsLogRepo()->create([
                        'user_id'=>$user->id,
                        'num'   => $del_credits,
                        'reason' => $companyError->type,
                        'reason_des' => $companyError->reason,
                        'project_error_id' => $id
                    ]);
               $error= $this->ProjectErrorRepository->model()::where('project_id',$companyError->project_id)->where('user_id',$companyError->other_user_id)->where('send_type','收到')->first();
               if(!empty($error)){
                    $error->update(['status'=>'已通过']);
               } 
            }
        }

        Flash::success('更新成功.');

        return redirect(route('companyErrors.index'));
    }

    /**
     * Remove the specified CompanyError from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $companyError = $this->ProjectErrorRepository->findWithoutFail($id);

        if (empty($companyError)) {
            Flash::error('没有找到企业纠错信息');

            return redirect(route('companyErrors.index'));
        }

        $this->ProjectErrorRepository->delete($id);

        Flash::success('删除成功');

        return redirect(route('companyErrors.index'));
    }
}
