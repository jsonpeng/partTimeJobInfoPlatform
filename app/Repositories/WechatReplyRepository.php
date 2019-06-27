<?php

namespace App\Repositories;

use App\Models\WechatReply;

/**
 * Reply Repository.
 */
class WechatReplyRepository
{
    use WechatBaseRepository;

    /**
     * model.
     *
     * @var App\Models\Reply
     */
    private $model;

    /**
     * eventRepository.
     *
     * @var App\Repositories\EventRepository
     */
    private $eventRepository;

    private $materialRepository;

    /**
     * construct.
     *
     * @param Reply           $reply           replyModel
     * @param EventRepository $eventRepository eventRepository
     */
    public function __construct(WechatReply $reply, WechatEventRepository $eventRepository, WechatMaterialRepository $materialRepository)
    {
        $this->model = $reply;

        $this->eventRepository = $eventRepository;

        $this->materialRepository = $materialRepository;
    }

    /**
     * 获取关注时的默认回复.
     *
     
     *
     * @return array|mixed
     */
    public function getFollowReply()
    {
        return $this->model->where('type', WechatReply::TYPE_FOLLOW)->first();
    }

    /**
     * 删除关注时的默认回复.
     * @param  [type] $accountId [description]
     * @return [type]            [description]
     */
    public function deleteFollowReply()
    {
        return $this->model->where('type', WechatReply::TYPE_FOLLOW)->delete();
    }

    /**
     * 取得关注时的默认回复.
     *
     
     *
     * @return array|mixed
     */
    public function getNoMatchReply()
    {
        return $this->model->where('type', WechatReply::TYPE_NO_MATCH)->first();
    }

    /**
     * 删除关注时的默认回复.
     */
    public function deleteNoMatchReply()
    {
        return $this->model->where('type', WechatReply::TYPE_NO_MATCH)->delete();
    }

    /**
     * 获取自动回复列表.
     *
     
     * @param int $pageSize  分页数目
     *
     * @return array
     */
    public function getList($pageSize)
    {
        return $this->model->where('type', WechatReply::TYPE_KEYWORDS)->get();
    }

    /**
     * 取得所有回复记录.
     *
     
     *
     * @return Response
     */
    public function all()
    {
        return $this->model->where('type', 'keywords')->get()->toArray();
    }

    /**
     * 保存事件自动回复.
     *
     * @param App\Http\Requests\Reply\EventRequest $request   request
     * @param int                                  $accountId accountId
     */
    public function saveEventReply($request)
    {
        $input = $request->all();
        $model = $this->model->where('type', $request->reply_type)
                             ->first();
        //新设置的material_id
        $materialId = $this->saveReplyToMaterial($input);
        $input['content'] = array($materialId);
        $input['type'] = $request->reply_type;

        if (!$model) {
            $model = new $this->model();
        } else {

            //如果原来的素材是文本，则先删除
            foreach ($model->content as $key => $value) {
                $m = $this->materialRepository->getMaterialByMediaId($value);

                if (isset($m->type) && $m->type == 'text') {
                    $this->materialRepository->delete($m->id);
                }
            }
        }
        return $this->savePost($model, $input);
    }

    /**
     * 保存自动回复设置
     * @param  [type] $request   [description]
     * @param  [type] $accountId [description]
     * @return [type]            [description]
     */
    public function saveAutoReply($request)
    {
        
        $reply = new $this->model();

        $input = $request->all();
        if (!isset($input['replies'])) {
            return null;
        }
        $replies = $input['replies'];

        $input['content'] = $this->saveRepliesToMaterial($replies);

        $input['type'] = WechatReply::TYPE_KEYWORDS;

        return WechatReply::create($input);
    }

    public function saveRepliesToMaterial($replies)
    {
        $materialRepository = $this->materialRepository;
        $materialIds = array_map(function ($reply) use ($materialRepository) {
           return $this->saveReplyToMaterial($reply);
        }, $replies);

        return $materialIds;
    }

    public function saveReplyToMaterial($reply)
    {
        if ($reply['type'] == 'text') {
            return $this->materialRepository->storeText($reply['text']);
        } else {
            return $reply['media_id'];
        }
    }

    /**
     * 保存自动回复到事件.
     *
     * @param array $replies   回复内容
     * @param int   $accountId accountId
     *
     * @return array
     */
    private function saveRepliesToEvent($replies)
    {
        $eventRepository = $this->eventRepository;

        $eventId = array_map(function ($reply) use ($eventRepository) {
            if ($reply['type'] == 'text') {
                return $eventRepository->storeText($reply['content']);
            } else {
                return $eventRepository->storeMaterial($reply['content']);
            }
        }, $replies);

        return $eventId;
    }

    /**
     * 新增一个回复到事件.
     *
     * @param string $replyType 回复类型
     * @param string $content   回复内容
     * @param int    $accountId accountId
     *
     * @return string eventId
     */
    private function saveReplyToEvent($replyType, $content)
    {
        if ($replyType == 'text') {
            $eventId = $this->eventRepository->storeText($content);
        } else {
            $eventId = $this->eventRepository->storeMaterial($content);
        }

        return $eventId;
    }

    /**
     * 更新一个自动回复中的事件.
     *
     * @param string $eventKey  eventKey
     * @param string $replyType 回复类型
     * @param string $content   回复内容
     */
    private function updateEvent($eventKey, $replyType, $content)
    {
        $event = $this->eventRepository->getEventByKey($eventKey);

        if ($replyType == 'text') {
            $this->eventRepository->updateToText($eventKey, $content);
        } else {
            $this->eventRepository->updateToMaterial($eventKey, $content);
        }
    }

    /**
     * 更新自动回复.
     *
     * @param int     $id        id
     * @param Request $request   request
     * @param integet $accountId accountId
     *
     * @return Reply
     */
    public function update($id, $request)
    {
        $input = $request->all();
        if (!isset($input['replies'])) {
            return [];
        }
        //先删除text
        $reply = $this->model->find($id);
        $oldMids = $reply->content;
        foreach ($oldMids as $key => $value) {
            $m = $this->materialRepository->getMaterialByMediaId($value);
            if ($m->type == 'text') {
                $m->delete();
            }
        }

        $replies = $input['replies'];

        $input['content'] = $this->saveRepliesToMaterial($replies);

        $input['type'] = WechatReply::TYPE_KEYWORDS;

        return $reply->update($input);

    }

    /**
     * 删除事件.
     *
     * @param array $eventIds 事件ids
     */
    private function distoryReplyEvent($eventIds)
    {
        $eventRepository = $this->eventRepository;

        return array_map(function ($eventId) use ($eventRepository) {

            return $eventRepository->distoryByEventId($eventId);

        }, $eventIds);
    }

    /**
     * 保存.
     *
     * @param App\Models\Reply $reply reply
     * @param array            $input input
     *
     * @return Reply 返回模型
     */
    public function savePost($reply, $input)
    {
        $reply->fill($input);

        $reply->save();

        return $reply;
    }

    public function get($id)
    {
        try {
            return WechatReply::find($id);
        } catch (Exception $e) {
            return '';
        }
        
    }

    public function delete($id)
    {
        return WechatReply::destroy($id);
    }
}
