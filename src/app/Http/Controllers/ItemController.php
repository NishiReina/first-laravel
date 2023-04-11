<?php

namespace App\Http\Controllers;

use Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ItemRequest;
use App\Models\Item;
use App\Models\Like;
use App\Models\Category;
use App\Models\Condition;
use App\Models\CategoryItem;

class ItemController extends Controller
{
    public function index(Request $request){
        if ($request->page == 'mylist'){
            $items = Like::where('user_id', Auth::id())->get()->map(function ($like_item){
                return $like_item->item;
            });
        }else {
            $items = Item::where('user_id', '<>', Auth::id())->get();
        }
        return view('index',compact('items'));
    }

    public function detail(Item $item){
        return view('detail', compact('item'));
    }

    public function sellView(){
        $categories = Category::all();
        $conditions = Condition::all();
        return view('sell',compact('categories', 'conditions'));
    }

    public function sellCreate(ItemRequest $request){

        $img = $request->file('img_url');
        $img_url = $img->store('img','public');

        $item = Item::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'img_url' => $img_url,
            'condition_id' => $request->condition_id,
            'user_id' => Auth::id(),
        ]);

        foreach ($request->categories as $category_id){
            CategoryItem::create([
                'item_id' => $item->id,
                'category_id' => $category_id
            ]);
        }

        return redirect()->route('item.detail',['item' => $item->id]);
    }

    public function photo(){

        $files = Storage::disk('s3')->files();

        // ファイルリスト生成
        $list = [];
        foreach ($files as $file) {
            $date = Storage::disk('s3')->lastModified($file);
            $list[$date] = [
                'name' => $file,
                'date' => date("Y-m-d H:i:s", $date),
                'type' => pathinfo($file, PATHINFO_EXTENSION),
                'size' =>  Storage::disk('s3')->size($file),
            ];
        }
        // 日付降順にソート
        krsort($list);

        // 画面表示
        return $list;
        return view('photo')->with(['list' => $list]);
    }
}
