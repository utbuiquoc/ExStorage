<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
use App\Files;
use App\Dir;
use App\User;
use App\Friends;

class FileController extends Controller
{
    //
    public function library() {
        return view('user-function.library', ['title' => 'Thư viện']);
    }

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
                'fileUpload' => 'required|mimes:docx,doc,xls,xlsx,pdf'
            ], [
                'fileUpload.mimes'     => 'Tệp tải lên phải là tệp: docx, doc, xls, xlsx, pdf.',
                'fileUpload.required'  => 'Không nhận được tệp tin'
            ]);

            $file = $request->file('fileUpload');

            $currentDir = $request->currentDir;

            $fileName = $file->getClientOriginalName();
            $fileExtension = $file->getClientOriginalExtension();

            // Tạo tên file
            $pages = range(1,20);
            shuffle($pages);
            $prefix = array_shift($pages);
            $fileNameToSave = time() . Auth::user()->id . Str::random(15) . '.' . $fileExtension;


            // return $fileNameToSave;
            $file->move('fileUploaded', $fileNameToSave);
            
            if ($fileExtension === 'doc' || $fileExtension === 'xls') {
                exec("libreoffice --headless --convert-to pdf fileUploaded/$fileNameToSave --outdir fileUploaded");
                $fileNameToSave = substr($fileNameToSave, 0, -3) . 'pdf';
            } else if ($fileExtension === 'docx' || $fileExtension === 'xlsx') {
                exec("libreoffice --headless --convert-to pdf fileUploaded/$fileNameToSave --outdir fileUploaded");
                $fileNameToSave = substr($fileNameToSave, 0, -4) . 'pdf';
            }
            
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

            $fileUploaded = $fileDb->where('name', $fileNameToSave)->where('owner', Auth::user()->name)->get();
            $currentTime = $fileUploaded[0]->created_at;

            return "$fileNameToSave|$fileExtension|$fileName|$currentTime";
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

        $folderCreated = $dirDb->where('dir', $currentDir)->where('owner', Auth::user()->name)->get();
        $currentTime = $folderCreated[0]->created_at;

        return "$currentDir|$currentTime";
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

    public function removeFolder(Request $request) {
        $DirDb = new Dir;

        $fileDir = $request->itemDirSelected;

        
        $DirDb->where('owner', Auth::user()->name)->where('dir', $fileDir)->delete();

        // Nếu mà có thư mục con thì xóa luôn các thư mục con
        if (($DirDb->where('owner', Auth::user()->name)->where('dir', 'like', "%$fileDir%")->get()) != null) {
            $DirDb->where('owner', Auth::user()->name)->where('dir', 'like', "%$fileDir%")->delete();
        }

        $this->removeAllChildFile($fileDir);
        
        // $fileDirToRemove = "/fileUpload/" . $fileName;
        // File::delete($fileDirToRemove);
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

        $fileToRename->fileName = $newFileName;
        $fileToRename->name = $newFileRealName;
        $fileToRename->save();

        echo "<hr/>";

        echo $fileToRename->fileName . "<br/>";
        echo $fileToRename->name . "<br/>";

        rename(public_path("fileUploaded/$oldFileRealName"), public_path("fileUploaded/$newFileRealName"));
        
        return redirect()->back();   
    }

    //Phần xóa file con của folder.
    private function removeAllChildFile($dir) {
        $fileDb = new Files;
        $filesNeedChange = $fileDb->where('dir', 'regexp', "$dir([a-zA-Z0-9!\p{L}@#$%^&*\s\/_-]*|)$")->get();

        // Đổi dir từng file
        foreach ($filesNeedChange as $key => $value) {
            $fileId = $value->id;
            
            $file = Files::find($fileId);
            $file->delete();
        }
    }

    //Phần đổi tên folder.
    public function renameChildFile($fileId, $oldDir, $newDir) {
        // $fileId = $request->fileId;
        // $oldDir = $request->oldDir;
        // $newDir = $request->newDir;
        $fileToRename = Files::find($fileId);
        
        $fileDir = $fileToRename->dir;
        $fileDir = str_replace($oldDir, $newDir, $fileDir);

        $fileToRename->dir = $fileDir;
        $fileToRename->save();

        return $fileToRename;
    }

    private function renameAllChildFile($dir, $newDir) {
        $fileDb = new Files;
        $filesNeedChange = $fileDb->where('dir', 'regexp', "$dir([a-zA-Z0-9!\p{L}@#$%^&*\s\/_-]*|)$")->get();

        // Đổi dir từng file
        foreach ($filesNeedChange as $key => $value) {
            $fileId = $value->id;
            $this->renameChildFile($fileId, $dir, $newDir);
        }

        // return $filesNeedChange;
    }

    public function renameFolder(Request $request) {
        $this->validate($request, [
            'itemDirSelected'   => 'required|min:3',
            'name'  => 'required|min:3'
        ]);

        // Xử lí dữ liệu
        $fileDir = $request->itemDirSelected;
        $newName = $request->name;

        $DirDb = new Dir;

        $DirsNeedRename = $DirDb->where('owner', Auth::user()->name)->where('dir', 'regexp', "$fileDir([a-zA-Z0-9!\p{L}@#$%^&*\s\/_-]*|)$")->get();

        //Tạo ra DirName mới
        $newDirName = explode('/', $fileDir);
        $newDirName[count($newDirName) - 1] = $newName;
        $newDirName = implode('/', $newDirName);

        //Đổi tên

        foreach ($DirsNeedRename as $key => $value) {
            $dirId = $value->id;

            $dirFinded = Dir::find($dirId);

            $nameDir = $dirFinded->dir;
            $nameDir = str_replace($fileDir, $newDirName, $nameDir);

            $dirFinded->dir = $nameDir;
            $dirFinded->save();
        }

        $this->renameAllChildFile($fileDir, $newDirName);

        return $newDirName; 
    }

    //Phần thay đổi private/public.
    public function changePermission($fileId, $allowShare, $allCanView, $type) {
        if ($type === 'folder') {
            $item = Dir::find($fileId);
        } else if ($type == 'file') {
            $item = Files::find($fileId);
        }

        $item->allowshare = $allowShare;
        $item->allcanview = $allCanView;
        $item->save();
    }

    private function changeAllFilePermission($dir, $allowShare, $allCanView) {
        $fileDb = new Files;
        $dir = str_replace('/', '\/', $dir);
        $filesNeedChange = $fileDb->where('owner', Auth::user()->name)->where('dir', 'regexp', "$dir([a-zA-Z0-9!\p{L}@#$%^&*\s\/_-]*|)$")->get();

        // Đổi dir từng file
        foreach ($filesNeedChange as $key => $value) {
            $fileId = $value->id;
            $this->changePermission($fileId, $allowShare, $allCanView, 'file');
        }

        $folderNeedChange = Dir::where('owner', Auth::user()->name)->where('dir', 'regexp', "$dir([a-zA-Z0-9!\p{L}@#$%^&*\s\/_-]*|)$")->get();


        foreach ($folderNeedChange as $key => $value) {
            $fileId = $value->id;
            $this->changePermission($fileId, $allowShare, $allCanView, 'folder');
        }
    }

    public function setPrivate(Request $request) {
        $fileName = $request->itemNameSelected;

        if ($fileName[strlen($fileName)-1] === '/') {
            $fileDir = substr($fileName,0,-1);

            $folder = Dir::where('owner', Auth::user()->name)->where('dir', $fileDir)->get();
            $folder = Dir::find($folder[0]->id);

            $folder->allcanview = false;
            $folder->allowshare = false;
            $folder->save();

            $this->changeAllFilePermission($fileDir, 0, 0);

            return 'Thành công!';
        }

        $fileDB = new Files;

        $file = $fileDB->where('owner', Auth::user()->name)->where('name', $fileName)->get();

        $file = Files::find($file[0]->id);

        $file->allcanview = false;
        $file->allowshare = false;
        $file->save();

        return 'Thành công!';
    }

    public function setPublic(Request $request) {
        $fileName = $request->itemNameSelected;

        if ($fileName[strlen($fileName)-1] === '/') {
            $fileDir = substr($fileName,0,-1);

            $folder = Dir::where('owner', Auth::user()->name)->where('dir', $fileDir)->get();
            $folder = Dir::find($folder[0]->id);

            $folder->allcanview = true;
            $folder->allowshare = false;
            $folder->save();

            $this->changeAllFilePermission($fileDir, 0, 1);
            return 'Thành công!';
        }

        $fileDB = new Files;

        $file = $fileDB->where('owner', Auth::user()->name)->where('name', $fileName)->get();

        $file = Files::find($file[0]->id);

        $file->allcanview = true;
        $file->allowshare = false;
        $file->save();

        return 'Thành công!';
    }

    public function setLimited(Request $request) {
        $fileName = $request->fileDir;

        if ($fileName[strlen($fileName)-1] === '/') {
            $fileDir = substr($fileName,0,-1);

            $folder = Dir::where('owner', Auth::user()->name)->where('dir', $fileDir)->get();
            $folder = Dir::find($folder[0]->id);

            $folder->allcanview = false;
            $folder->allowshare = true;
            $folder->save();

            $this->changeAllFilePermission($fileDir, 1, 0);

            return 'Thành công!';
        }

        $fileDB = new Files;

        $file = $fileDB->where('owner', Auth::user()->name)->where('name', $fileName)->get();

        $file = Files::find($file[0]->id);

        $file->allcanview = false;
        $file->allowshare = true;
        $file->save();

        return 'Thành công!';
    }


    // Phần thêm xóa friend trong share file bị hạn chế
    protected function removeIdUser($str, $id) {
        $strArr = explode('|', $str);

        foreach ($strArr as $key => $value) {
            if ($value == $id) {
                unset($strArr[$key]);

                return implode('|', $strArr);
            }
        }

        return implode('|', $strArr);
    }

    public function getListFriends() {
        $userId = Auth::user()->id;

        $friendsId = Friends::find($userId)->friends;
        
        $friendsIdArr = explode('|', $friendsId);
        $friendInfoArr = array();
        foreach ($friendsIdArr as $key => $value) {
            $friendInfo = array(
                'name'  => User::find($value)->name, 
                'id'    => $value
            );
            $friendInfoArr[] = $friendInfo;
        }

        return $friendInfoArr;
    }

    public function getListFriendsNotAllow(Request $request) {
        $friendsName = '';

        $userId = Auth::user()->id;

        // Lấy tên của những người đã kết bạn.
        $friendsId = Friends::find($userId)->friends;
        $friendsIdArr = explode('|', $friendsId);
        foreach ($friendsIdArr as $key => $value) {
            $friendItemName = User::find($value)->name;
            $friendsName .= '|' . $friendItemName;
        }
        $friendsName = substr($friendsName, 1);

        // Xóa những friend đã có thể xem file
        if (isset($request->friendAllowed)) {
            $friendAllowed = $request->friendAllowed;

            $friendsArr = explode('|', $friendAllowed);

            foreach ($friendsArr as $key => $value) {
                $friendsName = $this->removeIdUser($friendsName, $value);
            }
            
            $friendsIdArr = explode('|', $friendsName);
            $friendInfoArr = array();
            if ($friendsIdArr[0] == '') { return null; }
            foreach ($friendsIdArr as $key => $value) {
                $friendInfo = array(
                    'name'  => $value, 
                    'id'    => User::where('name', $value)->get()[0]->id
                );

                $friendInfoArr[] = $friendInfo;
            }

            return $friendInfoArr;
        }

        return explode('|', $friendName);
    }

    //Phần add friend cho thư mục
    public function addItemViewer($fileId, $name, $type) {
        if ($type === 'folder') {
            $item = Dir::find($fileId);
        } else if ($type == 'file') {
            $item = Files::find($fileId);
        }

        $item->viewer = $item->viewer . '|' . $name;
        $item->save();
    }

    private function addViewer($dir, $viewerName) {
        $fileDb = new Files;
        $dir = str_replace('/', '\/', $dir);
        $filesNeedChange = $fileDb->where('owner', Auth::user()->name)->where('dir', 'regexp', "$dir([a-zA-Z0-9!\p{L}@#$%^&*\s\/_-]*|)$")->get();

        // Đổi dir từng file
        foreach ($filesNeedChange as $key => $value) {
            $fileId = $value->id;
            $this->addItemViewer($fileId, $viewerName, 'file');
        }

        $folderNeedChange = Dir::where('owner', Auth::user()->name)->where('dir', 'regexp', "$dir([a-zA-Z0-9!\p{L}@#$%^&*\s\/_-]*|)$")->get();


        foreach ($folderNeedChange as $key => $value) {
            $fileId = $value->id;
            $this->addItemViewer($fileId, $viewerName, 'folder');
        }
    }

    public function addFriendCanViewFile(Request $request) {
        $this->validate($request, [
            'friendName'    => 'required',
            'fileDir'       => 'required'
        ]);

        $friendName = $request->friendName;
        $fileDir = $request->fileDir;

        if ($fileDir[strlen($fileDir)-1] === '/') {
            $fileDir = substr($fileDir,0,-1);

            $this->addViewer($fileDir, $friendName);

            return $friendName;
        }

        $fileTarget = Files::where('name', $fileDir)->get();

        $dataToSave = $fileTarget[0]->viewer . '|' . $friendName;

        $fileTarget[0]->viewer = $dataToSave;
        $fileTarget[0]->save();

        return $friendName;
    }

    public function getFriendsAllowedView(Request $request) {
        $fileDir = $request->fileDir;

        if ($fileDir[strlen($fileDir)-1] === '/') {
            $fileDir = substr($fileDir,0,-1);
            $friendAllowed = Dir::where('dir', $fileDir)->select('viewer')->get()[0];

            return $friendAllowed->viewer;
        }

        $friendAllowed = Files::where('name', $fileDir)->select('viewer')->get()[0];

        return $friendAllowed->viewer;
    }

    //Phần add friend cho thư mục
    public function removeItemViewer($fileId, $name, $type) {
        if ($type === 'folder') {
            $item = Dir::find($fileId);
        } else if ($type == 'file') {
            $item = Files::find($fileId);
        }

        $item->viewer = str_replace('|'.$name, '', $item->viewer);
        $item->save();
    }

    private function removeViewer($dir, $viewerName) {
        $fileDb = new Files;
        $dir = str_replace('/', '\/', $dir);
        $filesNeedChange = $fileDb->where('owner', Auth::user()->name)->where('dir', 'regexp', "$dir([a-zA-Z0-9!\p{L}@#$%^&*\s\/_-]*|)$")->get();

        // Đổi dir từng file
        foreach ($filesNeedChange as $key => $value) {
            $fileId = $value->id;
            $this->removeItemViewer($fileId, $viewerName, 'file');
        }

        $folderNeedChange = Dir::where('owner', Auth::user()->name)->where('dir', 'regexp', "$dir([a-zA-Z0-9!\p{L}@#$%^&*\s\/_-]*|)$")->get();


        foreach ($folderNeedChange as $key => $value) {
            $fileId = $value->id;
            $this->removeItemViewer($fileId, $viewerName, 'folder');
        }
    }

    public function removeFriendAdded(Request $request) {
        $this->validate($request, [
            'friendName'    => 'required',
            'fileDir'       => 'required'
        ]);

        $friendName = $request->friendName;
        $fileDir = $request->fileDir;

        if ($fileDir[strlen($fileDir)-1] === '/') {
            $fileDir = substr($fileDir,0,-1);

            $this->removeViewer($fileDir, $friendName);

            return 'Thành công';
        }

        $fileTarget = Files::where('name', $fileDir)->get()[0];

        $viewer = $this->removeIdUser($fileTarget->viewer, $friendName);

        $fileTarget->viewer = $viewer;
        $fileTarget->save();

        return 'Thành công';
    }

    public function getExerciseStatus(Request $request) {
        $fileSelected = $request->fileSelected;

        $fileExerciseStatus = Files::where('owner', Auth::user()->name)->where('name', $fileSelected)->get()[0]->is_exercise;

        return $fileExerciseStatus;
    }

    // Mark as exercise đối với file
    public function getExerciseStatusFolder(Request $request) {
        $folderSelected = $request->folderSelected;
        
        $folderExerciseStatus = Dir::where('owner', Auth::user()->name)->where('dir', $folderSelected)->get()[0]->is_exercise;

        return $folderExerciseStatus;
    }

    private function findIdFile($fileName) {
        return Files::where('owner', Auth::user()->name)->where('name', $fileName)->get()[0]->id;
    }

    private function changeExStatusFile($fileName, $exValue) {
        $exValue = filter_var($exValue, FILTER_VALIDATE_BOOLEAN);
        $file = Files::find($this->findIdFile($fileName));
        $file->is_exercise = $exValue;
        $file->save();

        return 'Thành công!';
    }

    public function changeExFileStatus(Request $request) {
        $exStatus = $request->exStatus;
        $fileSelected = $request->fileSelected;

        return $this->changeExStatusFile($fileSelected, $exStatus);

        return 'Thành công!';
    }

    // Mark as exercise đối với folder
    private function getAllChildFile($dir) {
        return Files::where('owner', Auth::user()->name)->where('dir', 'regexp', "$dir([a-zA-Z0-9!\p{L}@#$%^&*\s\/_-]*|)$")->get();
    }

    private function getAllChildFolder($dir) {
        return Dir::where('owner', Auth::user()->name)->where('dir', 'regexp', "$dir([a-zA-Z0-9!\p{L}@#$%^&*\s\/_-]*|)$")->get();
    }

    private function changeExFile($idFile, $exValue) {
        $exValue = filter_var($exValue, FILTER_VALIDATE_BOOLEAN);

        $file = Files::find($idFile);
        $file->is_exercise = $exValue;
        $file->save();

        return 'Thành công!';
    }

    private function changeExFolder($idFolder, $exValue) {
        $exValue = filter_var($exValue, FILTER_VALIDATE_BOOLEAN);

        $folder = Dir::find($idFolder);
        $folder->is_exercise = $exValue;
        $folder->save();

        return 'Thành công!';
    }

    private function changeExStatusChild($dirSelected, $exValue) {
        $allChildFile = $this->getAllChildFile($dirSelected);
        foreach ($allChildFile as $key => $childFile) {
            $this->changeExFile($childFile->id, $exValue);
        }

        $allChildFolder = $this->getAllChildFolder($dirSelected);
        foreach ($allChildFolder as $key => $childFolder) {
            $this->changeExFolder($childFolder->id, $exValue);
        }

        return 'Thành công!';
    }

    public function changeExFolderStatus(Request $request) {
        $exStatus = $request->exStatus;
        $dirSelected = $request->folderSelected;

        $this->changeExStatusChild($dirSelected, $exStatus);

        return 'Thành công!';
    }

    public function confirmExFile (Request $request) {
        $owner = $request->owner;
        $fileName = $request->fileName;

        return Files::where('owner', $owner)->where('name', $fileName)->get()[0];
    }

    public function confirmExFolder(Request $request) {
        $owner = $request->owner;
        $rootDir = $request->rootDir;

        return Dir::where('owner', $owner)->where('dir', $rootDir)->get()[0];
    }
}