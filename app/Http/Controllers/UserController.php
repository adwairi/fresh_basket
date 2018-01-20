<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use App\User;
use Intervention\Image\ImageManagerStatic as Image;
class UserController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(Auth::id());
        return view('user.index', ['user' => $user]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|numeric|min:10',
            'image' => 'required',
        ]);
        if($validator->fails())
            return $validator->validate();

        $data = $request->all();
        $user = User::find($data['user_id']);
        $user->name = $data['name'];
//        $user->email = $data['email'];   // email cannot be change
        $user->phone = $data['phone'];
        $user->img_url = $this->savePhoto($request->file('image'));
        if ($user->save()){
            return redirect('/user');
        }
    }

    private function savePhoto($image){
        $img = Image::make($image)->fit(150, 150, function ($constraint) {
//          $constraint->aspectRatio();
            $constraint->upsize('top');
        });
        $imagename = time() . '.' . $image->getClientOriginalExtension();
        $destinationPath = public_path('/images');
        $img->save($destinationPath . '/' . $imagename);
        return asset('images/' . $imagename);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
