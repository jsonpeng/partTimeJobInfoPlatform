<?php

namespace App\Http\Controllers\Admin\Wechat;

// use App\Http\Requests\Material\ArticleRequest;
// use App\Http\Requests\Material\VideoRequest;
// use App\Http\Requests\Material\voiceRequest;
use App\Repositories\WechatMaterialRepository;
use App\Repositories\WechatEventRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * 素材管理.
 *
 * @author rongyouyuan <rongyouyuan@163.com>
 */
class MaterialController extends Controller
{
    /**
     * 分页数目.
     *
     * @var int
     */
    private $pageSize = 24;

    /**
     * materialRepository.
     *
     * @var app\Repositories\MaterialRepository
     */
    private $materialRepository;

    private $eventRepository;
    /**
     * construct.
     */
    public function __construct(WechatMaterialRepository $materialRepository, WechatEventRepository $eventRepository)
    {
        $this->materialRepository = $materialRepository;
        $this->eventRepository = $eventRepository;
    }

    /**
     * 取得素材列表.
     */
    public function getIndex(Request $request)
    {
        $type = is_null($request->get('type')) ? 'article' : $request->get('type');
        $pageSize = $request->get('page_size', $this->pageSize);
        $materials = $this->materialRepository->getList($this->account()->id, $type, $pageSize);
        return admin_view('material.index', compact(['type', 'materials']));
    }

    /**
     * 取得素材列表.
     *
     * @param Request $request request
     */
    public function getLists(Request $request)
    {
        $pageSize = $request->get('page_size', $this->pageSize);

        return $this->materialRepository->getList($this->account()->id, $request->get('type'), $pageSize);
    }

    /**
     * 获取素材.
     *
     * @param Request $request request
     *
     * @return Response
     */
    public function getShow(Request $request)
    {
        if ($request->has('media_id')) {
            return $this->materialRepository->getMediaByMediaId($request->media_id);
        } else {
            return $this->materialRepository->getMediaById($request->id);
        }
    }

    /**
     * 统计素材数量.
     *
     * @return array
     */
    public function getSummary()
    {
        return [
            'image' => $this->materialRepository->countImage($this->account()->id),
            'video' => $this->materialRepository->countVoide($this->account()->id),
            'voice' => $this->materialRepository->countVoice($this->account()->id),
            'article' => $this->materialRepository->countArticle($this->account()->id),
        ];
    }

    /**
     * 创建新文章.
     *
     * @param string $value value
     */
    public function getNewArticle($value = '')
    {
        return  admin_view('material.new-article');
    }

    /**
     * 创建新图文.
     *
     * @param ArticleRequest $request request
     */
    public function postNewArticle(ArticleRequest $request)
    {
        return $this->materialRepository->storeArticle($request->get('article'));
    }

    /**
     * 创建声音.
     *
     * @param voiceRequest $request request
     */
    public function postVoice(voiceRequest $request)
    {
        return $this->materialRepository->storeVoice($this->account()->id, $request);
    }

    /**
     * 创建视频.
     *
     * @param VideoRequest $request request
     */
    public function postVideo(VideoRequest $request)
    {
        return $this->materialRepository->storeVideo($this->account()->id, $request);
    }

    /**
     * 通过event key获取material
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public function getByEventKey($key)
    {
        $event = $this->eventRepository->getEventByKey($key);
        return $this->materialRepository->getMaterialByMediaId($event->value);
    }

}
