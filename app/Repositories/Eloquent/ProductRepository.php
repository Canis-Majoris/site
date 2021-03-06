<?php 
namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\ProductInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\Tvoyo\User;
use App\Models\Language;
use App\Models\Product\Product;
use App\Models\Product\ProductMenu;
use App\Models\Product\ProductLanguage;
use App\Models\Region;
use Config;
use Image;

class ProductRepository extends BaseRepository implements ProductInterface  {

	/**
	 * @param Discount
	 */
	public function __construct(Product $model, ProductMenu $model_type, ProductLanguage $model_language, Language $language) {
		$this->model 		  = $model->filterRegion();
		$this->model_type	  = $model_type->filterRegion();
		$this->model_language = $model_language->filterRegion();
		$this->language 	  = $language->filterRegion();
		$this->region = session('region_id') ? session('region_id') : $this->default_region_id;
	}

	/**
	 * @return [type]
	 */
	public function getAll() {
		return $this->model->get();
	}

	/**
	 * @return [type]
	 */
	public function getTranslated() {
		return $this->model_language->get();
	}

	/**
	 * @return [type]
	 */
	public function getTypes() {
		return $this->model_type->get();
	}

	/**
	 * @param  [type]
	 * @return [type]
	 */
	public function getById($id) {

		return $this->model->find($id);
	}

	/**
	 * @param  [type]
	 * @return [type]
	 */
	public function getCount() {

		return $this->model->count();
	}

	/**
	 * @param  [type]
	 * @return [type]
	 */
	public function getSelect($id, array $select_array) {

		return $this->model->select($select_array)->find($id);
	}

	public function getCreate(array $attributes) {

	}

	/**
	 * @param  array
	 * @return [type]
	 */
	public function createNew($item_type, array $arr, array $update_arr) {

		$ind = 1;
        $ord = 1;
        if ($this->getCount() > 0) {
            $ind = $this->model->orderBy('ind', 'desc')->first()->ind + 1;
            $ord = $this->model->orderBy('ord', 'desc')->first()->ord + 1;
        }

        $arr['ind'] = $ind;
        $arr['ord'] = $ord;

        $languages = $this->language->get();
        $item = new Product;
        $result = $item->saveHelper($arr);
        $item_type->items()->save($item);

        foreach ($languages as $lang) {

            $update_arr['language_id'] = $lang->id;
            $translated = new ProductLanguage;
            $translated->saveHelper($update_arr);
            $item->translated()->save($translated);

        }

        return ['result' => $result, 'item' => $item];
	}

	/**
	 * @param  array
	 * @return [type]
	 */
	public function updateExisting($id, $language_id, array $arr, array $update_arr) {

		$languages = $this->language->get();
		$item = $this->getByid($id);

        $arr['ind'] = isset($arr['itemindex']) ? $arr['itemindex'] : $item->ind;
        $update_arr['ord'] = $item->ord;

        $result = $item->saveHelper($arr);
        $translated = $item->getTranslatedByLanguageId($language_id, ['*']);

        if ($translated) {

            $translated->saveHelper($update_arr);

        } else {

            foreach ($languages as $lang) {

                $update_arr['language_id'] = $lang->id;
                $translated = new ProductLanguage;
                $translated->saveHelper($update_arr);
                $item->translated()->save($translated);

            }

        }

        return ['result' => $result, 'item' => $item];
	}

	/**
	 * @param  array
	 * @return [type]
	 */
	public function getUpdate(array $data) {

		$id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;
		$language_id = null;
        $item = null;
        $translated = null;
        $item_type = null;
        $uploaded_img = null;

		if (isset($data['language_id'])) {

            $language_id = $data['language_id'];

            if ($id) {
                $item = $this->getSelect($id, ['id', 'price', 'weight', 'watch', 'is_service', 'is_p', 'is_com', 'is_goods', 'for_mobile', 'visible_on_site'
                    ,'yearly', 'price_old', 'days', 'discount', 'sum_x', 'sum_y', 'per_month_count', 'ind']);
                $item_type = $item->type()->first();
                $translated = $item->getTranslatedByLanguageId($language_id, ['video_link', 'text', 'alt', 'seo_title', 'seo_keywords', 'seo_description', 'img', 'name', 'short_text', 'created_at', 'updated_at']);

                $uploaded_img = $item->translated()->where('img', '!=', '')->whereNotNull('img')->count();

            } else {
                if (isset($data['current_menu_item_id'])) {
                    $item_type = $this->getTypes()->find($data['current_menu_item_id']);
                }
            }
        }

        return ['product' => $item, 'translated' => $translated, 'menu_item' => $item_type, 'language_id' => $language_id, 'uploaded_img' => $uploaded_img];
	}

	//Manage item Item
	/**
	 * @param  array
	 * @return [type]
	 */

	public function update($id = null, array $data) {

        $arr = [];
        $update_arr = [];
        $language_id = $data['language_id'];
        $images = null;
        $message = null;
        $result = null;

        $arr['price']                  = $data["price"];
        $arr['weight']                 = $data["weight"];
        $arr['watch']                  = (isset($data["watch"]) && $data["watch"] == "on") ? 1 : 0;
        $arr['is_service']             = (isset($data["is_service"]) && $data["is_service"] == "on") ? 1 : 0;
        $arr['is_p']                   = (isset($data["is_p"]) && $data["is_p"] == "on") ? 1 : 0;
        $arr['is_com']                 = (isset($data["is_com"]) && $data["is_com"] == "on") ? 1 : 0;
        $arr['is_goods']               = (isset($data["is_goods"]) && $data["is_goods"] == "on") ? 1 : 0;
        $arr['for_mobile']             = (isset($data["for_mobile"]) && $data["for_mobile"] == "on") ? 1 : 0;
        $arr['visible_on_site']        = (isset($data["visible_on_site"]) && $data["visible_on_site"] == "on") ? 1 : 0;
        $arr['yearly']                 = (isset($data["yearly"]) && $data["yearly"] == "on") ? 1 : 0;
        $arr['price_old']              = isset($data["price_old"]) ? $data["price_old"] : null;
        $arr['days']                   = isset($data["days"]) ? $data["days"] : null;
        $arr['discount']               = isset($data["discount"]) ? $data["discount"] : null;
        $arr['sum_x']                  = isset($data["sum_x"]) ? (float)trim($data["sum_x"]) : (float)0;
        $arr['sum_y']                  = isset($data["sum_y"]) ? (float)trim($data["sum_y"]) : (float)0;
        $update_arr['video_link']      = isset($data["video_link"]) ? $data["video_link"] : null;
        $update_arr['text']            = isset($data["text"]) ? $data["text"] : null;
        $update_arr['alt']             = isset($data["alt"]) ? $data["alt"] : null;
        $update_arr['seo_title']       = isset($data["seo_title"]) ? $data["seo_title"] : null;
        $update_arr['seo_keywords']    = isset($data["seo_keywords"]) ? $data["seo_keywords"] : null;
        $update_arr['seo_description'] = isset($data["seo_description"]) ? $data["seo_description"] : null;
        $update_arr['img']             = isset($data["product_image_name"]) ? trim($data["product_image_name"]) : null;
        $update_arr['name']            = htmlspecialchars($data["name"]);
        $update_arr['short_text']      = htmlspecialchars($data["short_text"]);
        $arr['per_month_count']        = (isset($data["per_month_count"]) && $data["per_month_count"] >= 0 && $data["per_month_count"] <= 12 ) ? $data["per_month_count"] : null;

        // if (isset($data['gallery_images'])) {

        //     $images = json_encode($data['gallery_images']);

        // } elseif (isset($data["image_name"]) ) {

        //     $images = json_encode([$data["image_name"]]);

        // }

        //$update_arr['img'] = $images;

        if (!isset($data['current_menu_item_id']) && isset($data['product_id'])) {

            $item_type = $this->getById($data['product_id'])->type;

        } elseif (isset($data['current_menu_item_id'])) {

            $item_type = $this->getTypes()->find($data['current_menu_item_id']);

        }

        if (isset($data['product_id']) && $data['product_id'] != null) {

            //update existing content

        	$result = $this->updateExisting($data['product_id'], $language_id, $arr, $update_arr);

            $id = $result['item']->id;
            $message = 'Product item updated';

        } else {

            //add new content
            
            $result = $this->createNew($item_type, $arr, $update_arr);

            $id = $result['item']->id;
            $message = 'New product item created';
        }

        if (isset($result['result']['errors']) && !empty($result['result']['errors'])) {

            $message = $result['result']['errors'];

        }

        return ['success' => $result['result']['status'], 'status' => $result['result']['status'], 'message' => $message, 'result' => ['id' => $id]];
	}

	public function delete(array $ids) {

        $errors = [];
        $messages = [];
        $status = 1; 

        foreach ($ids as $id) {
            $product = $this->getById($id);
            if ($product) {
                $translated = $product->translated()->first();
                
                $name = $product->name;

                if ($translated) {
                    $name = $translated->name . ( $translated->short_text ? ' (' . $translated->short_text . ')' : null );
                }
                
                if ($result = $product->deleteHelper()) {
                    if ($result['errors']) {
                        $errors [$name]= $result['errors'];
                        $status = $result['status'];
                    } else $messages [$name]= $result['messages'];
                }
            }
        }

        return ['errors' => $errors, 'messages' => $messages, 'status' => $status];
	}

	public function reorder(array $data) {

		$tmp_arr = [];

        if (isset($data['current_menu_item_id']) && $data['current_menu_item_id'] != '' && isset($data['diff'])) {

            $item_type = $this->getTypes()->find($data['current_menu_item_id']);
            $i = 0;
            $for_ids = array_keys($data['diff']);
            $item = $item_type->items()->whereIn('products.ord', $for_ids)->orderBy('products.id', 'asc')->get();

            foreach ($item as $s) {

                $i++;
                $s->ord = $data['diff'][$s->ord];
                $tmp_arr[$s->ord] = $data['diff'][$s->ord];
                $s->save();

            }

            return true;
       
        }

        return false;
	}


	public function changeStatus(array $ids) {

		$errors = '';
        $status = null;

        foreach ($ids as $id) {

            $item = $this->getById($id);
            $item->watch = ( $item->watch == 1 ? 0 : 1 );

            if ($result = $item->save()) {

                $errors .= ($result['errors'] ? $result['errors'] : '');
                $status = $item->watch;

            }

        }

        return ['status' => $status, 'errors' => $errors];
	}

}
