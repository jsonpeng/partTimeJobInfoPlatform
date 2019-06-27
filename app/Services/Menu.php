<?php

namespace App\Services;

//use Overtrue\Wechat\MenuItem;
use EasyWeChat\Foundation\Application;
use Illuminate\Support\Facades\Config;

use App\Repositories\WechatMenuRepository;
use App\Repositories\WechatMaterialRepository;
use App\Services\Material as MaterialService;
use App\Services\Event as EventService;
use App\Repositories\WechatEventRepository;
use Illuminate\Support\Facades\Log;

use EasyWeChat\Factory;

/**
 * 菜单服务提供类.
 *
 * @author rongyouyuan <rongyouyuan@163.com>
 */
class Menu
{

    /**
     * menuRepository.
     *
     * @var App\Repositories\menuRepository
     */
    private $menuRepository;
    private $materialService;
    private $eventService;
    private $eventRepository;

    public function __construct(WechatMenuRepository $menuRepository, MaterialService $materialService, EventService $eventService, WechatEventRepository $eventRepository)
    {
        $this->menuRepository = $menuRepository;
        $this->materialService = $materialService;
        $this->eventService = $eventService;
        $this->eventRepository = $eventRepository;
    }

    /**
     * 取得远程公众号的菜单.
     *
     * @param App\Models\
     *
     * @return array 菜单信息
     */
    private function getFromRemote()
    {
        //return with(new WechatMenu($account->app_id, $account->app_secret))->current();
        $menuService = app('wechat.official_account')->menu;
        return $menuService->current();

    }

    /**
     * 同步远程菜单到本地数据库.
     *
     * @param App\Models\ 公众号
     *
     * @return Response
     */
    public function syncToLocal()
    {

        $remoteMenus = $this->getFromRemote();

        $menus = $this->makeLocalize($remoteMenus);

        $result =  $this->saveToLocal($menus);
        //同步本地菜单到服务器
        $this->syncToRemote();

        return $result;
    }

    /**
     * 将远程菜单进行本地化.
     *
     * @param array $menus 菜单
     *
     * @return array 处理后的菜单
     */
    private function makeLocalize($menus)
    {
        $menus = $menus['selfmenu_info']['button'];
        if (empty($menus)) {
            return [];
        }

        return $this->filterEmptyMenu(array_map([$this, 'analyseRemoteMenu'], $menus));
    }

    /**
     * 过滤掉菜单中空的内容.
     *
     * @param array $menus 菜单
     *
     * @return array
     */
    private function filterEmptyMenu($menus)
    {
        foreach ($menus as $key => $menu) {
            if (false == $menu) {
                unset($menus[$key]);
            }

            if (isset($menu['sub_button'])) {
                $menus[$key]['sub_button'] = array_filter($menu['sub_button']);
            }
        }

        return $menus;
    }

    /**
     * 分析远程取得的菜单数据.
     *
     * @param array $menu 菜单
     *
     * @return array|NULL
     */
    //2017-05-04 private function analyseRemoteMenu($menu)
    private function analyseRemoteMenu($menu)
    {

        if (isset($menu['sub_button']['list'])) {
            $menu['sub_button'] = array_map([$this, 'analyseRemoteMenu'], $menu['sub_button']['list']);
        } else {
            Log::info('analyseRemoteMenu');
            Log::info(camel_case('resolve_'.$menu['type'].'_menu'));
            $menu = call_user_func([$this, camel_case('resolve_'.$menu['type'].'_menu')], $menu);
        }

        return $menu;
    }

    /**
     * 保存解析后台的菜单到本地.
     *
     * @param array $menus 菜单
     *
     * @return array
     */
    private function saveToLocal($menus)
    {
        return $this->menuRepository->storeMulti($menus);
    }

    /**
     * 解析文字类型的菜单 [转换为事件].
     *
     * @param App\Models\
     * @param array                   $menu
     *
     * @return array
     */
    //private function resolveTextMenu(, $menu)
    private function resolveTextMenu($menu)
    {

        $menu['type'] = 'click';

        //$account = account()->getCurrent();

        $mediaId = $this->materialService->saveText($menu['value']);

        //$menu['key'] = $this->eventService->makeMediaId($mediaId);
        $eventKey = $this->eventRepository->storeTextEvent($mediaId);
        $menu['key'] = $eventKey;

        unset($menu['value']);

        return $menu;
    }

    /**
     * 解析MediaId类型的菜单.
     *
     * @param array $menu 菜单
     *
     * @return array
     */
    private function resolveMediaIdMenu($menu)
    {
        return false; //暂时关掉此类型处理 todo
        $menu['type'] = 'click';
        //mediaId类型属于永久素材类型
        $menu['key'] = $this->eventService->makeMediaId();

        unset($menu['value']);

        Log::info('resolveMediaIdMenu mediaId');
        Log::info($menu);

        return $menu;
    }

    /**
     * 解析新闻类型的菜单 [转换为事件/存储图文为素材].
     *
     * @param array $menu 菜单
     *
     * @return array
     */
    private function resolveNewsMenu($menu)
    {
        $menu['type'] = 'click';
        $mediaId = $this->materialService->saveArticle(
            $menu['news_info']['list'],
            null,
            \App\Models\Material::CREATED_FROM_WECHAT,
            \App\Models\Material::CAN_NOT_EDITED //无法编辑
        );

        //$menu['key'] = $this->eventService->makeMediaId($mediaId);
        Log::info('resolveNewsMenu mediaId');
        Log::info($mediaId);
        $eventKey = $this->eventRepository->storeMaterialEvent($mediaId);
        $menu['key'] = $eventKey;
        unset($menu['value']);

        unset($menu['news_info']);

        return $menu;
    }

    /**
     * 解析视频类型的菜单 属于临时素材丢弃.
     *
     * @param array $menu 菜单参数
     *
     * @return false
     */
    private function resolveVideoMenu($menu)
    {
        return false;
    }

    /**
     * 解析声音类型的菜单 属于临时素材丢弃.
     *
     * @param array $menu 菜单参数
     *
     * @return false
     */
    private function resolveVoiceMenu($menu)
    {
        return false;
    }

    /**
     * 解析图片类型的菜单 属于临时素材丢弃.
     *
     * @param array $menu 菜单
     *
     * @return array
     */
    private function resolveImgMenu($menu)
    {
        return false;
    }

    /**
     * 解析地址类型菜单 不用处理.
     *
     * @param array $menu 菜单
     *
     * @return array
     */
    private function resolveViewMenu($menu)
    {
        $menu['key'] = $menu['url'];

        unset($menu['url']);

        return $menu;
    }

    /**
     * 解析点击事件类型的菜单 [自己的保留，否则丢弃].
     *
     * @param array $menu 菜单信息
     *
     * @return array|bool
     */
    private function resolveClickMenu($menu)
    {
        if (!$this->eventService->isOwnEvent($menu['key'])) {
            return false;
        }

        return $menu;
    }

    /**
     * 解析弹出摄像头类型菜单.
     *
     * @param array $menu 菜单
     *
     * @return array
     */
    private function resolvePicSysphotoMenu($menu)
    {
        return $menu;
    }

    /**
     * 解析微信相册类型菜单.
     *
     * @param array $menu 菜单
     *
     * @return array
     */
    private function resolvePicWeixinMenu($menu)
    {
        return $menu;
    }

    /**
     * 解析弹出拍照或者相册发图类型菜单.
     *
     * @param array $menu 菜单
     *
     * @return array
     */
    private function resolvePicPhotoOrAlbumMenu($menu)
    {
        return $menu;
    }

    /**
     * 解析选择地理位置类型菜单.
     *
     * @param array $menu 菜单
     *
     * @return array
     */
    private function resolveLocationSelectMenu($menu)
    {
        return $menu;
    }

    /**
     * 解析扫码推事件类型菜单.
     *
     * @param array $menu 菜单
     *
     * @return array
     */
    private function resolveScancodePushMenu($menu)
    {
        return $menu;
    }

    /**
     * 解析扫码推事件且弹出“消息接收中”提示框类型菜单.
     *
     * @param array $menu $menu
     *
     * @return array
     */
    private function resolveScancodeWaitmsgMenu($menu)
    {
        return $menu;
    }

    /**
     * 解析跳转图文MediaIdUrl类型的菜单[将被转换为View类型].
     *
     * @param array $menu 菜单
     *
     * @return array
     */
    private function resolveViewLimitedMenu($menu)
    {
        return false; //暂时关闭这个功能 

        $menu['type'] = 'view';

        $url = $this->materialService->localizeMaterialId($menu['value']);

        if (!$url) {
            return false;
        }

        $menu['key'] = $url;

        unset($menu['value']);

        return $menu;
    }

    /**
     * 提交菜单到微信
     *
     * @param 
     * @param array        $menus   菜单
     */
    public function saveToRemote($menus)
    {
        Log::info($menus);
        //menu数据是直接按最终格式传送
        $app = app('wechat.official_account');
        $app->menu->delete(); // 全部
        return $app->menu->create($menus);
    }

    /**
     * [parseLocalMenuFormRemote description]
     * @param  [type] $menus [description]
     * @return [type]        [description]
     */
    public function parseLocalMenuForRemote($menus)
    {
        foreach ($menus as $key => $menu) {
            unset($menus[$key]['id']);
            unset($menus[$key]['parent_id']);
            unset($menus[$key]['sort']);
            if ($menus[$key]['type'] == 'view') {
                $menus[$key]['url'] = $menu['key'];
                unset($menus[$key]['key']);
            } 
            if (empty($menus[$key]['sub_button'])) {
                unset($menus[$key]['sub_button']);
            }else{
                foreach ($menus[$key]['sub_button'] as $key2 => $menu) {
                    unset($menus[$key]['sub_button'][$key2]['id']);
                    unset($menus[$key]['sub_button'][$key2]['parent_id']);
                    unset($menus[$key]['sub_button'][$key2]['sort']);
                    if ($menus[$key]['sub_button'][$key2]['type'] == 'view') {
                        $menus[$key]['sub_button'][$key2]['url'] = $menu['key'];
                        unset($menus[$key]['sub_button'][$key2]['key']);
                    } 
                }
            }
        }
        return $menus;
    }

    /**
     * [syncToRemote description]
     * @param  [type] $account [description]
     * @return [type]          [description]
     */
    public function syncToRemote()
    {
        $buttons = $this->parseLocalMenuForRemote($this->menuRepository->listsWeixin()->toArray());
        return $this->saveToRemote( $buttons);
    }

    /**
     * 格式化为微信菜单.
     *
     * @param array $menus 菜单
     */
    //去除 MenuItem 类，创建菜单直接使用数组不再支持 callback 与 MenuItem 类似的繁杂的方式
    //废弃了
    /*
    private function formatToWechat($menus)
    {
        $saveMenus = [];

        foreach ($menus as $menu) {
            if (isset($menu['sub_button'])) {
                $menuItem = new MenuItem($menu['name']);
                $subButtons = [];
                foreach ($menu['sub_button'] as $subMenu) {
                    $subButtons[] = new MenuItem($subMenu['name'], $subMenu['type'], $subMenu['key']);
                }
                $menuItem->buttons($subButtons);
                $saveMenus[] = $menuItem;
            } else {
                $saveMenus[] = new MenuItem($menu['name'], $menu['type'], $menu['key']);
            }
        }

        return $saveMenus;
    }
    */
}
