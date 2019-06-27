<?php

namespace App\Http\Controllers\Admin\Wechat;

// use App\Http\Requests\Reply\CreateRequest;
// use App\Http\Requests\Reply\UpdateRequest;
// use App\Http\Requests\Reply\EventRequest;
use App\Services\Reply as ReplyService;
use App\Repositories\WechatReplyRepository;
use App\Repositories\WechatMaterialRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cache;

/**
 * 自动回复管理.
 *
 * @author rongyouyuan <rongyouyuan@163.com>
 */
class ReplyController extends Controller
{
    /**
     * 默认分页数量.
     *
     * @var int
     */
    private $pageSize = 1;

    /**
     * replyRepository.
     *
     * @var App\Repositories\ReplyRepository
     */
    private $replyRepository;

    /**
     * replyService.
     *
     * @var replyService
     */
    private $replyService;

    private $materialRepository;

    /**
     * construct.
     *
     * @param ReplyRepository $autoReply
     */
    public function __construct(WechatReplyRepository $replyRepository, ReplyService $replyService, WechatMaterialRepository $materialRepository)
    {
        $this->replyService = $replyService;

        $this->replyRepository = $replyRepository;

        $this->materialRepository = $materialRepository;
    }

    /**
     * 获取自动回复.
     */
    public function getIndex()
    {
        $rawreplies = $this->replyRepository->getList($this->pageSize);

        $replies = $this->replyService->resolveReplyContent($rawreplies);

        $replies->transform(function ($item, $key) {
            $newArray = $item->content;
            if (!$newArray) {
                return $item;
            }
            for ($i=0; $i < sizeof($newArray); $i++) { 
                switch ($newArray[$i]['type']) {
                    case 'article':
                        $newArray[$i]['display_type'] = '图文';
                        $newArray[$i]['display_name'] = $newArray[$i]['title'];
                    break;
                    case 'text':
                        $newArray[$i]['display_type'] = '文字回复';
                        $newArray[$i]['display_name'] = '';
                    break;
                    case 'image':
                        $newArray[$i]['display_type'] = '图片';
                        $newArray[$i]['display_name'] = $newArray[$i]['title'];
                    break;
                    case 'voice':
                        $newArray[$i]['display_type'] = '声音';
                        $newArray[$i]['display_name'] = $newArray[$i]['title'];
                    break;
                    case 'video':
                        $newArray[$i]['display_type'] = '视频';
                        $newArray[$i]['display_name'] = $newArray[$i]['title'];
                    break;
                    default:
                    break;
                }
            }
            $item->content = $newArray;
            return $item;
        });
        return view('admin.wechat.reply.index', compact('replies'));        
    }

    public function getRplFollow()
    {
        return view('admin.wechat.reply.fellow');
    }

    public function getRplNoMatch()
    {
        return view('admin.wechat.reply.nomatch');
    }

    /**
     * 获取无匹配回复的值.
     */
    public function getFollowReply()
    {
        $reply = $this->replyRepository->getFollowReply();
        if ($reply) {
            return $this->replyService->resolveReply($reply);
        }else{
            return 0;
        }
    }

    /**
     * 获取无匹配时的自动回复.
     */
    public function getNoMatchReply()
    {
        $reply = $this->replyRepository->getNoMatchReply();
        if ($reply) {
            return $this->replyService->resolveReply($reply);
        }else{
            return 0;
        }
    }

    /**
     * 取得自动回复的列表.
     *
     * @param Request $request request
     */
    public function getLists(Request $request)
    {
        $replies = $this->replyRepository->getList( $this->pageSize);

        return $this->replyService->resolveReplies($replies);
    }

    public function getSingle($id)
    {
        $replies = $this->replyRepository->get($id);

        return $this->replyService->resolveReply($replies->toArray());
    }

    /**
     * 新增与保存事件自动回复[ 关注与无匹配 ].
     *
     * @param EventRequest $request request
     *
     * @return array
     */
    public function getSaveEventReply(Request $request)
    {
        $reply = $this->replyRepository->saveEventReply($request);
        return $reply;
        //return $this->replyService->resolveReply($reply);
    }

    /**
     * 保存自动回复内容
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function postStore(Request $request)
    {
        $reply = $this->replyRepository->saveAutoReply($request);
        return $reply;
       
    }


    public function getEdit($id)
    {
        return view('admin.wechat.reply.edit', compact('id'));
    }
    /**
     * 更改自动回复内容.
     *
     * @param UpdateRequest $request request
     * @param int           $id      id
     *
     * @return array
     */
    public function postUpdate(Request $request, $id)
    {
        $reply = $this->replyRepository->update($id, $request);

        return $this->replyService->resolveReply($reply);
    }

    public function getUpdate(Request $request, $id)
    {
        return 'ok';
    }

    public function getDelete($id)
    {

        $reply = $this->replyRepository->get($id);
        $ids = $reply->content;
        foreach ($ids as $key => $value) {
            $material = $this->materialRepository->getMaterialByMediaId($value);
            if ($material->type == 'text') {
                $this->materialRepository->delete($material->id);
            }
        }
        if ($this->replyRepository->delete($id)) {
             return 'success';
        } else{
            return 'failure';
        }
    }

    public function getDeleteEvent($type)
    {
        if ($type == 'follow') {
            return $this->replyRepository->deleteFollowReply();
        } else if($type == 'no-match'){
            return $this->replyRepository->deleteNoMatchReply();
        }
    }
}
