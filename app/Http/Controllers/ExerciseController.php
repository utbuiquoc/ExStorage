<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use App\Files;
use App\Dir;
use App\User;
use App\Friends;

class ExerciseController extends FileController
{
    public function exercise() {
        return view('user-function.exercise', ['title' => 'Quản lí bài tập']);
    }

    private function addFile($ansJSON, $fileDir) {
        $file = Files::find(Files::where('name', $fileDir)->get()[0]->id);
        $file->exFile = $ansJSON;
        $file->save();

        return $file->exFile;
    }

    private function addFolder($ansJSON, $dir) {
        $folder = Dir::find(Dir::where('dir', $dir)->get()[0]->id);
        $folder->exFile = $ansJSON;
        $folder->save();

        return $folder->exFile;
    }

    public function getListFileUploaded(Request $request) {
        $fileName = $request->fileName;
        return Files::find(Files::where('name', $fileName)->get()[0]->id)->exFile;
    }
    
    public function getListFolderUploaded(Request $request) {
        $dir = $request->dir;
        return Dir::find(Dir::where('dir', $dir)->get()[0]->id)->exFile;
    }

    public function uploadAnswer(Request $request) {
        $ansJSON  = $request->answerUploadedJson;
        $fileName = $request->fileName;
        $fileDir  = $request->fileDir;
        $request->file('file')->move('fileUploaded', $fileName);

        return $this->addFile($ansJSON, $fileDir);
    }

    public function uploadAnswerFolder(Request $request) {
        $ansJSON  = $request->answerUploadedFolderJson;
        $fileName = $request->fileName;
        $dir      = $request->dir;
        $request->file('file')->move('fileUploaded', $fileName);

        return $this->addFolder($ansJSON, $dir);
    }

    public function removeAnsFile(Request $request) {
        $fileExJSON = $request->fileExJSON;
        $fileDir  = $request->fileDir;

        $file = Files::find(Files::where('name', $fileDir)->get()[0]->id);
        $file->exFile = $fileExJSON;
        $file->save();
        
        return 'Thành công!';
    }

    public function removeAnsFolder(Request $request) {
        $fileExJSON = $request->fileExJSON;
        $dir  = $request->dir;

        $folder = Dir::find(Dir::where('dir', $dir)->get()[0]->id);
        $folder->exFile = $fileExJSON;
        $folder->save();

        return 'Thành công!';
    }
}
