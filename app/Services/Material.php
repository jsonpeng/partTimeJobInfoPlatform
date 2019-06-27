<?php

namespace App\Services;

//use Overtrue\Wechat\Media as MediaService;
use EasyWeChat\Foundation\Application;
use Illuminate\Support\Facades\Config;
use App\Repositories\WechatMaterialRepository;
use Illuminate\Support\Facades\Log;

/**
 * 素材服务.
 *
 * @author rongyouyuan <rongyouyuan@163.com>
 */
class Material
{
    /**
     * 拉取素材默认起始位置.
     */
    const MATERIAL_DEFAULT_OFFSET = 0;

    /**
     * 拉取素材的最大数量.
     */
    const MATERIAL_MAX_COUNT = 20;

    /**
     * materialRepository.
     *
     * @var App\Repositories\MaterialRepository
     */
    private $materialRepository;

    /**
     * media.
     *
     * @var Overtrue\Wechat\Media
     */
    private $mediaService;

    public function __construct(WechatMaterialRepository $materialRepository)
    {
        $this->materialRepository = $materialRepository;
    }

    /**
     * 获取application实例
     * @return [EasyWeChat\Foundation\Application] [application实例]
     */
    private function app($app_id, $app_secret){
        $options = Config::get('weixin');
        $options['app_id'] = is_null($app_id) ? account()->getCurrent()->app_id : $app_id;
        $options['secret'] = is_null($app_secret) ? account()->getCurrent()->app_secret : $app_secret;
        return new Application($options);
    }

    /**
     * 保存图文消息.
     *
     * @param array $articles 图文消息
     *
     * @return array
     */
    public function saveArticle(
        $accountId,
        $articles,
        $originalMediaId,
        $createdFrom,
        $canEdited)
    {
        return $this->materialRepository->storeArticle(
            $accountId,
            $articles,
            $originalMediaId,
            $createdFrom,
            $canEdited
        );
    }

    /**
     * 存储一个文字回复消息.
     *
     * @param int    $accountId 公众号ID
     * @param string $text      文字内容
     *
     * @return Response
     */
    public function saveText($text)
    {
        return $this->materialRepository->storeText($text);
    }

    /**
     * 素材转为本地素材.
     *
     * @param string $mediaId     素材id
     * @param string $mediaType   素材类型
     * @param bool   $isTemporary 是否是临时素材
     *
     * @return string 生成的自己的MediaId
     */
    public function localizeMaterialId($mediaId, $mediaType, $isTemporary = true)
    {
        // var_dump($mediaId);
        // die();
    }

    /**
     * 检测素材是否存在.
     *
     * @param string $materialId 素材id
     *
     * @return bool
     */
    public function isExists($materialId)
    {
        return $this->materialRepository->isExists($this->account->id, $materialId);
    }

    /**
     * 生成一个素材mediaId.
     *
     * @return string mediaId
     */
    public function buildMaterialMediaId()
    {
        return 'MEDIA_'.strtoupper(uniqid());
    }

    /**
     * 上传素材到远程.
     *
     * @param App\Model\Material $material 素材模型
     */
    public function postToRemote($material)
    {
        $function = camel_case('post_remote_'.$material->type);

        return $function($material);
    }

    /**
     * 上传视频到远程.
     *
     * @param Material $video 视频素材
     *
     * @return string 微信素材id
     */
    private function postRemoteVideo($video)
    {
        $filePath = $this->mediaUrlToPath($video->source_url);
        /*
        $mediaService = new MediaService(
            account()->getCurrent()->app_id,
            account()->getCurrent()->app_secret
        );

        return $mediaService->forever()->video($filePath, $video->title, $video->description);
        */
        $mediaService = $this->app(null, null)->material;
        return $mediaService->uploadVideo($filePath, $video->title, $video->description);
    }

    /**
     * 上传声音到远程.
     *
     * @param Material $voice 声音素材
     *
     * @return string 微信素材id
     */
    private function postRemoteVoice($voice)
    {
        $filePath = $this->mediaUrlToPath($voice->source_url);
        /*
        $mediaService = new MediaService(
            account()->getCurrent()->app_id,
            account()->getCurrent()->app_secret
        );

        return $mediaService->forever()->voice($filePath);
        */
        $mediaService = $this->app(null, null)->material;
        return $mediaService->uploadVoice($filePath);
    }

    /**
     * 上传图片到远程.
     *
     * @param Material $image 图片素材
     *
     * @return string 微信素材id
     */
    private function postRemoteImage($image)
    {
        $filePath = $this->mediaUrlToPath($image->source_url);
        /*
        $mediaService = new MediaService(
            account()->getCurrent()->app_id,
            account()->getCurrent()->app_secret
        );

        return $mediaService->forever()->image($filePath);
        */
        $mediaService = $this->app(null, null)->material;
        return $mediaService->uploadImage($filePath);
    }

    /**
     * 上传图文素材到远程.
     *
     * @param array $articles 图文素材
     *
     * @return string
     */
    public function postRemoteArticles($articles)
    {
        /*
        $mediaService = new MediaService(
            account()->getCurrent()->app_id,
            account()->getCurrent()->app_secret
        );

        return $mediaService->news($articles);
        */
        $mediaService = $this->app(null, null)->material;
        return $mediaService->uploadArticle($articles);
    }

    /**
     * 同步远程素材到本地.
     *
     * @param Account $account 当前公众号
     * @param string  $type    素材类型
     *
     * @return Response
     */
    public function syncRemoteMaterial($type)
    {
        $countNumber = $this->getRemoteMaterialCount($type);

        for ($offset = self::MATERIAL_DEFAULT_OFFSET;
             $offset < $countNumber;
             $offset += self::MATERIAL_MAX_COUNT
            ) {
            $lists = $this->getRemoteMaterialLists($type, $offset, self::MATERIAL_MAX_COUNT);
            //Log::info('getRemoteMaterialLists');
            //Log::info($lists);
            $this->localizeRemoteMaterialLists($lists, $type);
        }
    }

    /**
     * 远程素材存储本地.
     *
     * @param Account $account 公众号
     * @param array   $lists   素材列表
     * @param string  $type
     *
     * @return Response
     */
    private function localizeRemoteMaterialLists($lists, $type)
    {
        return array_map(function ($list) use ($type) {
            $callFunc = 'storeRemote'.ucfirst($type);

            return $this->$callFunc($list);
        }, $lists);
    }

    /**
     * 存储远程图片素材.
     *
     * @param Account $account 公众号
     * @param array   $image   素材信息
     *
     * @return Response
     */
    private function storeRemoteImage($image)
    {
        $mediaId = $image['media_id'];

        if ($this->getLocalMediaId($mediaId)) {
            return;
        }

        $image['local_url'] = config('app.url').$this->downloadMaterial('image', $mediaId);

        return $this->materialRepository->storeWechatImage($image);
    }

    /**
     * 存储远程声音素材.
     *
     * @param array $voice 声音素材
     *
     * @return Response
     */
    private function storeRemoteVoice($account, $voice)
    {
        $mediaId = $voice['media_id'];

        if ($this->getLocalMediaId($mediaId)) {
            return;
        }


        $voice['local_url'] = config('app.url').$this->downloadMaterial('voice', $mediaId);

        return $this->materialRepository->storeWechatVoice($voice);
    }

    /**
     * 存储远程视频素材.
     *
     * @param array $video 素材信息
     *
     * @return Response
     */
    private function storeRemoteVideo($video)
    {
        $mediaId = $video['media_id'];

        if ($this->getLocalMediaId($mediaId)) {
            return;
        }

        $videoInfo = $this->downloadMaterial('video', $mediaId);

        return $this->materialRepository->storeWechatVideo($videoInfo);
    }

    /**
     * 存储远程图文素材.
     *
     * @param array $news 图文
     *
     * @return Response
     */
    private function storeRemoteNews($news)
    {
        $mediaId = $news['media_id'];

        if ($this->getLocalMediaId($mediaId)) {
            return;
        }
        $news['content']['news_item'] = $this->localizeNewsCoverMaterialId($news['content']['news_item']);

        return $this->materialRepository->storeArticle(
            $news['content']['news_item'],
            $news['media_id']
        );
    }

    /**
     * 将图文消息中的素材转换为本地.
     *
     * @param Account $account   公众号
     * @param array   $newsItems newItem
     *
     * @return array
     */
    private function localizeNewsCoverMaterialId($newsItems)
    {
        $newsItems = array_map(function ($item) {

            
            $item['cover_url'] = $this->mediaIdToSourceUrl($item['thumb_media_id']);

            if ($item['cover_url'] == '') {
                //thumb_media_id 有的返回是错误的
                //$item['cover_url'] = $item['thumb_url'];
                $dateDir = date('Ym').'/';
                //单独把封面图片下载到本地
                //
                $item['cover_url'] = config('app.url').$this->download_image($item['thumb_url'], $fileName = '', $dateDir, $fileType = array('jpg', 'gif', 'png', 'bmp'), $type = 1);

            }

            return $item;

        }, $newsItems);

        return $newsItems;
    }

    /**
     * mediaId转换为本地Url.
     *
     * @param string $mediaId mediaId
     *
     * @return string
     */
    private function mediaIdToSourceUrl($mediaId)
    {
        return $this->materialRepository->mediaIdToSourceUrl($mediaId);
    }

    /**
     * 下载素材到本地.
     *
     * @param Account $account 公众号
     * @param string  $type    素材类型
     * @param string  $mediaId 素材
     *
     * @return mixed
     */
    private function downloadMaterial($account, $type, $mediaId)
    {
        $dateDir = date('Ym').'/';

        $dir = config('material.'.$type.'.storage_path').$dateDir;

        $mediaService = $this->app($account->app_id, $account->app_secret)->material;

        $name = md5($mediaId);

        is_dir($dir) || mkdir($dir, 0755, true);
        //如果属于视频类型
        if ($type == 'video') {
            $videoInfo = $mediaService->get($mediaId);
            //取消下载Mp4文件
            return [
                'title' => $videoInfo['title'],
                'description' => $videoInfo['description'],
                'local_url' => $videoInfo['down_url'],
                'media_id' => $mediaId,
            ];
        } else {
            $media = $mediaService->get($mediaId);
            $filePath = config('material.'.$type.'.prefix').'/'.$dateDir.$name;
            file_put_contents(public_path($filePath), $media);
            return $filePath;
        }
    }


    /**
     * 下载远程图片到本地
     *
     * @param string $url 远程文件地址
     * @param string $filenNme 保存后的文件名（为空时则为随机生成的文件名，否则为原文件名）
     * @param array $fileType 允许的文件类型
     * @param string $dirName 文件保存的路径（路径其余部分根据时间系统自动生成）
     * @param int $type 远程获取文件的方式
     * @return json 返回文件名、文件的保存路径
     * @author 52php.cnblogs.com
     */
    private function download_image($url, $fileName = '', $dateDir, $fileType = array('jpg', 'gif', 'png', 'jpeg'), $type = 1)
    {
        if ($url == '')
        {
            return false;
        }
     
        // 获取远程文件资源
        if ($type)
        {
            $ch = curl_init();
            $timeout = 30;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $file = curl_exec($ch);
            curl_close($ch);
        }
        else
        {
            ob_start();
            readfile($url);
            $file = ob_get_contents();
            ob_end_clean();
        }

        //设置文件夹权限
        $dirName = config('material.image.storage_path').$dateDir;
        is_dir($dirName) || mkdir($dirName, 0755, true);
        //设置文件存储路径
        $filePath = config('material.image.prefix').'/'.$dateDir.md5($url);
        file_put_contents(public_path($filePath), $file);

        return $filePath;

    }
    

    /**
     * 获取远程图片列表.
     *
     * @param Account $account 公众号
     * @param int     $offset  起始位置
     * @param int     $count   获取数量
     *
     * @return array 列表
     */
    private function getRemoteMaterialLists($account, $type, $offset, $count)
    {
        /*
        $mediaService = new MediaService(
            $account->app_id,
            $account->app_secret
        );

        return $mediaService->lists($type, $offset, $count)['item'];
        */
        $mediaService = $this->app($account->app_id, $account->app_secret)->material;
        return $mediaService->lists($type, $offset, $count)['item'];
    }

    /**
     * 取得远程素材的数量.
     *
     * @param Account $account 公众号
     * @param string  $type    素材类型
     *
     * @return int
     */
    private function getRemoteMaterialCount($account, $type)
    {
        /*
        $mediaService = new MediaService(
            $account->app_id,
            $account->app_secret
        );

        return $mediaService->stats($type);
        */
        $mediaService = $this->app($account->app_id, $account->app_secret)->material;
        $stats = $mediaService->stats();
        switch ($type) {
            case 'voice':
                return $stats->voice_count;
            case 'video':
                return $stats->video_count;
            case 'image':
                return $stats->image_count;
            case 'news':
                return $stats->news_count;
            
            default:
                return 0;
        }
        return 0;
    }

    /**
     * 获取本地存储素材id.
     *
     * @param int    $accountId 公众号id
     * @param string $mediaId   素材id
     *
     * @return NULL|string
     */
    private function getLocalMediaId($accountId, $mediaId)
    {
        return $this->materialRepository->getLocalMediaId($accountId, $mediaId);
    }
}
