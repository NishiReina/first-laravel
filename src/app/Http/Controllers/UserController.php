<?php

namespace App\Http\Controllers;

use Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use App\Models\User;
use App\Models\Item;
use App\Models\SoldItem;
use App\Http\Requests\ProfileRequest;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;


class UserController extends Controller
{
    public function profile(){

        $profile = Profile::where('user_id', Auth::id())->first();

        return view('profile',compact('profile'));
    }

    public function updateProfile(ProfileRequest $request){

        $img = $request->file('img_url');
        $s3Client = new S3Client([
                'credentials' => [
                    'key' => 'coachtech',
                    'secret' => 'coachtech_pass',
                ],
                'region'   => 'ap-northeast-1',
                'version'  => 'latest',
                'endpoint' => 'http://localstack:4566',
                'use_path_style_endpoint' => true,
                'retries' => [
                    'mode' => 'legacy',
                    'max_attempts' => 10, 
                ]
        ]);

        if (isset($img)){
            $image_data = file_get_contents($img->getRealPath());
            // Storage::disk('s3')->put($img->getClientOriginalName(), $image_data, 'public');
            $s3Client->putObject([
                'Bucket' => 'coachtech',
                'Key' => $img->getClientOriginalName(),
                'Body' => $image_data
            ]);
            $img_url = $img->getClientOriginalName();
        }else{
            $img_url = '';
        }
        
        $profile = Profile::where('user_id', Auth::id())->first();
        if ($profile){
            $profile->update([
                'user_id' => Auth::id(),
                'img_url' => $img_url,
                'postcode' => $request->postcode,
                'address' => $request->address,
                'building' => $request->building
            ]);
        }else{
            Profile::create([
                'user_id' => Auth::id(),
                'img_url' => $img_url,
                'postcode' => $request->postcode,
                'address' => $request->address,
                'building' => $request->building
            ]);    
        }

        User::find(Auth::id())->update([
            'name' => $request->name
        ]);
        
        return redirect('/');
    }

    public function mypage(Request $request){
        $user = User::find(Auth::id());
        if ($request->page == 'buy'){
            $items = SoldItem::where('user_id', $user->id)->get()->map(function ($sold_item) {
                return $sold_item->item;
            });         
        }else {
            $items = Item::where('user_id', $user->id)->get();
        }

        return view('mypage', compact('user', 'items'));
    }
}
