<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use App\Files;
use App\Dir;

class FileController extends Controller
{
    //
    public function upload(Request $request) {
        if ($request->hasFile('fileUpload')) {
            $this->validate($request, [
                'fileUpload' => 'mimes:docx,xls,xlsx,pdf'
            ], [
                'mimes' => 'Tệp tải lên phải là tệp: docx, xls, xlsx, pdf.'
            ]);

            $file = $request->file('fileUpload');

            if ($request->docsName != '') {
                $fileName = $request->docsName;
            } else {
                $fileName = $file->getClientOriginalName();
            }

            
            $fileExtension = $file->getClientOriginalExtension();

            // Tạo tên file
            $pages = range(1,20);
            shuffle($pages);
            $prefix = array_shift($pages);
            if ($request->docsName != '') {
                $fileNameToSave = time() . Auth::user()->id . $prefix . '_' . $request->docsName;
            } else {
                $fileNameToSave = time() . Auth::user()->id . $prefix . '_' . $fileName;
            }

            $file->move('fileUploaded', $fileNameToSave);

            $dir = $request->mainDir;
            if ($request->folder !== '') {
                $dir .= $request->folder;
            }

            // Nếu người dùng đặt tên cho file
            if ($request->docsName != '') {
                $fileName = $request->docsName . '.' . $fileExtension;
            }

            // Lưu thông tin file vào File database
            $fileDb = new Files;
            $fileDb->name = $fileNameToSave;
            $fileDb->fileName = $fileName;
            $fileDb->dir = $dir;
            if ($request->allcanview === 'true') {
                $fileDb->allcanview = true;
            }
            $fileDb->parent = $request->mainDir;
            $fileDb->type = $fileExtension;
            $fileDb->owner = Auth::user()->name;
            $fileDb->viewer = Auth::user()->name;
            $fileDb->save();

            return redirect('/');
        }
    }

    public function createType(Request $request) {
        $this->validate($request, [
            'newType' => 'required|min:6'
        ], [
            'newType.required' => 'Không nhận được dữ liệu',
            'newType.min'      => 'Vui lòng nhập tối thiểu 6 kí tự'
        ]);

        $dir = new Dir;
        $dir->dir = $request->newType;
        $dir->parent = 'root';
        $dir->owner = Auth::user()->name;
        $dir->viewer = Auth::user()->name;
        if ($request->allcanview === 'true') {
            $dir->allcanview = true;     
        }
        $dir->save();

        return redirect()->back()->with('thongbao', 'Tạo mới thành công.');
    }

    public function uploadFile(Request $request) {
        if ($request->hasFile('fileUpload')) {
            $this->validate($request, [
                'fileUpload' => 'mimes:docx,xls,xlsx,pdf'
            ], [
                'mimes' => 'Tệp tải lên phải là tệp: docx, xls, xlsx, pdf.'
            ]);

            $file = $request->file('fileUpload');

            $fileName = $file->getClientOriginalName();
            $fileExtension = $file->getClientOriginalExtension();

            // Tạo tên file
            $pages = range(1,20);
            shuffle($pages);
            $prefix = array_shift($pages);
            $fileNameToSave = time() . Auth::user()->id . $prefix . '_' . $fileName;

            $file->move('fileUploaded', $fileNameToSave);

            $dir = $request->currentDir;

            // Lưu thông tin file vào File database
            $fileDb = new Files;
            $fileDb->name = $fileNameToSave;
            $fileDb->fileName = $fileName;
            $fileDb->dir = $dir;
            $fileDb->parent = $request->rootDir;
            $fileDb->type = $fileExtension;
            $fileDb->owner = Auth::user()->name;
            $fileDb->viewer = Auth::user()->name;
            if ($request->allcanview === 'true') {
                $fileDb->allcanview = true;
                $fileDb->allowshare = true;
            }
            $fileDb->save();

            return redirect()->back();
        }
    }

    public function createFolder(Request $request) {
        $this->validate($request, [
            'newFolder' => 'required|min:3'
        ], [
            'newFolder.required' => 'Không nhận được dữ liệu',
            'newFolder.min'      => 'Vui lòng nhập tối thiểu 3 kí tự'
        ]);

        $currentDir = $request->currentDir;
        $currentDir .= '/' . $request->newFolder;
        // Lưu thư mục mới tạo vào Dir Database
        $dirDb = new Dir;
        $dirDb->dir = $currentDir;
        $dirDb->parent = $request->rootDir;
        $dirDb->owner = Auth::user()->name;
        $dirDb->viewer = Auth::user()->name;
        if ($request->allcanview === 'true') {
            $dirDb->allcanview = true;
            $dirDb->allowshare = true;
        }
        $dirDb->save();

        return redirect()->back();
    }

    public function removeType(Request $request) {
        $dir = new Dir;
        $dirToRemove = $dir->where('owner', Auth::user()->name)->where('dir', $request->dirToRemove)->delete();

        return redirect('library');
    }

    public function typeData($type) {
        return view('user-function/library', ['title' => 'Thư viện', 'typeDir' => $type]);
    }

    public function removeItem(Request $request) {
        $fileDb = new Files;

        $fileDir = $request->itemDirSelected;
        $fileName = $request->itemNameSelected;
        
        $fileToRemove = $fileDb->where('owner', Auth::user()->name)->where('name', $fileName)->where('dir', $fileDir)->delete();
        
        $fileDirToRemove = "/fileUpload/" . $fileName;
        File::delete($fileDirToRemove);
        return redirect()->back();
    }

    public function renameItem(Request $request) {
        $this->validate($request, [
            'name' => 'required|min:3'
        ], [
            'name.required' => 'Không nhận được dữ liệu',
            'name.min'      => 'Vui lòng nhập tối thiểu 3 kí tự'
        ]);

        $fileDb = new Files;

        // Xử lí dữ liệu
        $fileDir = $request->itemDirSelected;
        $fileName = $request->itemNameSelected;
        $newFileName = $request->name;


        $fileToRename = $fileDb->where('owner', Auth::user()->name)->where('name', $fileName)->where('dir', $fileDir)->get();
        $id = $fileToRename[0]->id;

        $fileToRename = Files::find($id);

        $fileRealName = $fileToRename->name;
        $oldFileName = $fileToRename->fileName;
        $oldFileRealName = $fileToRename->name;

        // Dữ liệu để rename
        $newFileName .= '.' . $fileToRename->type;
        $newFileRealName = str_replace($oldFileName, $newFileName, $fileRealName);

        echo $newFileName . "<br/>";
        echo $newFileRealName . "<br/>";
        // dd($fileToRename);

        // $fileToRename->update(['fileName' => $newFileName, 'name' => $newFileRealName]);
        $fileToRename->fileName = $newFileName;
        $fileToRename->name = $newFileRealName;
        $fileToRename->save();

        echo "<hr/>";

        echo $fileToRename->fileName . "<br/>";
        echo $fileToRename->name . "<br/>";

        rename(public_path("fileUploaded/$oldFileRealName"), public_path("fileUploaded/$newFileRealName"));
        
        return redirect()->back();   
    }
}