<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getData(Request $request)
    {
        $productData = Product::query();
        if (!empty($request['search'])) {
            $search = "%" . $request['search'] . "%";
            $productData->orWhere('name', 'like', $search);
        }

        if (!empty($request['category_id'])) {
            $category_id = $request['category_id'];
            $productData->orWhere('category_id', $category_id);
        }

        $productData->where('stock', '>=', 1);
        $productData->orderBy('created_at', 'DESC');
        $products = $productData->paginate($request['perPage']);
        return response()->json($products);
    }

    public function detail($id)
    {
        $product =  Product::where('id', $id)->first();
        return response()->json($product);
    }

    public function post(Request $request)
    {
        // dd($request->all());
        $fotoFile = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file_extension = $file->getClientOriginalExtension();
            $lokasiFile = public_path() . '/' . 'assets/images';
            $this->fotoFile = 'image-' . $request->nama . Str::random(5) . '.' . $file_extension;
            $request->file('image')->move($lokasiFile, $this->fotoFile);
            $fotoFile = $this->fotoFile;
        }
        $productLatest = Product::latest('created_at')->first();
        if ($productLatest != null) {
            $nopo = substr($productLatest, 4, 5);
            $no_po = intval($nopo);
            do {
                $number = 'kode-' . str_pad(($no_po++ + 1), 5, "0", STR_PAD_LEFT) . '-' . $this->getRomawi(date('n')) . '-' . date('Y');
            } while ($productLatest->where('kode', $number)->exists());
        } else {
            $number = 'kode-00001' . '-' . $this->getRomawi(date('n')) . '-' . date('Y');
        }
        $empData = [
            'name' => $request->name,
            'kode' => $number,
            'stock' => $request->stock,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'image' => $fotoFile
        ];
        $product =   Product::create($empData); //create data berita
        return response()->json([
            'data' => $product,
            'message' => 'Data berhasil dibuat',
            'status' => 200,
        ]);
    }
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $emp = Product::Find($id);
        $lampiranFulltextFile = null;
        if ($request->hasFile('image')) {
            if ($emp->image) {
                File::delete(public_path('/assets/images/' . $emp->image));
            }
            $file = $request->file('image');
            $file_extension = $file->getClientOriginalExtension();
            $lokasiFile = public_path() . '/assets/images';

            $this->lampiranFulltextFile = 'image-' . $request->name . '-' . Str::random(5) . '.' . $file_extension;
            $request->file('image')->move($lokasiFile, $this->lampiranFulltextFile);
            $lampiranFulltextFile = $this->lampiranFulltextFile;
        } else {
            $this->lampiranFulltextFile = $emp->image;
            $lampiranFulltextFile = $this->lampiranFulltextFile;
        }

        $empData = [
            'name' => $request->name,
            'stock' => $request->stock,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'image' => $lampiranFulltextFile
        ];
        $emp->update($empData); //update berita
        return response()->json([
            'data' => $emp,
            'message' => "Data berhasil di ubah",
            'status' => 200,
        ]);
    }

    public function delete($id)
    {
        $emp = Product::find($id); //mengambil data berita berdasarkan id
        if (File::delete(public_path('/assets/images/' . $emp->image))) {
            Product::destroy($id); //delete product berdasarkan id
        } else {
            Product::destroy($id); //delete product berdasarkan id
        }
        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil dihapus',
        ]);
    }
    function getRomawi($bln)
    {
        switch ($bln) {
            case 1:
                return "I";
                break;
            case 2:
                return "II";
                break;
            case 3:
                return "III";
                break;
            case 4:
                return "IV";
                break;
            case 5:
                return "V";
                break;
            case 6:
                return "VI";
                break;
            case 7:
                return "VII";
                break;
            case 8:
                return "VIII";
                break;
            case 9:
                return "IX";
                break;
            case 10:
                return "X";
                break;
            case 11:
                return "XI";
                break;
            case 12:
                return "XII";
                break;
        }
    }
}
