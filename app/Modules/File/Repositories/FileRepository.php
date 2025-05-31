<?php

namespace App\Modules\File\Repositories;

use App\Helpers\UploadHelper;
use App\Modules\Auth\Models\User;
use App\Modules\File\Models\File;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FileRepository
{
    /**
     * Authenticated User Instance.
     *
     * @var User
     */
    public User | null $user;
    public $folder = "documents";
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->user = Auth::guard()->user();
    }

    public function copy($file_id) : File|null
    {
        $file = $this->getByID($file_id);

        if(is_null($file) == false){
            $data =  [];

            $data['user_id'] = $this->user->id;
            $data['nombre_original'] =  'Copia ' .$file->nombre_original;
            $data['tamanio'] =  $file->tamanio;
            $data['extension'] = $file->extension;
            $data['tipo'] = $file->tipo;
            $data['hash'] = Str::random(25);
            $unique = uniqid(). Str::random(25);
            $file_path = UploadHelper::copiarArchivoEnS3($file->ruta_url,$this->folder.'/'.$unique.'.'.$file->extension);//Storage::disk('s3')->put($folder, $file);

            if($file_path == false) {
                return null;
            }

            $data['ruta_url'] = $file_path;

            $obj = File::create($data);

            return $obj;
        }

        return null;

    }

    public function create($file) : File|null
    {
        $data =  [];

        $data['user_id'] = $this->user->id;
        $data['nombre_original'] =  $file->getClientOriginalName();
        $data['tamanio'] =  $file->getSize();
        $data['extension'] = $file->getClientOriginalExtension();
        $data['tipo'] = $file->getMimeType();
        $data['hash'] = Str::random(25);

        $file_path = UploadHelper::subirArchivoAS3($this->folder, $file);//Storage::disk('s3')->put($folder, $file);

        if($file_path == false) {
            return null;
        }

        $data['ruta_url'] = $file_path;


        $obj = File::create($data);

        return $this->getByID($obj['id']);

    }

    public function getByID(int $id): File|null
    {

        return File::with(['user' => function ($query) {
                    $query->select('id', 'name', 'surnames','email');
                },'status' => function ($query) {
                    $query->select('id', 'name');
                }])->find($id);

    }

    public function getByIDActive(int $id): File|null
    {

        return File::with(['user' => function ($query) {
                    $query->select('id', 'name', 'surnames','email');
                },'status' => function ($query) {
                    $query->select('id', 'name');
                }])->where('status_id', 1)->find($id);

    }

    public function download(int $id){

        $file = $this->getByID($id);

        if(is_null($file) == false){
            return UploadHelper::descargarArchivoDesdeS3($file->extension,$file->ruta_url,$file->nombre_original);
        }

        return null;
    }

    public function delete(int $id) {

        $result = false;

        $file = $this->getByID($id);

        if(is_null($file) == false){

            $result = UploadHelper::deleteArchivoDesdeS3($file->ruta_url);

            if($result){
                $file->update(['status_id' => 2]);
                return true;
            }
        }

        return $result;

    }

}
