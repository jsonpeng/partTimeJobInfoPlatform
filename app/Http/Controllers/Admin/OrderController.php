<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Repositories\OrderRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class OrderController extends AppBaseController
{
    /** @var  OrderRepository */
    private $orderRepository;

    public function __construct(OrderRepository $orderRepo)
    {
        $this->orderRepository = $orderRepo;
    }

    /**
     * Display a listing of the Order.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->orderRepository->pushCriteria(new RequestCriteria($request));
        $orders=$this->defaultSearchState($this->orderRepository->model());
        $input=$request->all();
        $input =array_filter( $input, function($v, $k) {
            return $v != '';
        }, ARRAY_FILTER_USE_BOTH );
        $tools=$this->varifyTools($input);

        #订单金额
        if(array_key_exists('price_start',$input)){
            $orders = $orders->where('price', '>=', $input['price_start']);
        }
        if(array_key_exists('price_end',$input)){
            $orders = $orders->where('price', '<=', $input['price_end']);
        }

        #订单类型
        if(array_key_exists('order_type',$input)){
            $orders = $orders->where('type', $input['order_type']);
        }

        #支付状态
        if(array_key_exists('order_pay',$input)){
            $orders = $orders->where('order_pay', $input['order_pay']);
        }

        #下单用户昵称 nickname
         if(array_key_exists('nickname',$input)){
            if(!empty($input['nickname'])){
            $user_id_arr=app('user')->getUserArrByNickName($input['nickname']);
      
            if(count($user_id_arr)){
                $orders = $orders->whereIn('user_id', $user_id_arr);
            }
          }
        }

        $orders = $this->descAndPaginateToShow($orders);

        return view('admin.orders.index')
            ->with('tools',$tools)
            ->with('input',$input)
            ->with('orders', $orders);
    }

    /**
     * Show the form for creating a new Order.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.orders.create');
    }

    /**
     * Store a newly created Order in storage.
     *
     * @param CreateOrderRequest $request
     *
     * @return Response
     */
    public function store(CreateOrderRequest $request)
    {
        $input = $request->all();

        $order = $this->orderRepository->create($input);

        Flash::success('Order saved successfully.');

        return redirect(route('orders.index'));
    }

    /**
     * Display the specified Order.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $order = $this->orderRepository->findWithoutFail($id);

        if (empty($order)) {
            Flash::error('没有找到该订单');

            return redirect(route('orders.index'));
        }

        return view('admin.orders.show')->with('order', $order);
    }

    /**
     * Show the form for editing the specified Order.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $order = $this->orderRepository->findWithoutFail($id);

        if (empty($order)) {
            Flash::error('没有找到该订单');

            return redirect(route('orders.index'));
        }

        return view('admin.orders.edit')->with('order', $order);
    }

    /**
     * Update the specified Order in storage.
     *
     * @param  int              $id
     * @param UpdateOrderRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateOrderRequest $request)
    {
        $order = $this->orderRepository->findWithoutFail($id);

        if (empty($order)) {
            Flash::error('没有找到该订单');

            return redirect(route('orders.index'));
        }

        $order = $this->orderRepository->update($request->all(), $id);

        Flash::success('Order updated successfully.');

        return redirect(route('orders.index'));
    }

    /**
     * Remove the specified Order from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $order = $this->orderRepository->findWithoutFail($id);

        if (empty($order)) {
            Flash::error('没有找到该订单');

            return redirect(route('orders.index'));
        }

        $this->orderRepository->delete($id);

        Flash::success('订单删除成功.');

        return redirect(route('orders.index'));
    }
}
