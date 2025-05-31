<?php

namespace App\Helpers;

use Request;
use File;
use Illuminate\Support\Facades\Storage;
class UploadHelper
{

  /**
   * Upload Any Types of File.
   *
   * It's not checking the file type which may be checked before pass here in validation
   *
   * @param  string $f               request with file
   * @param  binary $file            file
   * @param  string $name            filename
   * @param  string $target_location location where file will store
   * @return string                  filename
   */
  public static function upload($f, $file, $name, $target_location)
  {
    if (Request::hasFile($f)) {
      $filename = $name . '.' . $file->getClientOriginalExtension();
      $extension = $file->getClientOriginalExtension();
      $file->move($target_location, $filename);
      return $filename;
    } else {
      return null;
    }
  }


  /**
   * Update File
   * @param  string $f               request with file
   * @param  binary $file            file
   * @param string $name             filename
   * @param  string $target_location location where file will store
   * @param  string $old_location    file location which will delete
   * @return string                  filename
   */
  public static function update($f, $file, $name, $target_location, $old_location)
  {
    //delete the old file
    $target_location = $target_location . '/';
    if (File::exists($target_location . $old_location)) {
      File::delete($target_location . $old_location);
    }

    $filename = $name . '.' . $file->getClientOriginalExtension();
    $file->move($target_location, $filename);
    return $filename;
  }


  /**
   * delete file
   * @param  type $location file location that will delete
   * @return type                  null
   */
  public static function deleteFile($location)
  {
    if (File::exists($location)) {
      File::delete($location);
    }
  }

  public static function subirArchivoAS3($folder, $file){

    $ruta_path = Storage::disk('s3')->put($folder, $file, 'public');

    return $ruta_path;

  }

  public static function subirArchivoLocal($folder, $file){
        // Guarda el archivo en storage/app/public/$folder con visibilidad pública
        $ruta_path = \Storage::disk('public')->putFile($folder, $file, 'public');

        return $ruta_path; // devuelve la ruta relativa, ejemplo: 'products/image1.jpg'
    }


  public static function descargarArchivoDesdeS3($extension,$rutaArchivo,$nombre_original)
    {

        //$url = Storage::disk('s3')->url($rutaArchivo);

        $file = Storage::disk('s3')->get($rutaArchivo);

        // Devuelve una respuesta de descarga con el contenido del archivo
        return response($file, 200)
            ->header('Content-Type', $extension)
            ->header('Content-Disposition', 'attachment; filename='.$nombre_original.'');
    }

   public static function deleteArchivoDesdeS3($rutaOriginal) {

        Storage::disk('s3')->delete($rutaOriginal);

        return true;

    }

    public static function copiarArchivoEnS3($rutaArchivoOriginal, $rutaNueva)
    {
        $file = Storage::disk('s3')->get($rutaArchivoOriginal);

        // $ruta_path = Storage::disk('s3')->copy($rutaArchivoOriginal, $rutaNueva);
        $ruta_path = Storage::disk('s3')->put($rutaNueva, $file);

        return $rutaNueva;

        // return UploadHelper::subirArchivoAS3($folder, $file);
    }
}
