<?php

namespace App\Http\Controllers\Admin\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Message as MessageService;
use App\Repositories\WechatReplyRepository;
use App\Repositories\UserRepository;
use App\User;
use Log;

class WechatController extends Controller
{

	/**
     * 消息服务
     *
     * @var App\Services\Message
     */
    private $messageService;

    /**
     * replyRepository.
     *
     * @var App\Repositories\ReplyRepository
     */
    private $replyRepository;

    private $userRepository;

    /**
     * constructer.
     *
     * @param MessageService $messageService 消息服务
     */
    public function __construct(MessageService $messageService, WechatReplyRepository $replyRepo, UserRepository $userRepos)
    {
        $this->messageService = $messageService;

        $this->replyRepository = $replyRepo;
        $this->userRepository = $userRepos;
    }

    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        $app = app('wechat.official_account');
        $app->server->push(function($message) use ($app){
            Log::info('message：');
            Log::info($message);
        	switch ($message['MsgType']) {
		        case 'event':
		            switch ($message['Event']) {
                        case 'subscribe':
                            //介绍人
                            if (!empty($message['EventKey'])) {
                                $pos = stripos($message['EventKey'], '_')+1;

                                Log::info('message:');
                                Log::info($message);

                                $this->userRepository->varifyLeaderById($message['FromUserName'], intval(substr($message['EventKey'], $pos)) );

                            }
                            return $this->handleSubscribe();
                            break;
                        case 'unsubscribe':
                            //return '不要离开我啊。。。';
                            break;
                        case 'CLICK':
                            Log::info($message['EventKey']);
                            return $this->messageService->eventToMessage($message['EventKey']);
                            break;
                        default:
                            # code...
                            break;
                    }
		            break;
		        case 'text':
		            return $this->handleAutoReply($message, $app);
		            break;
		        case 'image':
		            return '收到图片消息';
		            break;
		        case 'voice':
		            return '收到语音消息';
		            break;
		        case 'video':
		            return '收到视频消息';
		            break;
		        case 'location':
		            return '收到坐标消息';
		            break;
		        case 'link':
		            return '收到链接消息';
		            break;
		        // ... 其它消息
		        default:
		            return '收到其它消息';
		            break;
		    }
        });

        return $app->server->serve();
    }


    /**
     * 处理事件.
     *
     * @param int                    $account 公众号
     * @param array                  $event   事件
     * @param Overtrue\Wechat\Server $server  server
     *
     * @return Response
     */
    private function handleEvent($event)
    {
        if ($event['Event'] == 'subscribe') {
            return $this->handleSubscribe();
        }
    }

    /**
     * 处理订阅时的消息.
     *
     * @return Response
     */
    private function handleSubscribe()
    {
        $reply = $this->replyRepository->getFollowReply();
        $materialId = $reply['content'][0];
        return $materialId ? $this->messageService->mediaIdToMessage($materialId) : $this->messageService->emptyMessage();
    }

    /**
     * 处理未匹配时的回复.
     *
     * @return Response
     */
    private function handleNoMatch($app, $openid)
    {
        $event = $this->replyRepository->getNoMatchReply();
        if (empty($event)) {
            return $this->messageService->emptyMessage();
        }

        $mediaId = $event['content'][0];
        if (empty($mediaId)) {
            $app->customer_service->message($this->messageService->emptyMessage())->to($openid)->send();
        } else {
            $app->customer_service->message($this->messageService->mediaIdToMessage($mediaId))->to($openid)->send();
        }
        
        

        //return $eventId ? $this->messageService->eventToMessage($eventId) : $this->messageService->emptyMessage();
    }

    private function handleAutoReply($message, $app)
    {
        //获取用户自定义关键词回复规则
        $result = '';
        $replies = $this->replyRepository->all();
        foreach ($replies as $reply) {
            $keywords = explode(',',$reply['trigger_keywords']); 
            
            if ($reply['trigger_type'] == 'equal') {
                foreach ($keywords as $keyword) {
                    if ($keyword == $message['Content']) {
                        $materialIds = $reply['content'];
                        foreach ($materialIds as $key => $value) {
                            $result = $this->messageService->mediaIdToMessage($value);
                            if ($result) {
                                $app->customer_service->message($result)->to($message['FromUserName'])->send();
                            }
                        }
                    }
                }
            }else{
                foreach ($keywords as $keyword) {
                    if (strpos($keyword, $message['Content']) !== false) {
                        $materialIds = $reply['content'];
                        foreach ($materialIds as $key => $value) {
                            $result = $this->messageService->mediaIdToMessage($value);
                            if ($result) {
                                $app->customer_service->message($result)->to($message['FromUserName'])->send();
                            }
                        }
                    }
                }
            }
            
        }
        if (empty($result)) {
            $this->handleNoMatch($app, $message['FromUserName']);
        }
    }

    /**
     * 处理消息.
     *
     * @param int                    $account 公众号
     * @param array                  $message 消息
     * @param Overtrue\Wechat\Server $server  server
     *
     * @return Response
   
    private function handleMessage($account, $message, $server)
    {
        //存储消息
        $this->messageService->storeMessage($account, $message);
        //属于文字类型消息
        if ($message['MsgType'] == 'text') {
            $replies = (array) Cache::get('replies_'.$account->id);

            if (empty($replies)) {
                return $this->handleNoMatch($account);
            }

            foreach ($replies as $key => $reply) {
                //查找字符串
                if (str_contains($message['Content'], $key)) {
                    return $this->messageService->eventsToMessage($reply['content']);
                }
            }

            return $this->handleNoMatch($account);
        }
    }
      */
}
