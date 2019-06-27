<?php

namespace App\Services;

use App\Services\Event as EventService;
use App\Repositories\WechatReplyRepository;
use App\Repositories\WechatMaterialRepository;
use Cache;

/**
 * 回复服务.
 *
 * @author rongyouyuan <rongyouyuan@163.com>
 */
class Reply
{
    /**
     * eventService.
     *
     * @var EventService
     */
    private $eventService;

    /**
     * replyRepository.
     *
     * @var App\Repositories\ReplyRepository
     */
    private $replyRepository;

    private $materialRepository;

    public function __construct( EventService $eventService, WechatReplyRepository $replyRepository, WechatMaterialRepository $materialRepository )
    {
        $this->eventService = $eventService;

        $this->replyRepository = $replyRepository;

        $this->materialRepository = $materialRepository;
    }

    /**
     * 解析一个事件回复.
     *
     * @param App\Models\Reply $reply reply
     *
     * @return array
     */
    public function resolveReply($reply)
    {

        $mids = $reply['content'];
        if ( !is_array($mids) ) {
            return [];
        }

        $materialRepository = $this->materialRepository;
        $reply['content'] = array_map(function ($materialId) use ($materialRepository) {
            return $materialRepository->getMaterialByMediaId($materialId);
        }, $mids);
        
        return $reply;
    }

    /**
     * 解析多个事件回复.
     *
     * @param array $replies replies
     *
     * @return array
     */
    public function resolveReplies($replies)
    {
        $replies = $replies->toArray();
        return array_map(function ($reply) {
            return $this->resolveReply($reply);
        }, $replies);
    }

    public function resolveReplyContent($replies)
    {
        return $multiplied = $replies->map(function ($item, $key) {
            return $this->resolveReply($item);
        });
    }

    /**
     * 重建回复缓存.
     *
     * @param int $accountId 公众号ID
     */
    public function rebuildReplyCache($accountId)
    {
        $replies = $this->replyRepository->all($accountId);

        if (empty($replies)) {
            Cache::forget('replies_'.$accountId);
        }

        $caches = [];

        foreach ($replies as $reply) {
            $keywords = explode(',',$reply['trigger_keywords']); 
            foreach ($keywords as $keyword) {
                $caches[$keyword]['type'] = $reply['trigger_type'];
                $caches[$keyword]['content'] = $reply['content'];
            }
        }

        Cache::forever('replies_'.$accountId, $caches);
    }
}
