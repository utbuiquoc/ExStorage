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

    public function getListFileUploaded(Request $request) {
        $fileName = $request->fileName;
        return Files::find(Files::where('name', $fileName)->get()[0]->id)->exFile;
    }

    public function uploadAnswer(Request $request) {
        $ansJSON  = $request->answerUploadedJson;
        $fileName = $request->fileName;
        $fileDir  = $request->fileDir;
        $request->file('file')->move('fileUploaded', $fileName);

        return $this->addFile($ansJSON, $fileDir);
    }

    public function removeAnsFile(Request $request) {
        $fileExJSON = $request->fileExJSON;
        $fileDir  = $request->fileDir;

        $file = Files::find(Files::where('name', $fileDir)->get()[0]->id);
        $file->exFile = $fileExJSON;
        $file->save();
        
        return 'Thành công!';
    }
}
