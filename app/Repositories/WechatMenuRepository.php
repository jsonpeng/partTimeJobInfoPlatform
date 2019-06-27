<?php

namespace App\Repositories;

use App\Models\WechatMenu;
use Illuminate\Support\Facades\Log;

/**
 * Menu Repository.
 */
class WechatMenuRepository
{
    use WechatBaseRepository;

    /**
     * Account Model.
     *
     * @var Account
     */
    protected $model;

    /**
     * eventRepository
     *
     * @var EventRepository
     */
    protected $eventRepository;

    /**
     * materialRepository
     *
     * @var MaterialRepository
     */
    protected $materialRepository;

    //protected $menuService;

    /**
     * construct
     *
     * @param Menu               $menu               模型
     * @param EventRepository    $eventRepository    事件Repository
     * @param MaterialRepository $materialRepository 素材Repository
     */
    public function __construct(WechatMenu $menu, 
        WechatEventRepository $eventRepository, 
        WechatMaterialRepository $materialRepository)
    {
        $this->model = $menu;

        $this->eventRepository = $eventRepository;

        $this->materialRepository = $materialRepository;

        //$this->menuService = $menuService;
    }

    /**
     * 菜单列表.
     *
     * @return array
     */
    public function lists()
    {
        return $this->model->where('parent_id', 0)->orderBy('id', 'asc')->with('subButtons')->orderBy('id', 'desc')->get();
    }


    /**
     * 返回菜单列表，专为微信远程设置
     * @param  [type] $accountId [description]
     * @return [type]            [description]
     */
    public function listsWeixin()
    {
        return $this->model->where('parent_id', 0)->orderBy('id', 'asc')->with('subButton')->orderBy('id', 'desc')->get();
    }

    /**
     * 取得所有菜单 不带有层级.
     *
     * @return array
     */
    public function all()
    {
        return $this->model->get()->toArray();
    }

    /**
     * 一次存储所有菜单.
     *
     * @param int   $$accountId id
     * @param array $menus      菜单
     */
    public function storeMulti($menus)
    {
        foreach ($menus as $key => $menu) {
            $menu['sort'] = $key;

            $parentId = $this->store($menu)->id;

            if (!empty($menu['sub_button'])) {
                foreach ($menu['sub_button'] as $subKey => $subMenu) {
                    $subMenu['parent_id'] = $parentId;

                    $subMenu['sort'] = $subKey;

                    $this->store($subMenu);
                }
            }
        }
    }



    /**
     * 解析菜单数据.
     *
     * @param int   $accountId 公众号ID
     * @param array $menus     menus
     *
     * @return array
     */
    public function parseMenus($menus)
    {
        $menus = array_map(function ($menu){
            if (isset($menu['sub_button'])) {
                $menu['sub_button'] = $this->parseMenus($menu['sub_button']);
            } else {
                $menu = $this->makeMenuEvent($menu);
            }

            return $menu;

        }, $menus);

        return $menus;
    }

    /**
     * 生成菜单中的事件.
     *
     * @param int   $accountId 公众号Id
     * @param array $menu      menu
     *
     * @return array
     */
    private function makeMenuEvent($menu)
    {
        if ($menu['type'] == 'text') {
            $menu['type'] = 'click';
            $menu['key'] = $this->eventRepository->storeTextEvent($menu['value']);
        } elseif ($menu['type'] == 'media') {
            $menu['type'] = 'click';
            $menu['key'] = $this->eventRepository->storeMaterialEvent($menu['value']);
        } elseif ($menu['type'] == 'view') {
            $menu['url'] = $menu['value'];
            //$menu['key'] = $menu['value'];
        } elseif ($menu['type'] == 'media_id') {
            $menu['media_id'] = $menu['value'];
        } elseif ($menu['type'] == 'view_limited') {
            $menu['media_id'] = $menu['value'];
        } else {
            $menu['key'] = $menu['value'];
        }

        unset($menu['value']);

        return $menu;
    }


    /**
     * 获取菜单中的素材具体信息.
     *
     * @param array $menus 菜单列表
     *
     * @return array
     */
    public function withMaterials($menus)
    {
        return array_map(function ($menu) {
            //该循环有问题，像根节点等根本就没有key值需要进行过滤
            $mediaId = $this->eventRepository->getEventByKey($menu['key'])->value;

            $menu['material'] = $this->materialRepository->getMaterialByMediaId($mediaId);

            return $menu;
        }, $menus);
    }

    
    /**
     * 保存菜单.
     *
     * @param array $input input
     */
    public function store($input)
    {

        if (array_key_exists( 'type', $input ) && $input['type'] == 'view') {
            $input['key'] = $this->addhttp($input['key']);
        }
        return $this->savePost(new $this->model(), $input);
    }

    /**
     * 删除旧菜单
     * @param  int $accountId 公众号id
     * @return [type]            [description]
     */
    public function destroyMenu()
    {
        $menus = $this->all();

        array_map(function ($menu) {

            if ($menu['type'] == 'click') {
                $this->eventRepository->distoryByEventKey($menu['key']);
            }

        }, $menus);

        $this->model->delete();
    }

    /**
     * savePost.
     *
     * @param App\Models\Menu $menu  菜单
     * @param array           $input input
     *
     * @return App\Models\Menu
     */
    public function savePost($menu, $input)
    {
        $menu->fill($input);
        $menu->save();

        return $menu;
    }

    /**
     * 删除菜单
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function delete($id)
    {
        $menu = WechatMenu::find($id);
        //删除相关的事件
        if ($menu && !is_null($menu->key)) {
             $this->eventRepository->distoryByEventKey($menu->key);
        }
        //如果该菜单有父菜单，并且其是父菜单的唯一子菜单, 同时删除父菜单
        if ($menu->parent_id != 0) {
            $menu_parent = WechatMenu::find($menu->parent_id);
            if ($menu_parent->subButtons()->count() <= 1) {
                //解决父菜单'key' => NULL时，invalid button key size hint 错误
                //$menu_parent->update(['type' => 'view', 'key' => 'www.wiswebs.com']);
                if ( WechatMenu::destroy([$id, $menu->parent_id]) ) {
                    return 'success';
                } else {
                    return 'error';
                }
            }
        }
        
        if (WechatMenu::destroy($id)) {
            return 'success';
        } else {
            return 'error';
        }
    }

    /**
     * 更新菜单信息
     * @param  [type] $id     [description]
     * @param  [type] $fields [description]
     * @return [type]         [description]
     */
    public function update($id, $fields)
    {
        $menu = WechatMenu::find($id);
        if (array_key_exists('type', $fields) && $fields['type'] == 'view' ){
            $fields['key'] = $this->addhttp($fields['key']);
        }
        if ($menu->update($fields)) {
            return $menu;
        } else {
            return 'error';
        }
    }

    public function create($input)
    {
        if ($input['type'] == 'view') {
            $input['key'] = $this->addhttp($input['key']);
        }
        return WechatMenu::create($input);
    }

    /**
     * 获取单个菜单
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function get($id)
    {
        return WechatMenu::find($id);
    }

    function addhttp($url) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
        return $url;
    }

}
