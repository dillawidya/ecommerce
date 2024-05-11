<?php

namespace App\Http\Controllers\admin;

use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
// use Image;
 

class CategoryController extends Controller
{
    public function index(Request $request) {
        $categories = Category::latest();

        if (!empty($request->get('keyword'))) {
            $categories = $categories->where('name','like','%'.$request->get('keyword').'%');
        }
        $categories = $categories->paginate(10);
        return view('admin.category.list',compact('categories'));
    }

    public function create() {
        return view('admin.category.create');
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:categories',
        ]);

        if ($validator->passes()) {

            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();

            //Save Image

            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);
                
                $newImageName = $category->id.'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/category/'.$newImageName;
                File::copy($sPath,$dPath);
                
                $category->image = $newImageName;
                $category->save();

                //Generate Image Thumbnail
                // $dPath = public_path().'/uploads/category/thumb/'.$newImageName;
                // $img = Image::make($sPath);
                // $img->resize(450, 600);
                // $img->fit(800, 600, function ($constraint) {
                //     $constraint->upsize();
                // });
                // $img->save($dPath);


                
            }

            $request->session()->flash('success', 'Kategori Berhasil Ditambahkan');

            return response()->json([
                'status' => true,
                'message' => 'Kategori Berhasil Ditambahkan'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($categoryId, Request $request) {
        $category = Category::find($categoryId);
        if (empty($category)) {
            return redirect()->route('categories.index');
        }
        
        return view('admin.category.edit',compact('category'));
    }


    public function update($categoryId, Request $request) {
       
        $category = Category::find($categoryId);

        if (empty($category)) {
            $request->session()->flash('error','Kategori Tidak Ditemukan');
            return response()->json([
                'status' => false,
                'message' => 'Data Kategori Tidak Ditemukan'
            ]); 
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'notFound' => 'true',
            'slug' => 'required|unique:categories,slug,'.$category->id.',id',
        ]);

        if ($validator->passes()) {

            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();

            $oldImage = $category->image;

            //Save Image
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);
                
                $newImageName = $category->id.'-'.time().'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/category/'.$newImageName;
                File::copy($sPath,$dPath);

                //Generate Image Thumbnail
                // $dPath = public_path().'/uploads/category/thumb/'.$newImageName;
                // $img = Image::make($sPath);
                // $img->resize(450, 600); 
                // $img->fit(800, 600, function ($constraint) {
                //     $constraint->upsize();
                // });
                // $img->save($dPath);

                $category->image = $newImageName;
                $category->save();

                //Delete Old Images Here
                File::delete(public_path().'/uploads/category/'.$oldImage);
                //File::delete(public_path().'/uploads/category/'.$oldImage);

            }

            $request->session()->flash('success', 'Kategori Berhasil Diupdate');

            return response()->json([
                'status' => true,
                'message' => 'Kategori Berhasil Diupdate'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($categoryId, Request $request) {
        $category = Category::find($categoryId);
        if (empty($category)) {
            $request->session()->flash('error','Kategori Tidak Ditemukan');
            return response()->json([
                'status' => true,
                'message' => 'Kategori Tidak Ditemukan'
            ]);
        }


        File::delete(public_path().'/uploads/category/'.$category->image);


        $category->delete();

        $request->session()->flash('success','Kategori Berhasil Dihapus');

        return response()->json([
            'status' => true,
            'message' => 'Kategori Berhasil Dihapus'
        ]);
    }
}
