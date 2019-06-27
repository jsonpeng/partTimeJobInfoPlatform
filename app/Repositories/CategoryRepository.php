<?php

namespace App\Repositories;

use App\Models\Category;
use InfyOm\Generator\Common\BaseRepository;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

use DB;

/**
 * Class CategoryRepository
 * @package App\Repositories
 * @version October 17, 2017, 4:39 pm CST
 *
 * @method Category findWithoutFail($id, $columns = ['*'])
 * @method Category find($id, $columns = ['*'])
 * @method Category first($columns = ['*'])
 */
class CategoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'slug',
        'sort',
        'parent_id',
        'image'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Category::class;
    }

    public function getCacheCategory($idORslug){
        return Cache::remember('zcjy_category_'.$idORslug, Config::get('web.shrottimecache'), function() use ($idORslug) {
            try {
                if (is_numeric($idORslug)) {
                    return Category::find($idORslug);
                } else {
                    return Category::where('slug', $idORslug)->first();
                }
                return Category::find($id);
            } catch (Exception $e) {
                return null;
            }
        });
    }

    //获取子分类列表 并是否带上文章
    //$withPosts 为true则带文章，false不带
    public function getCacheChildCats($parentidORslug, $withPosts=false){
        return Cache::remember('zcjy_child_cat_of_'.$parentidORslug, Config::get('web.shrottimecache'), function() use ($parentidORslug,$withPosts) {
            try {
                $parentCat = $this->getCacheCategory($parentidORslug);
                if ($parentCat) {
                    if ($withPosts) {
                        return Category::where('parent_id', $parentCat->id)->with('posts')->get();
                    } else {
                        return Category::where('parent_id', $parentCat->id)->get();
                    }
                }else{
                    return collect([]);
                }
            } catch (Exception $e) {
                return collect([]);
            }
        });
    }

    //按层级获取所有的分类信息
    //子分类会排在父分类下方
    public function getCascadeCategories(){
        $origin_categories = Category::where('parent_id', null)->orWhere('parent_id', 0)->get();
        $cascade_categories = collect([]);
        foreach ($origin_categories as $tmp1) {
            $cascade_categories->push($tmp1);
            $origin_categories_tmp = Category::where('parent_id', $tmp1->id)->get();
            foreach ($origin_categories_tmp as $tmp2) {
                $tmp2->name = $tmp2->name;
                $cascade_categories->push($tmp2);
            }
        }
        return $cascade_categories;
    }

    //获取包含自身及子分类的分类信息（自身以及子分类）
    public function getChildCatsOfCatWithParent($cat)
    {
        if (empty($cat)) {
            return collect([]);
        }
        $collection = collect([$cat]);
        $childCats = Category::where('parent_id', $cat->id)->get();
        if ($childCats->count()) {
            foreach ($childCats as $childCat) {
                $childCollection = $this->getChildCatsOfCatWithParent($childCat);
                $collection = $collection->concat($childCollection);
            }
        }
        return $collection;
    }

    public function getCachePostOfCat($idORslug, $number = 6){
        return Cache::remember('zcjy_posts_of_category_id'.$idORslug.'_'.$number, Config::get('web.shrottimecache'), function() use ($idORslug, $number) {
            try {
                $cat = $this->getCacheCategory($idORslug);
                if ($cat) {
                    return $cat->posts()->take($number)->get();
                }else{
                    return collect([]);
                }
            } catch (Exception $e) {
                return collect([]);
            }
        });
    }
    /**
     * 获取某分类下的文章，包含子分类
     * @param  [type]  $parentidORslug [description]
     * @param  boolean $withPosts      [description]
     * @return [type]                  [description]
     */
    public function getCachePostOfCatIncludeChildren($category){
        return Cache::remember('get_cache_post_of_cat_include_children'.$category->id, Config::get('web.shrottimecache'), function() use ($category) {
            try {
                $childCats = $this->getChildCatsOfCatWithParent($category);
                $cat_id_array = array();
                foreach ($childCats as $cat) {
                    array_push($cat_id_array, $cat->id);
                }
                return DB::table('posts')
                    ->join('category_post', 'posts.id', '=', 'category_post.post_id')
                    ->join('categories', 'categories.id', '=', 'category_post.category_id')
                    ->select('posts.*')
                    ->whereIn('categories.id', $cat_id_array)
                    ->where('posts.deleted_at', null)
                    ->get();
            } catch (Exception $e) {
                return collect([]);
            }
        });
    }

        public function getCachePostByCatSlug($slug, $number=6){
        return Cache::remember('zcjy_posts_of_category_'.$slug.'_'.$number, Config::get('web.shrottimecache'), function() use ($slug, $number) {
            try {
                $cat = $this->getCacheCategoryBySlug($slug);
                if ($cat) {
                    return $cat->posts()->take($number)->orderBy('created_at','desc')->get();
                }else{
                    return collect([]);
                }
            } catch (Exception $e) {
                return;
            }
        });
    }


   public function getPostByCatSlugWithSkipAndTake($slug, $skip=0,$take=16){
            try {
                $cat = $this->getCacheCategoryBySlug($slug);
                if ($cat) {
                    return $cat->posts()->skip($skip)->take($take)->orderBy('created_at','desc')->get();
                }else{
                    return collect([]);
                }
            } catch (Exception $e) {
                return;
            }
     
    }

    public function getCacheCategoryBySlug($slug){
        return Cache::remember('zcjy_category_'.$slug, Config::get('web.shrottimecache'), function() use ($slug) {
            try {
                return Category::where('slug', $slug)->first();
            } catch (Exception $e) {
                return;
            }
        });
    }

    //获取父分类下的子分类
    public function getCacheChildCatsOfParentBySlug($parent_slug){
        return Cache::remember('child_cat_of_parent_by_slug'.$parent_slug, Config::get('web.shrottimecache'), function() use ($parent_slug) {
            try {
                $cat = $this->getCacheCategoryBySlug($parent_slug);
                if ($cat) {
                    return Category::where('parent_id', $cat->id)->get();
                }else{
                    return collect([]);
                }
            } catch (Exception $e) {
                return;
            }
        });
    }

    /**
     * 生成适合Form::select展示的array格式
     * 新建产品分类信息
     * @return [type] [description]
     */
    public function getRootCatArray($id = 0){
        $catArray = array(0 => '无分类');
        $categories = Category::select('id', 'name')->where('parent_id', 0)->orWhere('parent_id', null)->get()->toArray();
        while (list($key, $val) = each($categories)) {
            if ($id != $val['id']) {
                $catArray[$val['id']] = $val['name'];
            }
        }
        return $catArray;
    }
}
