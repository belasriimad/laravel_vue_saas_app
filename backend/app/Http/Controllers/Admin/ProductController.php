<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use Endroid\QrCode\Builder\Builder;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\AddProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('admin.products.index')->with([
            'products' => Product::with(['positives','negatives'])->latest()->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddProductRequest $request)
    {
        //
        if($request->validated()) {
            $data = $request->validated();
            $data['image_path'] = $this->saveImage($request->file('image_path'));
            $product = Product::create($data);
            $product->qr_code_path = $this->saveProductQRCode($product->id);
            $product->save();
            $this->mergeProductImageWithQRCode($product);
            return redirect()->route('admin.products.index')->with([
                'success' => 'Product added successfully'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
        return view('admin.products.edit')->with([
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
        if($request->validated()) {
            $data = $request->validated();
            if($request->has('image_path')) {
                //remove the old thumbnail
                $this->removeProductImageFromStorage($product->image_path);
                //store the new thumbnail
                $data['image_path'] = $this->saveImage($request->file('image_path'));
                //merge product image with qr code
                $this->mergeProductImageWithQRCode($product->fill($data));
            }
            $product->update($data);
            return redirect()->route('admin.products.index')->with([
                'success' => 'Product updated successfully'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //remove product image
        $this->removeProductImageFromStorage($product->image_path);
        //remove product qr code
        $this->removeProductImageFromStorage($product->qr_code_path);
        //delete product
        $product->delete();
        return redirect()->route('admin.products.index')->with([
            'success' => 'Product deleted successfully'
        ]);
    }

    /**
     * Upload and save product images
     */
    public function saveImage($file)
    {
        $image_name = time().'_'.$file->getClientOriginalName();
        $file->storeAs('images/products',$image_name,'public');
        return 'storage/images/products/'.$image_name;
    }

    /**
     * Remove old images from storage
     */
    public function removeProductImageFromStorage($file)
    {
        $path = public_path($file);
        if(File::exists($path)) {
            File::delete($path);
        }
    }

    /**
     * Save product's qr code
     */
    public function saveProductQRCode($productId) 
    {
        //generate the qr code 
        $qrCode = Builder::create()
            ->data($productId)
            ->size(60)
            ->build();

        // Define the qr code file path and name
        $fileName = 'qr_codes/'. $productId. '.png';
        
        // Save the QR code to the storage directory
        Storage::disk('public')->put($fileName, $qrCode->getString());

        //return file name
        return 'storage/'.$fileName;
    }

    /**
     * Merge product image with qr code
     */
    public function mergeProductImageWithQRCode($product) 
    {
        // Load the base image (e.g., a logo or background image)
        $baseImagePath = $product->image_path;
        // check the type of image 
        if (mime_content_type($baseImagePath) === 'image/png') {
            $baseImage = imagecreatefrompng($baseImagePath);
        } elseif (mime_content_type($baseImagePath) === 'image/jpeg') {
            $baseImage = imagecreatefromjpeg($baseImagePath);
        }

        // Load the QR code as an image
        $qrCodePath = $product->qr_code_path;
        $qrImage = imagecreatefrompng($qrCodePath);

        // Get dimensions of both images
        $baseWidth = imagesx($baseImage);
        $baseHeight = imagesy($baseImage);
        $qrWidth = imagesx($qrImage);
        $qrHeight = imagesy($qrImage);

        // Calculate position to center the QR code on the base image 
        $dstX = ($baseWidth - $qrWidth) / 2; 
        $dstY = ($baseHeight - $qrHeight) / 2; 

        // Merge the QR code onto the base image
        // The imagecopy function in PHP is used to copy a portion 
        // of one image onto another image. It's a core function of the GD library, 
        // which is commonly used for image manipulation in PHP.
        // 0,0 means top left
        imagecopy($baseImage, $qrImage, $dstX, $dstY, 0, 0, $qrWidth, $qrHeight);

        // Enable transparency (important for PNGs with transparent backgrounds)
        imagesavealpha($baseImage, true);

        // Save the final image inside the folder where the product image is saved
        imagepng($baseImage, $product->image_path);

        // Free up memory
        imagedestroy($baseImage);
        imagedestroy($qrImage);
    }
}
