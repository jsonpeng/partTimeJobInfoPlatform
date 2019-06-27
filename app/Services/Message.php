<?php

namespace App\Services;

use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;
use EasyWeChat\Kernel\Messages\Text;
use EasyWeChat\Kernel\Messages\Article;
use EasyWeChat\Kernel\Messages\Voice;
use EasyWeChat\Kernel\Messages\Video;

use App\Repositories\WechatMaterialRepository;
use App\Repositories\WechatEventRepository;
use App\Services\Event as EventService;
//use App\Repositories\MessageRepository;

/**
 * 消息服务提供类.
 *
 * @author rongyouyuan <rongyouyuan@163.com>
 */
class Message
{
    /**
     * eventService.
     *
     * @var App\Services\Event
     */
    private $eventService;

    /**
     * materialService.
     *
     * @var App\Services\Material
     */
    private $materialRepository;

    private $eventRepository;

    /**
     * construct.
     *
     * @param ReplySercice $replyService
     */
    public function __construct(EventService $eventService,
                                 WechatMaterialRepository $materialRepository,
                                 //MessageRepository $messageRepository,
                                 WechatEventRepository $eventRepository
        ) {
        $this->eventService = $eventService;

        $this->materialRepository = $materialRepository;

        //$this->messageRepository = $messageRepository;

        $this->eventRepository = $eventRepository;
    }

    /**
     * 事件解析为消息.
     *
     * @param array $eventIds 事件Ids
     *
     * @return Response
     */
    public function eventsToMessage($eventIds)
    {
        return new Text(['content' => '欢迎试用智琛佳源科技微信公众号管理系统，欢迎访问我们的网站：www.yunlike.cn']);
        //return WechatMessage::make('text')->content('感谢您关注');
    }

    /**
     * 事件解析为消息.
     *
     * @param string $eventKey 事件key
     *
     * @return Response
     */
    public function eventToMessage($eventKey)
    {
        $event = $this->eventRepository->getEventByKey($eventKey);

        if (!isset($event['value'])) {
            return $this->emptyMessage();
        }
        return $this->mediaIdToMessage($event['value']);
    }

    /**
     * mediaId 转为消息.
     *
     * @param string $mediaId mediaId
     *
     * @return Response
     */
    public function mediaIdToMessage($mediaId)
    {
        $media = $this->materialRepository->getMaterialByMediaId($mediaId);

        if (!$media) {
            return $this->emptyMessage();
        }
        $callback = 'reply'.ucfirst($media->type);

        return $this->$callback($media);
    }

    /**
     * 图文转为消息.
     *
     * @param App\Models\Material $media 素材
     *
     * @return Response
     */
    private function replyArticle($media)
    {
        //return '点的是文章';
        $articles = [];

        $news = new News([
            'title'       => $media->title,
            'description' => $media->description,
            'url'         => $media->source_url,
            'image'       => $media->cover_url,
        ]);
        array_push($articles, $news);
        $children = $this->materialRepository->materialOfParent($media->id);

        foreach ($children as $key => $value) {
            array_push( $articles, new News([
                'title'       => $value->title,
                'description' => $value->description,
                'url'         => $value->source_url,
                'image'       => $value->cover_url,
            ]) );
        }

        return $articles;
    }

    /**
     * 文字转为消息.
     *
     * @param App\Models\Material $media 素材
     *
     * @return Response
     */
    private function replyText($media)
    {
        return new Text($media->content);
    }

    /**
     * 回复图片.
     *
     * @param App\Models\Material $media 素材
     *
     * @return Response
     */
    private function replyImage($media)
    {
        return new Image($media->original_id);
    }

    /**
     * 回复声音.
     *
     * @param App\Models\Material $voice 素材
     *
     * @return Response
     *
     * @todo  不能使用老版本的sdk
     */
    private function replyVoice($media)
    {
        return new Voice($media->original_id);
    }

    /**
     * 回复视频.
     *
     * @param App\Models\Material $video 素材
     *
     * @return Response
     */
    private function replyVideo($video)
    {
        return new Video($video->original_id, [
            'title' => $video->title,
            'description' => $video->description,
        ]);
    }

    /**
     * 存储消息.
     *
     * @param array $account 公众号
     * @param array $message 消息
    
    public function storeMessage($account, $message)
    {
        $accountId = $account->id;

        return $this->messageRepository->storeMessage($accountId, $message);
    }
     */
    /**
     * 返回一条空消息.
     *
     * @return Response
     */
    public function emptyMessage()
    {
        return new Text('无法匹配请求的内容');
    }
}
