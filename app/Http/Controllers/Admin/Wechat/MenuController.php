<?php

namespace App\Http\Controllers\Admin\Wechat;

use App\Http\Controllers\Controller;
//use App\Http\Requests\Menu\CreateRequest;

use App\Services\Menu as MenuService;
use App\Services\Material as MaterialService;

use App\Repositories\WechatMenuRepository;
use App\Repositories\WechatEventRepository;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\WechatMenu;

/**
 * 菜单管理.
 *
 * @author rongyouyuan <rongyouyuan@163.com>
 */
class MenuController extends Controller
{
    /**
     * MenuRepository.
     *
     * @var App\Repositories\MenuRepository;
     */
    private $menuRepository;

    private $eventRepository;

    private $materialService;

    private $menuService;

    public function __construct(WechatMenuRepository $menuRepository, WechatEventRepository $eventRepository, MaterialService $materialService, MenuService $menuService)
    {
        $this->menuRepository = $menuRepository;
        $this->eventRepository = $eventRepository;
        $this->materialService = $materialService;
        $this->menuService = $menuService;
    }

    /**
     * 菜单.
     */
    public function getIndex()
    {
        return view('admin.wechat.menu.index');
    }

    /**
     * 获取菜单列表.
     *
     * @return Response
     */
    public function getLists()
    {
        $menus = $this->menuRepository->lists()->toArray();
        //modify by yyjz
        //return $this->menuRepository->withMaterials($menus);
        return $menus;
    }

    /**
     * 自定义后台创建新的menu 
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getCreate(Request $request)
    {
        $input = $request->all();
        //检查菜单数目限制，一级三个，二级五个
        if ( $input['parent_id'] == 0) {
            if (WechatMenu::where('parent_id', 0)->count() > 2) {
                return "一级菜单最多只能设置三个";
            }
        } else {
            $parent_children_num =  $this->menuRepository->get($input['parent_id'])->subButtons()->count();
            if ( $parent_children_num > 4 ) {
                return "二级菜单最多只能设置五个";
            }
        }
        
        $result =  $this->menuRepository->create($input);
        if ($result && $result->parent_id != 0) {
            //看看prent_menu是否click
            $parent_menu =  $this->menuRepository->get($result->parent_id);
            if ($parent_menu->type != 'click' || $parent_menu->key != null) {
                $this->menuRepository->update($parent_menu->id, ['type' => 'click', 'key' => null]);
            }
        }
        return $result;
    }

    /**
     * 删除制定id的菜单项
     * @param  [int] $id [菜单id]
     * @return [type]     [description]
     */
    public function getDelete($id)
    {
        return $this->menuRepository->delete($id);
    }

    /**
     * 更新菜单
     * @param  Request $request [description]
     * @param  [int]  $id      [菜单id]
     * @return [type]           [description]
     */
    public function getUpdate(Request $request, $id)
    {
        return $this->menuRepository->update($id, $request->all());
    }

    public function getSingle($id)
    {
        return $this->menuRepository->get($id);
    }

    /**
     * 保存菜单.
     *
     * @param CreateRequest $request request
     */
    public function postStore(Request $request)
    {
        $this->menuRepository->destroyMenu();
        $menus = $this->menuRepository->parseMenus($request->get('menus'));
        Log::info(array_values($menus));
        $this->menuRepository->storeMulti($menus);
        $menuService = new MenuService();
        $result = $menuService->saveToRemote( array_values($menus) );

        Log::info('begin....................');
        Log::info($result);

        return response()->json(['status' => true]);
    }
    
   
    /**
     * 更新菜单点击事件的响应信息
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getUpdateMenuEvent(Request $request){
        
        //获取原来的menu
        $menu =  $this->menuRepository->get($request['menu_id']);
        //修改后菜单响应view事件
        if ($request['type'] == 'view') {
            if ($menu->type == 'view') {
                //原来的类型也是view，只需修改key值即可
                //$menu->update(['key' => $request['view_url']]);
                $this->menuRepository->update($request['menu_id'], ['type' => 'view', 'key' => $request['view_url']]);
            } else {
                //以前是event事件，则先删除原来的事件，再更新
                $this->eventRepository->distoryByEventKey($menu->key);
                //$menu->update(['type' => 'view', 'key' => $request['view_url']]);
                $this->menuRepository->update($request['menu_id'], ['type' => 'view', 'key' => $request['view_url']]);
            }
        } else {
            //click
            if ($menu->type == 'view') {
                //以前是view，则需新建event
                if ($request['type'] == 'text') {
                    $mediaId = $this->materialService->saveText($request['content']);
                    $event_key = $this->eventRepository->storeTextEvent($mediaId);
                    $this->menuRepository->update($request['menu_id'], ['type' => 'click', 'key' => $event_key]);
                } else {
                    $event_key = $this->eventRepository->storeMaterialEvent($request['media_id']);
                    $this->menuRepository->update($request['menu_id'], ['type' => 'click', 'key' => $event_key]);
                }
                
            } else {
                //以前就是click，更新就好
                if ($request['type'] == 'text') {
                    $mediaId = $this->materialService->saveText($request['content']);
                    $this->eventRepository->updateTextEvent($menu->key, $mediaId);
                } else {
                    $this->eventRepository->updateMaterialEvent($menu->key, $request['media_id']);
                }
            }
        }
        //同步到微信服务器
        $this->menuService->syncToRemote();

        return $menu;
    }

}
